<?php

namespace App\Exporters;

use App\Contracts\ReportExporterInterface;
use App\Traits\LogsReportExports;
use InvalidArgumentException;

abstract class AbstractReportExporter implements ReportExporterInterface
{
    use LogsReportExports;

    /**
     * Template Method ينسق سير عملية التصدير المشتركة:
     * 1. التحقق من وجود بيانات
     * 2. تحويل البيانات للصيغة المطلوبة
     * 3. تسجيل العملية
     */
    public function export(array $rows, string $reportName): string
    {
        $this->validateRows($rows);

        $content = $this->formatContent($rows);

        $this->logExport($reportName, count($rows), $this->getFormatName());

        return $content;
    }

    protected function validateRows(array $rows): void
    {
        if (empty($rows)) {
            throw new InvalidArgumentException('لا توجد بيانات لتصدير التقرير.');
        }
    }

    abstract protected function formatContent(array $rows): string;

    abstract public function getFormatName(): string;

    abstract public function getContentType(): string;
}
