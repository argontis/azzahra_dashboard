<?php

/**
 * Quick patch to add missing columns to `karyawan` table in database/database.sqlite
 * Usage: php fix_karyawan_sqlite.php
 */
$dbFile = __DIR__.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'database.sqlite';

if (! file_exists($dbFile)) {
    echo "[ERROR] database.sqlite not found at {$dbFile}\n";
    exit(1);
}

$pdo = new PDO('sqlite:'.$dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$colsKaryawan = $pdo->query('PRAGMA table_info(`karyawan`)')->fetchAll(PDO::FETCH_ASSOC);
$colNamesKaryawan = array_column($colsKaryawan, 'name');

echo '[INFO] Existing columns in `karyawan`: '.implode(', ', $colNamesKaryawan)."\n";

$added = 0;

if (! in_array('created_at', $colNamesKaryawan)) {
    $pdo->exec('ALTER TABLE `karyawan` ADD COLUMN `created_at` DATETIME NULL;');
    echo "  + Added column `created_at`\n";
    $added++;
}

if (! in_array('updated_at', $colNamesKaryawan)) {
    $pdo->exec('ALTER TABLE `karyawan` ADD COLUMN `updated_at` DATETIME NULL;');
    echo "  + Added column `updated_at`\n";
    $added++;
}

if (! in_array('kry_status', $colNamesKaryawan)) {
    $pdo->exec('ALTER TABLE `karyawan` ADD COLUMN `kry_status` INTEGER DEFAULT 1;');
    echo "  + Added column `kry_status` (default 1)\n";
    $added++;
}

if (! in_array('kry_telp', $colNamesKaryawan)) {
    $pdo->exec('ALTER TABLE `karyawan` ADD COLUMN `kry_telp` VARCHAR(20) NULL;');
    echo "  + Added column `kry_telp`\n";
    $added++;
    if (in_array('kry_tlp', $colNamesKaryawan)) {
        $pdo->exec('UPDATE `karyawan` SET `kry_telp` = `kry_tlp` WHERE `kry_telp` IS NULL;');
        echo "  * Copied data from `kry_tlp` to `kry_telp`\n";
    }
}

if (! in_array('kry_join_date', $colNamesKaryawan)) {
    $pdo->exec('ALTER TABLE `karyawan` ADD COLUMN `kry_join_date` DATE NULL;');
    echo "  + Added column `kry_join_date`\n";
    $added++;
    if (in_array('kry_tgl_masuk', $colNamesKaryawan)) {
        $pdo->exec('UPDATE `karyawan` SET `kry_join_date` = `kry_tgl_masuk` WHERE `kry_join_date` IS NULL;');
        echo "  * Copied data from `kry_tgl_masuk` to `kry_join_date`\n";
    }
}

if ($added === 0) {
    echo "[SUCCESS] All columns already exist in `karyawan` table.\n";
} else {
    echo "[SUCCESS] Successfully updated `karyawan` schema with {$added} new columns.\n";
}
