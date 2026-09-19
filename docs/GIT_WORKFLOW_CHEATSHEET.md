# دليل دورة عمل Git المعتمدة — Git Workflow Cheatsheet

> القواعد والمعايير الإلزامية للفريق لتنظيم سجل Git وتجنب تعارضات الدمج وضمان جودة الكود.

---

## 1. التسمية القياسية للفروع (Branch Naming Convention)

تعتمد التسمية الصيغة الصريحة المحددة في المقرر:
```text
type/issue-number-short-description
```

### أنواع الفروع المعتمدة:
- **`feature/`**: لإضافة ميزة جديدة (مثل: `feature/issue-2-create-task-form`).
- **`fix/`**: لإصلاح خطأ برمجي (مثل: `fix/issue-7-fix-checkbox-value`).
- **`docs/`**: لكتابة وتعديل التوثيق ووثائق المتطلبات (مثل: `docs/issue-1-srs-specifications`).
- **`test/`**: لإضافة أو تعديل اختبارات آلية (مثل: `test/issue-5-task-crud-tests`).

---

## 2. معايير رسائل الالتزام (Commit Message Standards)

يجب أن تكون رسالة الـ Commit واضحة ومحددة، وتصف ما تم إنجازه بدقة.

### أمثلة ممتازة:
```bash
git commit -m "docs: add mini-srs document with functional requirements"
git commit -m "feat: implement store method in TaskController with FormRequest"
git commit -m "fix: resolve empty task title validation message in Arabic"
```

### رسائل ممنوعة نهائياً:
- `update`
- `changes`
- `final`
- `test`
- `fix bug`

---

## 3. الخطوات التنفيذية لكل مهمة (Step-by-Step Flow)

1. **تحديث الفرع الرئيسي والانطلاق منه:**
   ```bash
   git checkout main
   git pull origin main
   ```
2. **إنشاء فرع المهمة الجديد:**
   ```bash
   git checkout -b docs/issue-1-srs-specifications
   ```
3. **فحص التغييرات وإضافتها:**
   ```bash
   git status
   git diff
   git add docs/SRS.md
   ```
4. **حفظ الالتزام (Commit):**
   ```bash
   git commit -m "docs: complete mini-srs specifications and edge cases"
   ```
5. **رفع الفرع إلى GitHub:**
   ```bash
   git push -u origin docs/issue-1-srs-specifications
   ```
6. **فتح Pull Request:**
   - ربط الـ PR بالـ Issue: `Closes #1`.
   - كتابة ملخص التغييرات وقائمة التحقق.
   - طلب مراجعة من زميل بالفريق (Reviewer).
7. **الدمج بعد الموافقة:**
   - دمج التغييرات إلى `main` وحذف الفرع الفرعي لتنظيف المستودع.
