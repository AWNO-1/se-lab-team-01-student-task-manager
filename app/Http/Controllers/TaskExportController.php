<?php

namespace App\Http\Controllers;

use App\Contracts\ReportExporterInterface;
use App\Models\Task;
use Illuminate\Http\Response;

class TaskExportController extends Controller
{
    /**
     * حقن الاعتمادية عبر الـ Constructor (Constructor Dependency Injection)
     */
    public function __construct(
        protected ReportExporterInterface $exporter
    ) {
    }

    /**
     * تصدير جميع المهام بالصيغة المربوطة في Service Container.
     */
    public function export(): Response
    {
        $tasks = Task::query()->latest()->get();

        if ($tasks->isEmpty()) {
            return response('لا توجد مهام لتصديرها حالياً.', 404, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        }

        $rows = $tasks->map(fn (Task $task) => [
            'المعرف' => $task->id,
            'عنوان المهمة' => $task->title,
            'الوصف' => $task->description ?? 'بدون وصف',
            'الحالة' => $task->is_completed ? 'مكتملة' : 'قيد التنفيذ',
            'تاريخ الإنشاء' => $task->created_at?->format('Y-m-d H:i') ?? '',
        ])->all();

        $content = $this->exporter->export($rows, 'مهام_الطلاب');
        $format = strtolower($this->exporter->getFormatName());
        $filename = "tasks_report_" . date('Ymd_His') . ".{$format}";

        return response($content, 200, [
            'Content-Type' => $this->exporter->getContentType() . '; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
