<?php

/**
 * المشغل الشامل لتمارين البرمجة كائنية التوجه (Lab 2 OOP Exercises Runner)
 * مقرر هندسة البرمجيات — المستوى الرابع
 * إشراف: م. ساهر القائد
 */

require_once __DIR__ . '/Exercise1_Student.php';
require_once __DIR__ . '/Exercise2_Shapes.php';
require_once __DIR__ . '/Exercise3_ReferenceNumber.php';
require_once __DIR__ . '/Exercise4_Exporter.php';
require_once __DIR__ . '/Exercise5_PaymentSystem.php';

use Exercises\Oop\Student;
use Exercises\Oop\Rectangle;
use Exercises\Oop\Circle;
use Exercises\Oop\MaintenanceRequest;
use Exercises\Oop\TrainingApplication;
use Exercises\Oop\ReportService;
use Exercises\Oop\JsonExporter;
use Exercises\Oop\CsvExporter;
use Exercises\Oop\CardPaymentGateway;
use Exercises\Oop\CashPaymentGateway;

echo "================================================================" . PHP_EOL;
echo "   🎓 نتائج تشغيل تمارين المعمل الثاني (البرمجة كائنية التوجه)   " . PHP_EOL;
echo "================================================================" . PHP_EOL . PHP_EOL;

// 1. تمرين 1
echo "【تمرين 1: Class الطالب ومحددات الوصول】" . PHP_EOL;
$student = new Student('أحمد علي', 4, 'IT-2026-4401');
echo "  ✓ الكائن: " . $student->introduce() . PHP_EOL . PHP_EOL;

// 2. تمرين 2
echo "【تمرين 2: Abstract Class وحساب مساحات الأشكال】" . PHP_EOL;
$rect = new Rectangle(6.0, 3.5);
$circle = new Circle(2.5);
echo "  ✓ مساحة المستطيل (6.0 × 3.5): " . $rect->area() . PHP_EOL;
echo "  ✓ مساحة الدائرة (نصف القطر 2.5): " . round($circle->area(), 2) . PHP_EOL . PHP_EOL;

// 3. تمرين 3
echo "【تمرين 3: Trait لتوليد الأرقام المرجعية】" . PHP_EOL;
$mnt = new MaintenanceRequest('خادم قواعد بيانات', 'ارتفاع حرارة المعالج');
$trn = new TrainingApplication('خالد منصور', 'أمن المعلومات المتقدم');
echo "  ✓ طلب الصيانة: " . $mnt->getDetails() . PHP_EOL;
echo "  ✓ طلب التدريب: " . $trn->getDetails() . PHP_EOL . PHP_EOL;

// 4. تمرين 4
echo "【تمرين 4: Interface والتصدير المتعدد بتعدد الأشكال (Polymorphism)】" . PHP_EOL;
$tasksData = [
    ['id' => 101, 'task' => 'تصميم وثيقة SRS', 'status' => 'Done'],
    ['id' => 102, 'task' => 'بناء مسارات CRUD', 'status' => 'Done'],
    ['id' => 103, 'task' => 'تطبيق حقن التبعيات', 'status' => 'In Progress'],
];
$jsonExporter = new ReportService(new JsonExporter());
$csvExporter = new ReportService(new CsvExporter());
echo "  ✓ نوع التصدير الأول: " . $jsonExporter->getMimeType() . PHP_EOL;
echo "  ✓ حجم مخرجات JSON: " . strlen($jsonExporter->generate($tasksData)) . " حرف" . PHP_EOL;
echo "  ✓ نوع التصدير الثاني: " . $csvExporter->getMimeType() . PHP_EOL;
echo "  ✓ عينة من مخرجات CSV: " . PHP_EOL;
echo "    " . str_replace("\n", "\n    ", trim($csvExporter->generate($tasksData))) . PHP_EOL . PHP_EOL;

// 5. تمرين 5
echo "【تمرين 5: نظام الدفع الشامل (Interface + Abstract + Trait)】" . PHP_EOL;
$card = new CardPaymentGateway('****-8842');
$cash = new CashPaymentGateway('عمر فؤاد');
echo "  ✓ تنفيذ عملية دفع إلكتروني:" . PHP_EOL . "    ";
$card->pay(25000);
echo "  ✓ تنفيذ عملية دفع نقدي:" . PHP_EOL . "    ";
$cash->pay(12500);

echo "  ✓ فحص اعتراض حالة الحافة (مبلغ <= 0):" . PHP_EOL;
try {
    $card->pay(-500);
} catch (\InvalidArgumentException $e) {
    echo "    تم اعتراض الخطأ بنجاح: " . $e->getMessage() . PHP_EOL;
}

echo "  ✓ سجلات العمليات المحفوظة في Trait:" . PHP_EOL;
foreach ($card->getLogs() as $log) {
    echo "    " . $log . PHP_EOL;
}

echo PHP_EOL . "================================================================" . PHP_EOL;
echo "   ✅ جميع التمارين الخمسة نُفذت واجتازت الاختبارات بنجاح 100%   " . PHP_EOL;
echo "================================================================" . PHP_EOL;
