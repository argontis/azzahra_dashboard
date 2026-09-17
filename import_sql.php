<?php

/**
 * Script to convert and import MySQL dump (azzahra2_azza(1).sql) into SQLite.
 * Also ensures Laravel 11 infrastructure tables (sessions, users, cache, jobs).
 * Usage: php import_sql.php
 */

$sqlFile = __DIR__ . DIRECTORY_SEPARATOR . 'azzahra2_azza(1).sql';
$dbFile = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';

if (!file_exists($sqlFile)) {
    echo "[ERROR] File {$sqlFile} not found.\n";
    exit(1);
}

if (!is_dir(dirname($dbFile))) {
    mkdir(dirname($dbFile), 0777, true);
}

if (file_exists($dbFile)) {
    copy($dbFile, $dbFile . '.bak');
    echo "[INFO] Existing SQLite database backed up to database.sqlite.bak\n";
}

echo "[INFO] Reading {$sqlFile}...\n";
$content = file_get_contents($sqlFile);

echo "[INFO] Parsing schema metadata (Primary Keys, Auto Increments, Indexes)...\n";
$pkMap = [];
$autoIncMap = [];
$indexes = [];

if (preg_match_all('/ALTER\s+TABLE\s+[`"]?(\w+)[`"]?\s+([^;]+);/i', $content, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $m) {
        $tbl = $m[1];
        $body = $m[2];

        if (preg_match('/ADD\s+PRIMARY\s+KEY\s*\(([^)]+)\)/i', $body, $pkMatch)) {
            $pkMap[$tbl] = array_map(fn($c) => trim($c, "`\" \t\n\r"), explode(',', $pkMatch[1]));
        }

        if (preg_match('/MODIFY\s+[`"]?(\w+)[`"]?[^;]*AUTO_INCREMENT/i', $body, $aiMatch)) {
            $autoIncMap[$tbl] = $aiMatch[1];
        }

        if (preg_match_all('/ADD\s+(UNIQUE\s+)?(?:KEY|INDEX)\s*[`"]?(\w+)?[`"]?\s*\(([^)]+)\)/i', $body, $clMatches, PREG_SET_ORDER)) {
            foreach ($clMatches as $c) {
                $indexes[] = [
                    'table' => $tbl,
                    'is_unique' => !empty($c[1]),
                    'name' => $c[2] ?: 'idx_' . $tbl . '_' . count($indexes),
                    'cols' => trim($c[3]),
                ];
            }
        }
    }
}

echo "[INFO] Detected " . count($pkMap) . " Primary Keys, " . count($autoIncMap) . " AUTO_INCREMENTs, " . count($indexes) . " Indexes.\n";

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("PRAGMA foreign_keys = OFF;");
$pdo->exec("PRAGMA journal_mode = MEMORY;");
$pdo->exec("PRAGMA synchronous = OFF;");

echo "[INFO] Parsing and creating tables in SQLite...\n";
$createdCount = 0;

if (preg_match_all('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`"]?(\w+)[`"]?\s*\((.*?)\)\s*(?:ENGINE\s*=[^;]*)?;/is', $content, $tableMatches, PREG_SET_ORDER)) {
    foreach ($tableMatches as $tm) {
        $tableName = $tm[1];
        $body = $tm[2];

        $pdo->exec("DROP TABLE IF EXISTS `{$tableName}`;");

        $lines = preg_split('/\r\n|\r|\n/', $body);
        $colLines = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || preg_match('/^(PRIMARY\s+KEY|KEY|INDEX|UNIQUE|CONSTRAINT)/i', $line)) {
                continue;
            }

            $line = trim($line, ", \t\n\r");

            $line = preg_replace('/enum\s*\([^)]*\)/i', 'TEXT', $line);
            $line = preg_replace('/ON\s+UPDATE\s+current_timestamp\(\)/i', '', $line);
            $line = preg_replace('/current_timestamp\(\)/i', 'CURRENT_TIMESTAMP', $line);
            $line = preg_replace('/COLLATE\s+\w+/i', '', $line);
            $line = preg_replace('/(?:CHARACTER\s+SET|CHARSET)\s+\w+/i', '', $line);
            $line = preg_replace('/\bUNSIGNED\b/i', '', $line);
            $line = preg_replace('/\bZEROFILL\b/i', '', $line);

            if (preg_match('/^[`"]?(\w+)[`"]?\s+([\w()]+)(.*)$/', $line, $cm)) {
                $cName = $cm[1];
                if ((isset($autoIncMap[$tableName]) && $autoIncMap[$tableName] === $cName) || stripos($line, 'AUTO_INCREMENT') !== false) {
                    $line = "`{$cName}` INTEGER PRIMARY KEY AUTOINCREMENT";
                }
            }

            $colLines[] = $line;
        }

        if (isset($pkMap[$tableName]) && !isset($autoIncMap[$tableName])) {
            $pkCols = implode(', ', array_map(fn($c) => "`$c`", $pkMap[$tableName]));
            $colLines[] = "PRIMARY KEY ({$pkCols})";
        }

        $newBody = implode(",\n  ", $colLines);
        $createSql = "CREATE TABLE `{$tableName}` (\n  {$newBody}\n);";

        try {
            $pdo->exec($createSql);
            $createdCount++;
        } catch (Exception $e) {
            echo "[WARN] Error creating table `{$tableName}`: " . $e->getMessage() . "\n";
        }
    }
}
echo "[INFO] Successfully created {$createdCount} tables.\n";

