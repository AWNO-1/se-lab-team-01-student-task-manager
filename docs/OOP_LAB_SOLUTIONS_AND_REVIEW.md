# 📘 الدليل الشامل لحلول تمارين ومراجعة المعمل الثاني (OOP & Dependency Injection)
### المقرر: هندسة البرمجيات — الجانب العملي (المستوى الرابع)
**إشراف: م. ساهر القائد (الهمداني)**

---

## 📑 فهرس الدليل
1. [مقدمة المفاهيم المعمارية في Laravel و PHP](#1-مقدمة-المفاهيم-المعمارية)
2. [حلول التمارين العملية الخمسة (Code Solutions)](#2-حلول-التمارين-العملية-الخمسة)
3. [تطبيق حقن التبعيات (Dependency Injection) في Laravel](#3-تطبيق-حقن-التبعيات-في-laravel)
4. [الإجابات النموذجية لأسئلة المراجعة العشرين (20 Review Questions)](#4-الإجابات-النموذجية-لأسئلة-المراجعة-العشرين)
5. [حل الاختبار القصير (Quiz Solutions)](#5-حل-الاختبار-القصير)
6. [جدول القرار والمقارنة الهندسية الشاملة](#6-جدول-القرار-والمقارنة-الهندسية-الشاملة)

---

## 1. مقدمة المفاهيم المعمارية

البرمجة كائنية التوجه (OOP) ليست مجرد صياغة برمجية، بل هي **فلسفة تنظيمية وهندسية** تهدف إلى:
- **قابلية الصيانة (Maintainability):** تعديل جزء دون كسر بقية أجزاء النظام.
- **إعادة الاستخدام (Reusability):** تجنب تكرار المنطق البرمجي (DRY).
- **تقليل الارتباط (Decoupling):** الاعتماد على عقود ومجردات (Abstractions) بدلاً من الارتباط المباشر بالتنفيذات اللحظية.

---

## 2. حلول التمارين العملية الخمسة

جميع التمارين تم تطبيقها واختبارها فعلياً داخل مجلد [`exercises/oop/`](file:///exercises/oop) ويمكن تشغيلها دفعة واحدة عبر:
```bash
php exercises/oop/RunAllExercises.php
```

### 📌 تمرين 1: Class الطالب ومحددات الوصول (`Student`)
- **المفهوم:** استخدام Constructor Property Promotion وتغليف البيانات.
- **الملف:** [`exercises/oop/Exercise1_Student.php`](file:///exercises/oop/Exercise1_Student.php)
```php
namespace Exercises\Oop;

class Student
{
    public function __construct(
        private string $name,
        private int $level,
        private string $studentNumber
    ) {}

    public function introduce(): string
    {
        return "أنا الطالب {$this->name}، مستواي {$this->level}، ورقمي الجامعي {$this->studentNumber}.";
    }
}
```

---

### 📌 تمرين 2: Abstract Class للأشكال الهندسية (`Shape`)
- **المفهوم:** فرض عقد مجرد للأبناء يشترك في السلوك ويختلف في التنفيذ الرياضي.
- **الملف:** [`exercises/oop/Exercise2_Shapes.php`](file:///exercises/oop/Exercise2_Shapes.php)
```php
namespace Exercises\Oop;

abstract class Shape
{
    abstract public function area(): float;
}

class Rectangle extends Shape
{
    public function __construct(private float $width, private float $height) {}
    public function area(): float { return $this->width * $this->height; }
}

class Circle extends Shape
{
    public function __construct(private float $radius) {}
    public function area(): float { return pi() * ($this->radius ** 2); }
}
```

---

### 📌 تمرين 3: Trait لتوليد الأرقام المرجعية (`GeneratesReferenceNumber`)
- **المفهوم:** إعادة استخدام وظيفة أفقية مشتركة بين فئات لا تشترك في علاقة وراثة رأسية.
- **الملف:** [`exercises/oop/Exercise3_ReferenceNumber.php`](file:///exercises/oop/Exercise3_ReferenceNumber.php)
```php
namespace Exercises\Oop;

trait GeneratesReferenceNumber
{
    public function generateReferenceNumber(string $prefix): string
    {
        return sprintf('%s-%s-%04d', strtoupper($prefix), date('Ymd'), random_int(1000, 9999));
    }
}

class MaintenanceRequest { use GeneratesReferenceNumber; }
class TrainingApplication { use GeneratesReferenceNumber; }
```

---

### 📌 تمرين 4: Interface والتصدير المتعدد (`ReportExporterInterface`)
- **المفهوم:** تصميم عقد برمجى يتيح تطبيق مبدأ تعدد الأشكال (Polymorphism).
- **الملف:** [`exercises/oop/Exercise4_Exporter.php`](file:///exercises/oop/Exercise4_Exporter.php)
- الفئات المنفذة: `JsonExporter` و `CsvExporter`.
- فئة الخدمة `ReportService` تتعامل فقط مع `ReportExporterInterface` دون معرفة التنفيذ الفعلي.

---

### 📌 تمرين 5: نظام الدفع المتكامل (Interface + Abstract Class + Trait)
- **المفهوم:** دمج كافة المفاهيم في نظام دفع متماسك يطبق نمط Template Method:
  1. **العقد (`PaymentGatewayInterface`):** دالة `pay(float $amount)`.
  2. **السمة (`LogsPayments`):** تسجيل العمليات المالية بتاريخها وتفاصيلها.
  3. **الفئة المجردة (`AbstractPaymentGateway`):** التحقق من صحة المبلغ (> 0)، استدعاء المعالجة المخصصة، وتسجيل النتيجة.
  4. **الفئات التنفيذية (`CardPaymentGateway` و `CashPaymentGateway`):** معالجة الدفع الخاصة بكل وسيلة.
- **الملف:** [`exercises/oop/Exercise5_PaymentSystem.php`](file:///exercises/oop/Exercise5_PaymentSystem.php)

---

## 3. تطبيق حقن التبعيات في Laravel

تم دمج ميزة تصدير التقارير في مشروع Laravel الفعلي لإثبات الفهم العملي لـ **Service Container** و **Dependency Injection**:

1. **العقد:** [`app/Contracts/ReportExporterInterface.php`](file:///app/Contracts/ReportExporterInterface.php)
2. **السمة:** [`app/Traits/LogsReportExports.php`](file:///app/Traits/LogsReportExports.php)
3. **الفئة المجردة:** [`app/Exporters/AbstractReportExporter.php`](file:///app/Exporters/AbstractReportExporter.php)
4. **التنفيذات:** [`app/Exporters/CsvReportExporter.php`](file:///app/Exporters/CsvReportExporter.php) و [`app/Exporters/JsonReportExporter.php`](file:///app/Exporters/JsonReportExporter.php)
5. **المتحكم:** [`app/Http/Controllers/TaskExportController.php`](file:///app/Http/Controllers/TaskExportController.php) يستقبل `ReportExporterInterface` عبر الـ Constructor.
6. **الربط (Binding):** داخل [`app/Providers/AppServiceProvider.php`](file:///app/Providers/AppServiceProvider.php):
   ```php
   $this->app->bind(ReportExporterInterface::class, CsvReportExporter::class);
   ```
7. **الاختبار الآلي:** [`tests/Feature/TaskExportTest.php`](file:///tests/Feature/TaskExportTest.php) يختبر تبديل التنفيذ وتصدير التقارير بنجاح 100%.

---

## 4. الإجابات النموذجية لأسئلة المراجعة العشرين (20 Review Questions)

### س1: ما الفرق بين `Class` و `Object`؟
- **Class (الفئة):** هي المخطط الهندسي (Blueprint) أو القالب النظري الذي يحدد الخصائص والوظائف المشتركة.
- **Object (الكائن):** هو النسخة الحقيقية الملموسة المنشأة في الذاكرة باستخدام الكلمة المحجوزة `new`.

### س2: ما وظيفة المتغير الخاص `$this`؟
- يشير `$this` إلى **الكائن الحالي** (Current Instance) الذي يستدعي الدالة داخل الفئة للوصول إلى خصائصه وتوابعه الداخلية.

### س3: ما وظيفة الدالة البانية (Constructor)؟
- هي دالة سحرية (`__construct`) تُنفّذ تلقائياً عند إنشاء الكائن بواسطة `new`، ومسؤوليتها الأساسية تهيئة الخصائص المبدئية وحقن الاعتماديات.

### س4: ما الفرق بين محددات الوصول `public` و `protected` و `private`؟
- `public`: يمكن الوصول إليها من أي مكان (داخل الفئة، من الفئات الوارثة، وخارجها).
- `protected`: لا يمكن الوصول إليها إلا من داخل نفس الفئة أو من الفئات التي ترث منها (`extends`).
- `private`: لا يمكن الوصول إليها إطلاقاً إلا من داخل نفس الفئة التي عُرّفت فيها فقط.

### س5: ما المقصود بالتغليف (Encapsulation)؟
- هو إخفاء التفاصيل الداخلية وحالة البيانات للكائن ومنع التعديل المباشر عليها، والتحكم في الوصول إليها وتعديلها عبر دوال محددة (Getters / Setters / Business Methods) لضمان اتساق البيانات.

### س6: ما معنى الوراثة (Inheritance)؟
- هي آلية برمجية تسمح لفئة ابنة (Child Class) بأن ترث الخصائص والدوال المسموحة من فئة أب (Parent Class) عبر الكلمة `extends` لتحقيق إعادة استخدام الكود وتطبيق مبادئ التخصص.

### س7: هل يمكن لـ Class في PHP أن ترث مباشرة من أكثر من Class؟
- **لا**، لغة PHP تدعم الوراثة الأحادية فقط (Single Inheritance). والبديل المعماري لتعويض الوراثة المتعددة هو استخدام **Traits** و **Interfaces**.

### س8: ما المقصود بإعادة تعريف الدوال (Method Overriding)؟
- هو قيام الفئة الابن بإعادة كتابة دالة موروثة من الفئة الأب لتقديم سلوك مخصص يناسب طبيعة الفئة الابن، مع إمكانية استدعاء تنفيذ الأب عبر `parent::methodName()`.

### س9: ما المقصود بالواجهة (Interface)؟
- هي عقد برمجي (Contract) صارم يحدد أسماء الدوال ومعاملاتها ونوع إرجاعها دون كتابة أي سطر تنفيذي، وتُلزم أي فئة تطبقها (`implements`) بتقديم الكود التنفيذي الكامل لجميع دوالها.

### س10: هل يمكن إنشاء كائن (Object) مباشرة من Interface؟
- **لا قطعيّاً**؛ لأن الـ Interface خالية تماماً من أي تنفيذ، ومحاولة كتابة `new MyInterface()` تؤدي إلى Fatal Error فوري في PHP.

### س11: ما المقصود بالفئة المجردة (Abstract Class)؟
- هي فئة غير مكتملة تُستخدم كقاعدة أساسية للفئات الأبناء، تجمع بين دوال مكتملة التنفيذ ودوال مجردة (`abstract methods`) يجب على الأبناء تنفيذها.

### س12: هل يمكن إنشاء كائن (Object) مباشرة من Abstract Class؟
- **لا يمكن**؛ ومحاولة كتابة `new MyAbstractClass()` تُطلق خطأ فورياً لأنها مصممة فقط لكي تُورث (`extends`).

### س13: هل يمكن أن تحتوي Abstract Class على Methods مكتملة؟
- **نعم**، هذه إحدى أهم الفروق بينها وبين الـ Interface؛ حيث يمكن للفئة المجردة احتواء خصائص ودوال كاملة التنفيذ يشترك فيها جميع الأبناء.

### س14: ما المقصود بالدالة المجردة (Abstract Method)؟
- هي دالة تُعرّف بدون جسم برمجي (بدون `{ }`)، تبدأ بالكلمة `abstract`، وتُلزم أول فئة حقيقية ترثها بكتابة التنفيذ الفعلي لها.

### س15: ما المقصود بالسمة (Trait)؟
- هي آلية أفقية لإعادة استخدام الأكواد عبر فئات متعددة لا ترتبط بعلاقة وراثة واحدة، وتُحقن داخل الفئة عبر الكلمة `use`.

### س16: هل يمكن إنشاء كائن (Object) مباشرة من Trait؟
- **لا يمكن إطلاقاً**؛ فالـ Trait ليست فئة مستقلة بذاتها، بل هي "قصاصة كود" تُدمج داخل فئات أخرى أثناء التحميل.

### س17: ما الفرق الدقيق بين الكلمات `extends` و `implements` و `use`؟
- `extends`: لوراثة فئة واحدة (Class أو Abstract Class) أو توسيع Interface من Interface أخرى.
- `implements`: لتطبيق عقد واجهة برمجية واحدة أو أكثر (Interfaces) داخل الفئة.
- `use`: لاستيراد Namespace في بداية الملف، أو لإدراج Trait واحدة أو أكثر داخل الفئة.

### س18: متى نستخدم Trait بدلاً من الوراثة (Inheritance)؟
- نستخدم Trait عندما تكون الميزة المطلوبة **أفقية وعامة** تشترك فيها فئات متباعدة وغير متجانسة في شجرة الوراثة (مثال: توليد أرقام تسلسلية، تسجيل نشاط، رفع ملفات)، حتى نتجنب تعقيد الوراثة الرأسية.

### س19: ما معنى حقن التبعيات (Dependency Injection)؟
- هو نمط تصميم (Design Pattern) يقوم على تزويد الكائن بالخدمات أو الاعتماديات التي يحتاجها من الخارج (غالباً عبر الـ Constructor) بدلاً من أن يقوم الكائن بإنشائها بنفسه داخلياً بـ `new`، مما يقلل الارتباط ويسهل الاختبار (Unit Testing).

### س20: ما فائدة الربط (Binding) داخل Service Container في Laravel؟
- الفائدة هي إخبار Laravel: *"عندما يطلب أي متحكم أو خدمة العقد `ReportExporterInterface`، أعطه تلقائياً نسخة من الفئة الحقيقية `CsvReportExporter`"*. هذا يسمح بتغيير المزود على مستوى النظام كاملاً من سطر واحد دون تعديل Controllers أو Services.

---

## 5. حل الاختبار القصير (Quiz Solutions)

- **السؤال 1 (إنشاء كائن من Abstract Class):**  
  خطأ fatal error؛ لا يمكن إنشاء كائن من فئة مجردة.
- **السؤال 2 (حماية البيانات والتغليف):**  
  أفضل طريقة هي جعل الحقول `private` أو `protected` والتحكم بها عبر توابع الفئة.
- **السؤال 3 (تعدد الواجهات):**  
  الفئة تستطيع تطبيق أكثر من Interface مفصولة بفواصل: `implements A, B, C`.
- **السؤال 4 (تعارض التوابع في Traits):**  
  يُحل التعارض باستخدام `insteadof` لاختيار التابع المعتمد، و `as` لإعطاء اسم بديل للتابع الآخر.
- **السؤال 5 (الربط في Laravel):**  
  يتم عادةً داخل دالة `register()` في `AppServiceProvider` باستخدام `$this->app->bind()` أو `singleton()`.
- **السؤال 6 (التوجيه use داخل الفئة وخارجها):**  
  خارج الفئة: استيراد فئة من مساحة أسماء (Namespace). داخل الفئة: إدخال Trait.
- **السؤال 7 (منع التوريث النهائي):**  
  استخدام الكلمة المفتاحية `final` قبل تعريف الفئة أو الدالة.

---

## 6. جدول القرار والمقارنة الهندسية الشاملة

| المعيار | Class عادية | Abstract Class | Interface | Trait |
| :--- | :--- | :--- | :--- | :--- |
| **الإنشاء المباشر بـ `new`** | متاح ✅ | ممنوع ❌ | ممنوع ❌ | ممنوع ❌ |
| **دوال كاملة التنفيذ** | نعم ✅ | نعم ✅ | لا (عقد مجرد فقط) ❌ | نعم ✅ |
| **دوال مجردة (Abstract)** | لا ❌ | نعم ✅ | جميعها مجردة ✅ | نعم (اختيارياً) ✅ |
| **الخصائص والحقول (State)** | نعم ✅ | نعم ✅ | ثوابت فقط `const` | نعم ✅ |
| **طريقة الاستخدام** | `new Class()` | `extends` | `implements` | `use` |
| **تعدد الاستخدام في الفئة** | وراثة أحادية (1) | وراثة أحادية (1) | متعدد (غير محدود) ✅ | متعدد (غير محدود) ✅ |
| **الهدف الأساسي** | كائن مستقل | قالب هرمي مشترك | عقد وسلوك إلزامي | مشاركة وظائف أفقية |
