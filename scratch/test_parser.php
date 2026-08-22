<?php

require __DIR__.'/../vendor/autoload.php';

function parseExcelOrCsv($filePath)
{
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $rows = [];

    if ($extension === 'csv' || $extension === 'txt') {
        if (($handle = fopen($filePath, 'r')) !== false) {
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ((strpos($firstLine, "\t") !== false) ? "\t" : ',');

            while (($data = fgetcsv($handle, 2000, $delimiter)) !== false) {
                if (array_filter($data)) {
                    $rows[] = array_map('trim', $data);
                }
            }
            fclose($handle);
        }
    } elseif ($extension === 'xlsx') {
        $zip = new ZipArchive;
        if ($zip->open($filePath) === true) {
            $sharedStrings = [];
            if (($sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
                $xml = simplexml_load_string($sharedStringsXml);
                if ($xml && isset($xml->si)) {
                    foreach ($xml->si as $val) {
                        $text = '';
                        if (isset($val->t)) {
                            $text = (string) $val->t;
                        } elseif (isset($val->r)) {
                            foreach ($val->r as $run) {
                                $text .= (string) $run->t;
                            }
                        }
                        $sharedStrings[] = $text;
                    }
                }
            }

            if (($sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml')) !== false) {
                $xml = simplexml_load_string($sheetXml);
                if ($xml && isset($xml->sheetData->row)) {
                    foreach ($xml->sheetData->row as $row) {
                        $rowCells = [];
                        foreach ($row->c as $cell) {
                            $cellType = (string) $cell['t'];
                            $val = (string) $cell->v;
                            if ($cellType === 's' && isset($sharedStrings[(int) $val])) {
                                $val = $sharedStrings[(int) $val];
                            }
                            $rowCells[] = trim($val);
                        }
                        if (array_filter($rowCells)) {
                            $rows[] = $rowCells;
                        }
                    }
                }
            }
            $zip->close();
        }
    }

    return $rows;
}

echo "Function parseExcelOrCsv initialized OK!\n";
