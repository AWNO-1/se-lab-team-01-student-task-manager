<?php

namespace Exercises\Oop;

/**
 * تمرين 2: Abstract Class للأشكال الهندسية
 * المطلوب: إنشاء Abstract Class باسم Shape تحتوي على دالة مجردة area(): float
 * ثم إنشاء Rectangle و Circle يرثان منها ويطبقان حساب المساحة.
 */
abstract class Shape
{
    abstract public function area(): float;

    public function describe(): string
    {
        return sprintf("الشكل: %s، المساحة: %.2f", static::class, $this->area());
    }
}

class Rectangle extends Shape
{
    public function __construct(
        private float $width,
        private float $height
    ) {
        if ($this->width <= 0 || $this->height <= 0) {
            throw new \InvalidArgumentException("أبعاد المستطيل يجب أن تكون أكبر من الصفر.");
        }
    }

    public function area(): float
    {
        return $this->width * $this->height;
    }
}

class Circle extends Shape
{
    public function __construct(
        private float $radius
    ) {
        if ($this->radius <= 0) {
            throw new \InvalidArgumentException("نصف قطر الدائرة يجب أن يكون أكبر من الصفر.");
        }
    }

    public function area(): float
    {
        return pi() * ($this->radius ** 2);
    }
}

// تنفيذ تجريبي عند استدعاء الملف مباشرة
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $rect = new Rectangle(5.0, 4.0);
    $circle = new Circle(3.0);

    echo "مساحة المستطيل (5x4): " . $rect->area() . PHP_EOL;
    echo "مساحة الدائرة (r=3): " . round($circle->area(), 2) . PHP_EOL;
}
