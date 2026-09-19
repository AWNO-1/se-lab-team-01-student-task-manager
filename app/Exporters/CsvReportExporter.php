<?php

namespace App\Exporters;

class CsvReportExporter extends AbstractReportExporter
{
    public function getFormatName(): string
    {
        return 'CSV';
    }

    public function getContentType(): string
    {
        return 'text/csv';
    }

    protected function formatContent(array $rows): string
    {
        $output = fopen('php://temp', 'r+');

        // كتابة عناوين الأعمدة
        fputcsv($output, array_keys($rows[0]), ',', '"', '\\');

        // كتابة الصفوف
        foreach ($rows as $row) {
            fputcsv($output, array_values($row), ',', '"', '\\');
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv ?: '';
    }
}
