<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogsReportExports
{
    public function logExport(string $reportName, int $rowsCount, string $format): void
    {
        Log::info("تم تصدير تقرير [{$reportName}] بنجاح بعدد [{$rowsCount}] سجل بصيغة [{$format}].");
    }
}
