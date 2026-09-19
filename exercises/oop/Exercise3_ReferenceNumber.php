<?php

namespace Exercises\Oop;

/**
 * تمرين 3: Trait لتوليد الأرقام المرجعية (GeneratesReferenceNumber)
 * المطلوب: إنشاء Trait باسم GeneratesReferenceNumber تحتوي على دالة توليد رقم مرجعي
 * واستخدامها داخل فئتي MaintenanceRequest و TrainingApplication.
 */
trait GeneratesReferenceNumber
{
    public function generateReferenceNumber(string $prefix): string
    {
        return sprintf(
            '%s-%s-%04d',
            strtoupper(trim($prefix)),
            date('Ymd'),
            random_int(1000, 9999)
        );
    }
}

class MaintenanceRequest
{
    use GeneratesReferenceNumber;

    private string $referenceNumber;

    public function __construct(
        private string $deviceTitle,
        private string $issueDescription
    ) {
        $this->referenceNumber = $this->generateReferenceNumber('MNT');
    }

    public function getDetails(): string
    {
        return "[طلب صيانة] رقم: {$this->referenceNumber} | الجهاز: {$this->deviceTitle} | العطل: {$this->issueDescription}";
    }

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }
}

class TrainingApplication
{
    use GeneratesReferenceNumber;

    private string $referenceNumber;

    public function __construct(
        private string $traineeName,
        private string $courseName
    ) {
        $this->referenceNumber = $this->generateReferenceNumber('TRN');
    }

    public function getDetails(): string
    {
        return "[طلب تدريب] رقم: {$this->referenceNumber} | المتدرب: {$this->traineeName} | المقرر: {$this->courseName}";
    }

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }
}

// تنفيذ تجريبي عند استدعاء الملف مباشرة
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $maintenance = new MaintenanceRequest('حاسوب محمول Dell', 'الشاشة لا تعمل');
    $training = new TrainingApplication('سارة أحمد', 'تطوير الويب بلارافيل');

    echo $maintenance->getDetails() . PHP_EOL;
    echo $training->getDetails() . PHP_EOL;
}
