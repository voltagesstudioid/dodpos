<?php

namespace App\Support\Export;

class SimpleXlsxWriter
{
    public static function buildBinary(array $rows): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'dodpos_xlsx_');
        if (! is_string($tmp) || $tmp === '') {
            throw new \RuntimeException('Gagal membuat file sementara untuk XLSX.');
        }

        $zip = new \ZipArchive;
        $ok = $zip->open($tmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        if ($ok !== true) {
            @unlink($tmp);
            throw new \RuntimeException('Gagal membuat arsip XLSX.');
        }

        $zip->addFromString('[Content_Types].xml', self::contentTypesXml());
        $zip->addFromString('_rels/.rels', self::relsXml());
        $zip->addFromString('xl/workbook.xml', self::workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRelsXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheetXml($rows));

        $zip->close();

        $bin = (string) file_get_contents($tmp);
        @unlink($tmp);

        return $bin;
    }

    private static function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'</Types>';
    }

    private static function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    private static function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            .' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets>'
            .'<sheet name="Sheet1" sheetId="1" r:id="rId1"/>'
            .'</sheets>'
            .'</workbook>';
    }

    private static function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'</Relationships>';
    }

    private static function sheetXml(array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<sheetData>';

        $r = 1;
        foreach ($rows as $row) {
            if (! is_array($row)) {
                $row = [$row];
            }

            $xml .= '<row r="'.$r.'">';
            $c = 1;
            foreach ($row as $val) {
                $cellRef = self::colIndexToLetters($c).$r;
                $xml .= self::cellXml($cellRef, $val);
                $c++;
            }
            $xml .= '</row>';
            $r++;
        }

        $xml .= '</sheetData></worksheet>';

        return $xml;
    }

    private static function cellXml(string $ref, mixed $value): string
    {
        if (is_int($value) || is_float($value)) {
            $v = (string) $value;

            return '<c r="'.$ref.'"><v>'.self::xmlEscape($v).'</v></c>';
        }

        if (is_bool($value)) {
            return '<c r="'.$ref.'" t="b"><v>'.($value ? '1' : '0').'</v></c>';
        }

        $s = trim((string) $value);
        if ($s !== '' && preg_match('/^-?\d+(\.\d+)?$/', $s) === 1) {
            return '<c r="'.$ref.'"><v>'.self::xmlEscape($s).'</v></c>';
        }

        return '<c r="'.$ref.'" t="inlineStr"><is><t>'.self::xmlEscape($s).'</t></is></c>';
    }

    private static function colIndexToLetters(int $index): string
    {
        $s = '';
        while ($index > 0) {
            $index--;
            $s = chr(65 + ($index % 26)).$s;
            $index = intdiv($index, 26);
        }

        return $s;
    }

    private static function xmlEscape(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
