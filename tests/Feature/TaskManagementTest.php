<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('يجب على الزائر تسجيل الدخول قبل الوصول إلى صفحات المهام', function () {
    $task = Task::factory()->create();

    $this->get(route('tasks.index'))->assertRedirect(route('login'));
    $this->get(route('tasks.create'))->assertRedirect(route('login'));
    $this->get(route('tasks.show', $task))->assertRedirect(route('login'));
    $this->get(route('tasks.edit', $task))->assertRedirect(route('login'));
});

test('يمكن للمستخدم عرض صفحات المهام الأربع', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'title' => 'إعداد تقرير المشروع',
        'description' => 'مراجعة النتائج وإرفاق التوصيات النهائية.',
    ]);

    $this->actingAs($user)
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertSee('إدارة المهام')
        ->assertSee($task->title);

    $this->actingAs($user)
        ->get(route('tasks.create'))
        ->assertOk()
        ->assertSee('إنشاء مهمة جديدة');

    $this->actingAs($user)
        ->get(route('tasks.show', $task))
        ->assertOk()
        ->assertSee($task->title)
        ->assertSee($task->description);

    $this->actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertOk()
        ->assertSee('تعديل المهمة')
        ->assertSee($task->title);
});

test('يمكن للمستخدم إنشاء مهمة جديدة', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('tasks.store'), [
        'title' => 'تجهيز العرض التقديمي',
        'description' => 'إعداد الشرائح ومراجعة الأمثلة قبل العرض.',
        'is_completed' => '0',
    ]);

    $task = Task::query()->sole();

    $response
        ->assertRedirect(route('tasks.show', $task))
        ->assertSessionHas('success', 'تم إنشاء المهمة بنجاح.');

    expect($task)
        ->title->toBe('تجهيز العرض التقديمي')
        ->description->toBe('إعداد الشرائح ومراجعة الأمثلة قبل العرض.')
        ->is_completed->toBeFalse();
});

test('تظهر رسائل تحقق عربية عند إرسال بيانات غير صحيحة', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('tasks.create'))
        ->post(route('tasks.store'), [
            'title' => '',
            'description' => str_repeat('أ', 1001),
        ])
        ->assertRedirect(route('tasks.create'))
        ->assertSessionHasErrors([
            'title' => 'يرجى كتابة عنوان المهمة.',
            'description' => 'يجب ألا يزيد وصف المهمة على 1000 حرف.',
        ]);
});

test('يمكن للمستخدم تحديث المهمة وحالتها', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $response = $this->actingAs($user)->put(route('tasks.update', $task), [
        'title' => 'المهمة بعد التحديث',
        'description' => 'تم تحديث التفاصيل بنجاح.',
        'is_completed' => '1',
    ]);

    $response
        ->assertRedirect(route('tasks.show', $task))
        ->assertSessionHas('success', 'تم تحديث المهمة بنجاح.');

    expect($task->refresh())
        ->title->toBe('المهمة بعد التحديث')
        ->description->toBe('تم تحديث التفاصيل بنجاح.')
        ->is_completed->toBeTrue();
});

test('يمكن للمستخدم حذف المهمة', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $this->actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect(route('tasks.index'))
        ->assertSessionHas('success', 'تم حذف المهمة بنجاح.');

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});
