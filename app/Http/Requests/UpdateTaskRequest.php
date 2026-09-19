<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * السماح للمستخدم المسجل بتعديل المهمة.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق من بيانات المهمة عند التعديل.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'is_completed' => 'sometimes|boolean',
        ];
    }

    /**
     * أسماء الحقول المستخدمة في رسائل التحقق العربية.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'عنوان المهمة',
            'description' => 'وصف المهمة',
            'is_completed' => 'حالة المهمة',
        ];
    }

    /**
     * رسائل تحقق عربية واضحة للمستخدم.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'يرجى كتابة عنوان المهمة.',
            'title.min' => 'يجب ألا يقل عنوان المهمة عن 3 أحرف.',
            'title.max' => 'يجب ألا يزيد عنوان المهمة على 255 حرفًا.',
            'description.max' => 'يجب ألا يزيد وصف المهمة على 1000 حرف.',
            'is_completed.boolean' => 'حالة المهمة المرسلة غير صحيحة.',
        ];
    }
}