echo "[INFO] Importing data rows...\n";
$pdo->beginTransaction();

$lines = preg_split('/\r\n|\r|\n/', $content);
$inInsert = false;
$currentInsert = [];
$insertCount = 0;

foreach ($lines as $line) {
    $trimmed = trim($line);
    if (!$inInsert) {
        if (stripos($trimmed, 'INSERT INTO') === 0) {
            $inInsert = true;
            $currentInsert = [$line];
            if (str_ends_with($trimmed, ');')) {
                $stmt = implode("\n", $currentInsert);
                $stmt = preg_replace('/^INSERT\s+INTO/i', 'INSERT OR IGNORE INTO', $stmt);
                $stmt = str_replace(['\\\\', "\\'", '\\"'], ['__BSLASH__', "''", '"'], $stmt);
                $stmt = str_replace('__BSLASH__', '\\', $stmt);
                try {
                    $pdo->exec($stmt);
                    $insertCount++;
                } catch (Exception $e) {
                    echo "[WARN] Insert error: " . $e->getMessage() . "\n";
                }
                $inInsert = false;
                $currentInsert = [];
            }
        }
    } else {
        $currentInsert[] = $line;
        if (str_ends_with($trimmed, ');')) {
            $stmt = implode("\n", $currentInsert);
            $stmt = preg_replace('/^INSERT\s+INTO/i', 'INSERT OR IGNORE INTO', $stmt);
            $stmt = str_replace(['\\\\', "\\'", '\\"'], ['__BSLASH__', "''", '"'], $stmt);
            $stmt = str_replace('__BSLASH__', '\\', $stmt);
            try {
                $pdo->exec($stmt);
                $insertCount++;
            } catch (Exception $e) {
                echo "[WARN] Insert error: " . $e->getMessage() . "\n";
            }
            $inInsert = false;
            $currentInsert = [];
        }
    }
}
$pdo->commit();
echo "[INFO] Executed {$insertCount} insert statements.\n";

echo "[INFO] Creating indexes...\n";
$idxCount = 0;
foreach ($indexes as $idx) {
    $colsClean = preg_replace('/\(\d+\)/', '', $idx['cols']);
    $uStr = $idx['is_unique'] ? 'UNIQUE ' : '';
    $safeName = "idx_{$idx['table']}_{$idx['name']}";
    $createIdx = "CREATE {$uStr}INDEX IF NOT EXISTS `{$safeName}` ON `{$idx['table']}` ({$colsClean});";
    try {
        $pdo->exec($createIdx);
        $idxCount++;
    } catch (Exception) {
    }
}
echo "[INFO] Created {$idxCount} indexes.\n";

echo "[INFO] Ensuring Laravel infrastructure tables...\n";
$laravelSql = "
CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` DATETIME NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
);
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email` VARCHAR(255) PRIMARY KEY,
    `token` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NULL
);
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` VARCHAR(255) PRIMARY KEY,
    `user_id` INTEGER NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` TEXT NOT NULL,
    `last_activity` INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS `idx_sessions_user_id` ON `sessions` (`user_id`);
