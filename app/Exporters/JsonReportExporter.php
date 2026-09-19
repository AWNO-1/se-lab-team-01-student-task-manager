<?php

namespace App\Exporters;

class JsonReportExporter extends AbstractReportExporter
{
    public function getFormatName(): string
    {
        return 'JSON';
    }

    public function getContentType(): string
    {
        return 'application/json';
    }

    protected function formatContent(array $rows): string
    {
        return json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '';
    }
}
