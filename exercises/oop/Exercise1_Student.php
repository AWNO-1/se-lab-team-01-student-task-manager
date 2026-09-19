<?php

namespace Exercises\Oop;

/**
 * تمرين 1: Class الطالب
 * المطلوب: إنشاء Class باسم Student تحتوي على:
 * - اسم الطالب (name)
 * - المستوى الدراسي (level)
 * - الرقم الجامعي (studentNumber)
 * - دالة تعيد وصفاً مختصراً للطالب (introduce)
 */
class Student
{
    public function __construct(
        private string $name,
        private int $level,
        private string $studentNumber
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getStudentNumber(): string
    {
        return $this->studentNumber;
    }

    public function introduce(): string
    {
        return "أنا الطالب {$this->name}، مستواي {$this->level}، ورقمي الجامعي {$this->studentNumber}.";
    }
}

// تنفيذ تجريبي عند استدعاء الملف مباشرة
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $student = new Student('أواب النزيلي', 4, 'IT-2026-0015');
    echo $student->introduce() . PHP_EOL;
}
