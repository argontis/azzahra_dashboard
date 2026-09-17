<?php

/**
 * Quick patch to add Xendit tracking columns to `transaksi_detail` table in SQLite
 * Usage: php fix_xendit_sqlite.php
 */
$dbFile = __DIR__.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'database.sqlite';

if (! file_exists($dbFile)) {
    echo "[ERROR] database.sqlite not found at {$dbFile}\n";
    exit(1);
}

$pdo = new PDO('sqlite:'.$dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$cols = $pdo->query('PRAGMA table_info(`transaksi_detail`)')->fetchAll(PDO::FETCH_ASSOC);
$colNames = array_column($cols, 'name');

echo '[INFO] Existing columns in `transaksi_detail`: '.implode(', ', $colNames)."\n";

$added = 0;

if (! in_array('xendit_invoice_id', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `xendit_invoice_id` VARCHAR(100) NULL;');
    echo "  + Added column `xendit_invoice_id`\n";
    $added++;
}

if (! in_array('xendit_invoice_url', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `xendit_invoice_url` TEXT NULL;');
    echo "  + Added column `xendit_invoice_url`\n";
    $added++;
}

if (! in_array('xendit_status', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `xendit_status` VARCHAR(50) NULL;');
    echo "  + Added column `xendit_status`\n";
    $added++;
}

if (! in_array('xendit_payment_method', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `xendit_payment_method` VARCHAR(50) NULL;');
    echo "  + Added column `xendit_payment_method`\n";
    $added++;
}

if (! in_array('xendit_paid_at', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `xendit_paid_at` DATETIME NULL;');
    echo "  + Added column `xendit_paid_at`\n";
    $added++;
}

if (! in_array('dtl_payment_method', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `dtl_payment_method` VARCHAR(50) NULL;');
    echo "  + Added column `dtl_payment_method`\n";
    $added++;
}

if (! in_array('created_at', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `created_at` DATETIME NULL;');
    echo "  + Added column `created_at`\n";
    $added++;
}

if (! in_array('updated_at', $colNames)) {
    $pdo->exec('ALTER TABLE `transaksi_detail` ADD COLUMN `updated_at` DATETIME NULL;');
    echo "  + Added column `updated_at`\n";
    $added++;
}

// Ensure `transaksi` table also has timestamps
$colsTransaksi = $pdo->query('PRAGMA table_info(`transaksi`)')->fetchAll(PDO::FETCH_ASSOC);
$colNamesTransaksi = array_column($colsTransaksi, 'name');

if (! in_array('created_at', $colNamesTransaksi)) {
    $pdo->exec('ALTER TABLE `transaksi` ADD COLUMN `created_at` DATETIME NULL;');
    echo "  + Added column `created_at` to `transaksi`\n";
}

if (! in_array('updated_at', $colNamesTransaksi)) {
    $pdo->exec('ALTER TABLE `transaksi` ADD COLUMN `updated_at` DATETIME NULL;');
    echo "  + Added column `updated_at` to `transaksi`\n";
}

if (! in_array('tipe_layanan', $colNamesTransaksi)) {
    $pdo->exec("ALTER TABLE `transaksi` ADD COLUMN `tipe_layanan` VARCHAR(30) DEFAULT 'walk-in';");
    echo "  + Added column `tipe_layanan` to `transaksi`\n";
}

// Ensure `costomer` table has scoring columns
$colsCostomer = $pdo->query('PRAGMA table_info(`costomer`)')->fetchAll(PDO::FETCH_ASSOC);
$colNamesCostomer = array_column($colsCostomer, 'name');

if (! in_array('cos_score', $colNamesCostomer)) {
    $pdo->exec('ALTER TABLE `costomer` ADD COLUMN `cos_score` INTEGER DEFAULT 1;');
    echo "  + Added column `cos_score` to `costomer`\n";
}

if (! in_array('cos_tier', $colNamesCostomer)) {
    $pdo->exec("ALTER TABLE `costomer` ADD COLUMN `cos_tier` VARCHAR(20) DEFAULT 'reguler';");
    echo "  + Added column `cos_tier` to `costomer`\n";
}

if (! in_array('total_transaksi', $colNamesCostomer)) {
    $pdo->exec('ALTER TABLE `costomer` ADD COLUMN `total_transaksi` INTEGER DEFAULT 0;');
    echo "  + Added column `total_transaksi` to `costomer`\n";
}

echo "[SUCCESS] Finished checking schema. Added {$added} new columns to transaksi_detail.\n";
