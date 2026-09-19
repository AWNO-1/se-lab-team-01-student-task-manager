<?php

use App\Contracts\ReportExporterInterface;
use App\Exporters\JsonReportExporter;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('يجب على الزائر تسجيل الدخول قبل تصدير تقرير المهام', function () {
    $this->get(route('tasks.export'))->assertRedirect(route('login'));
});

test('يمكن للمستخدم المسجل تصدير تقرير المهام بصيغة CSV عبر حقن التبعية الافتراضي', function () {
    $user = User::factory()->create();
    Task::factory()->count(3)->create();

    $response = $this->actingAs($user)->get(route('tasks.export'));

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
        ->assertHeader('Content-Disposition');

    expect($response->streamedContent ?? $response->content())
        ->toContain('المعرف')
        ->toContain('عنوان المهمة')
        ->toContain('الحالة');
});

test('تطبيق تعدد الأشكال وحقن التبعية: يمكن تبديل المصدر إلى JSON دون تعديل المتحكم', function () {
    $user = User::factory()->create();
    Task::factory()->create([
        'title' => 'مهمة مخصصة لاختبار JSON',
        'description' => 'شرح تجريبي للتحقق من DI',
    ]);

    // إعادة ربط الـ Interface بـ JsonReportExporter
    app()->bind(ReportExporterInterface::class, JsonReportExporter::class);

    $response = $this->actingAs($user)->get(route('tasks.export'));

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'application/json; charset=UTF-8');

    expect($response->content())
        ->toContain('مهمة مخصصة لاختبار JSON')
        ->toContain('شرح تجريبي للتحقق من DI');
});

test('يعيد النظام استجابة 404 عند محاولة تصدير مهام وقاعدة البيانات فارغة', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('tasks.export'));

    $response
        ->assertStatus(404)
        ->assertSee('لا توجد مهام لتصديرها حالياً.');
});
