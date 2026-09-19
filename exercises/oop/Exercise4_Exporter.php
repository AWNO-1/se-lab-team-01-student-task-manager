<?php

namespace Exercises\Oop;

/**
 * تمرين 4: Interface للتصدير (ReportExporterInterface)
 * المطلوب: إنشاء Interface باسم ReportExporterInterface تحتوي على export(array $data): string
 * وتطبيقها في فئتي JsonExporter و CsvExporter مع دالة تعمل بتعدد الأشكال (Polymorphism).
 */
interface ReportExporterInterface
{
    /**
     * @param array<int, array<string, mixed>> $data
     */
    public function export(array $data): string;

    public function getContentType(): string;
}

class JsonExporter implements ReportExporterInterface
{
    public function export(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function getContentType(): string
    {
        return 'application/json';
    }
}

class CsvExporter implements ReportExporterInterface
{
    public function export(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $output = fopen('php://temp', 'r+');
        // كتابة عناوين الأعمدة (Header)
        fputcsv($output, array_keys($data[0]), ',', '"', '\\');

        // كتابة الصفوف
        foreach ($data as $row) {
            fputcsv($output, array_values($row), ',', '"', '\\');
        }

        rewind($output);
        $csvString = stream_get_contents($output);
        fclose($output);

        return $csvString ?: '';
    }

    public function getContentType(): string
    {
        return 'text/csv';
    }
}

/**
 * دالة استهلاك تعتمد كلياً على العقد (Interface) وتدعم تعدد الأشكال
 */
class ReportService
{
    public function __construct(
        private ReportExporterInterface $exporter
    ) {
    }

    public function generate(array $data): string
    {
        return $this->exporter->export($data);
    }

    public function getMimeType(): string
    {
        return $this->exporter->getContentType();
    }
}

// تنفيذ تجريبي عند استدعاء الملف مباشرة
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $sampleTasks = [
        ['id' => 1, 'title' => 'تسليم المعمل الأول', 'status' => 'مكتمل'],
        ['id' => 2, 'title' => 'دراسة اختبار OOP', 'status' => 'قيد التنفيذ'],
    ];

    $jsonService = new ReportService(new JsonExporter());
    $csvService = new ReportService(new CsvExporter());

    echo "--- تصدير JSON ---" . PHP_EOL;
    echo $jsonService->generate($sampleTasks) . PHP_EOL;

    echo "--- تصدير CSV ---" . PHP_EOL;
    echo $csvService->generate($sampleTasks) . PHP_EOL;
}