CREATE INDEX IF NOT EXISTS `idx_sessions_last_activity` ON `sessions` (`last_activity`);
CREATE TABLE IF NOT EXISTS `cache` (
    `key` VARCHAR(255) PRIMARY KEY,
    `value` TEXT NOT NULL,
    `expiration` INTEGER NOT NULL
);
CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` VARCHAR(255) PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INTEGER NOT NULL
);
CREATE TABLE IF NOT EXISTS `jobs` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` TEXT NOT NULL,
    `attempts` INTEGER NOT NULL,
    `reserved_at` INTEGER NULL,
    `available_at` INTEGER NOT NULL,
    `created_at` INTEGER NOT NULL
);
CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` VARCHAR(255) PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INTEGER NOT NULL,
    `pending_jobs` INTEGER NOT NULL,
    `failed_jobs` INTEGER NOT NULL,
    `failed_job_ids` TEXT NOT NULL,
    `options` TEXT NULL,
    `cancelled_at` INTEGER NULL,
    `created_at` INTEGER NOT NULL,
    `finished_at` INTEGER NULL
);
CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `uuid` VARCHAR(255) NOT NULL UNIQUE,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` TEXT NOT NULL,
    `exception` TEXT NOT NULL,
    `failed_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
$pdo->exec($laravelSql);

// Ensure Laravel compatibility columns on legacy tables
$colsKaryawan = $pdo->query("PRAGMA table_info(`karyawan`)")->fetchAll(PDO::FETCH_ASSOC);
$colNamesKaryawan = array_column($colsKaryawan, 'name');

if (!in_array('created_at', $colNamesKaryawan)) {
    $pdo->exec("ALTER TABLE `karyawan` ADD COLUMN `created_at` DATETIME NULL;");
}
if (!in_array('updated_at', $colNamesKaryawan)) {
    $pdo->exec("ALTER TABLE `karyawan` ADD COLUMN `updated_at` DATETIME NULL;");
}
if (!in_array('kry_status', $colNamesKaryawan)) {
    $pdo->exec("ALTER TABLE `karyawan` ADD COLUMN `kry_status` INTEGER DEFAULT 1;");
}
if (!in_array('kry_telp', $colNamesKaryawan)) {
    $pdo->exec("ALTER TABLE `karyawan` ADD COLUMN `kry_telp` VARCHAR(20) NULL;");
    if (in_array('kry_tlp', $colNamesKaryawan)) {
        $pdo->exec("UPDATE `karyawan` SET `kry_telp` = `kry_tlp` WHERE `kry_telp` IS NULL;");
    }
}
if (!in_array('kry_join_date', $colNamesKaryawan)) {
    $pdo->exec("ALTER TABLE `karyawan` ADD COLUMN `kry_join_date` DATE NULL;");
    if (in_array('kry_tgl_masuk', $colNamesKaryawan)) {
        $pdo->exec("UPDATE `karyawan` SET `kry_join_date` = `kry_tgl_masuk` WHERE `kry_join_date` IS NULL;");
    }
}

// Synchronize Laravel migrations
$maxBatch = (int) $pdo->query("SELECT COALESCE(MAX(batch), 0) FROM `migrations`")->fetchColumn();
$newBatch = $maxBatch + 1;
$stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM `migrations` WHERE `migration` = ?");
$stmtInsert = $pdo->prepare("INSERT INTO `migrations` (`migration`, `batch`) VALUES (?, ?)");

foreach ([
    '0001_01_01_000000_create_users_table',
    '0001_01_01_000001_create_cache_table',
    '0001_01_01_000002_create_jobs_table',
] as $mig) {
    $stmtCheck->execute([$mig]);
    if ($stmtCheck->fetchColumn() == 0) {
        $stmtInsert->execute([$mig, $newBatch]);
    }
}

$pdo->exec("PRAGMA synchronous = NORMAL;");

echo "\n[SUMMARY] Database now contains tables:\n";
$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) {
    if (str_starts_with($t, 'sqlite_')) continue;
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM `{$t}`")->fetchColumn();
        echo "  - {$t}: {$count} rows\n";
    } catch (Exception) {
    }
}

echo "\n[SUCCESS] Successfully converted azzahra2_azza(1).sql into database/database.sqlite with all Laravel tables!\n";
