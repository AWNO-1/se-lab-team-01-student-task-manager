<?php

namespace App\Contracts;

interface ReportExporterInterface
{
    /**
     * تصدير مصفوفة البيانات إلى نص منسق.
     *
     * @param array<int, array<string, mixed>> $rows
     */
    public function export(array $rows, string $reportName): string;

    public function getFormatName(): string;

    public function getContentType(): string;
}
