<?php

namespace Exercises\Oop;

/**
 * تمرين 5: نظام دفع متكامل يجمع جميع مفاهيم OOP
 * المطلوب:
 * 1. Interface باسم PaymentGatewayInterface مع دالة pay(float $amount): void
 * 2. Abstract Class باسم AbstractPaymentGateway تتحقق من أن المبلغ أكبر من صفر، وتوفر قالب السلوك Template Method
 * 3. Trait باسم LogsPayments لتسجيل العمليات
 * 4. فئات حقيقية: CardPaymentGateway و CashPaymentGateway
 */

interface PaymentGatewayInterface
{
    public function pay(float $amount): void;
    public function getMethodName(): string;
}

trait LogsPayments
{
    /**
     * @var array<int, string>
     */
    protected array $paymentLogs = [];

    public function logPayment(string $gateway, float $amount, bool $success, string $message = ''): void
    {
        $status = $success ? 'ناجحة' : 'فاشلة';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] عملية دفع {$status} عبر ({$gateway}) بمبلغ: " . number_format($amount, 2) . " ر.ي | {$message}";
        $this->paymentLogs[] = $logEntry;
    }

    public function getLogs(): array
    {
        return $this->paymentLogs;
    }
}

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    use LogsPayments;

    /**
     * Template Method: التحقق من المبلغ المشترك ثم استدعاء التنفيذ الخاص
     */
    public function pay(float $amount): void
    {
        // 1. التحقق الهندسي من صحة القيمة المدخلة (حالة حافة)
        if ($amount <= 0) {
            $this->logPayment($this->getMethodName(), $amount, false, 'المبلغ يجب أن يكون أكبر من الصفر.');
            throw new \InvalidArgumentException("فشلت العملية: قيمة المبلغ المراد دفعه (" . $amount . ") غير صالحة. يجب أن تكون أكبر من الصفر.");
        }

        // 2. استدعاء المعالجة المخصصة في الفئة الابن
        $this->processPayment($amount);

        // 3. تسجيل نجاح العملية عبر Trait
        $this->logPayment($this->getMethodName(), $amount, true, 'تم اعتماد الدفع بنجاح.');
    }

    /**
     * دالة مجردة تنفذها كل بوابة دفع بحسب آليتها الخاصة
     */
    abstract protected function processPayment(float $amount): void;
}

class CardPaymentGateway extends AbstractPaymentGateway
{
    public function __construct(
        private string $maskedCardNumber
    ) {
    }

    public function getMethodName(): string
    {
        return 'البطاقة الائتمانية / فيزا';
    }

    protected function processPayment(float $amount): void
    {
        // محاكاة الاتصال ببوابة البطاقات
        echo "💳 تم خصم مبلغ " . number_format($amount, 2) . " ر.ي من البطاقة المنتهية بـ ({$this->maskedCardNumber})." . PHP_EOL;
    }
}

class CashPaymentGateway extends AbstractPaymentGateway
{
    public function __construct(
        private string $cashierName
    ) {
    }

    public function getMethodName(): string
    {
        return 'الدفع النقدي (كاش)';
    }

    protected function processPayment(float $amount): void
    {
        // محاكاة إصدار سند استلام نقدي
        echo "💵 تم استلام مبلغ " . number_format($amount, 2) . " ر.ي نقداً بواسطة أمين الصندوق ({$this->cashierName})." . PHP_EOL;
    }
}

// تنفيذ تجريبي عند استدعاء الملف مباشرة
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $card = new CardPaymentGateway('****-4589');
    $cash = new CashPaymentGateway('محمد السعدي');

    echo "=== اختبار الدفع بالبطاقة ===" . PHP_EOL;
    $card->pay(15000);

    echo PHP_EOL . "=== اختبار الدفع النقدي ===" . PHP_EOL;
    $cash->pay(7500);

    echo PHP_EOL . "=== سجلات العمليات (Trait Logs) ===" . PHP_EOL;
    foreach ($card->getLogs() as $log) {
        echo $log . PHP_EOL;
    }
    foreach ($cash->getLogs() as $log) {
        echo $log . PHP_EOL;
    }

    echo PHP_EOL . "=== اختبار حالة الحافة (مبلغ صفري أو سالب) ===" . PHP_EOL;
    try {
        $card->pay(0);
    } catch (\InvalidArgumentException $e) {
        echo "تم التقاط الاستثناء بنجاح: " . $e->getMessage() . PHP_EOL;
    }
}
