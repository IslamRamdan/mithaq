@extends('layouts.app')

@section('title', '📝 طلبات تسجيل الشركات')

@section('content')
    <style>
        body {
            background-color: #f4f6f9;
        }

        /* ===================== السايدبار ===================== */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #174A7C 0%, #1a5a94 80%, #B89C5A 100%);
            color: white;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        /* ===================== الكارد ===================== */
        .registration-card {
            margin: 50px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(23, 74, 124, 0.15);
            overflow: hidden;
            transition: 0.3s;
        }

        .registration-card:hover {
            transform: translateY(-3px);
        }

        /* ===================== الهيدر ===================== */
        .card-header-custom {
            background: linear-gradient(135deg, #174A7C 0%, #B89C5A 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card-header-custom h4 {
            margin: 0;
            font-size: 1.4rem;
        }

        /* ===================== البودي ===================== */
        .card-body-custom {
            padding: 2rem;
            background: #fdfdfd;
        }

        /* ===================== عناصر النموذج ===================== */
        .form-label {
            font-weight: 600;
            color: #174A7C;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select,
        textarea {
            border-radius: 12px;
            border: 1px solid #d6d9de;
            padding: 10px 14px;
            transition: all 0.3s ease;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: #174A7C;
            box-shadow: 0 0 0 3px rgba(23, 74, 124, 0.15);
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: #dc3545;
        }

        .form-text {
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* ===================== الأزرار ===================== */
        .submit-btn {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #174A7C 0%, #B89C5A 100%);
            border: none;
            color: white;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: 0.3s;
        }

        .submit-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* ===================== البادجز والتنبيهات ===================== */
        .required-field {
            color: #dc3545;
        }

        .optional-badge {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .alert {
            border-radius: 12px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: none;
            color: #065f46;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: none;
            color: #991b1b;
            font-weight: 500;
        }

        /* ===================== تأثير تحميل الوظائف ===================== */
        .loading-spinner {
            width: 1rem;
            height: 1rem;
            border: 2px solid #174A7C;
            border-top: 2px solid transparent;
            border-radius: 50%;
            display: inline-block;
            animation: spin 1s linear infinite;
            margin-left: 5px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 991px) {
            .registration-card {
                margin: 30px 15px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            {{-- ✅ السايدبار --}}
            @include('admin.partials.sidebar')

            {{-- ✅ المحتوى الرئيسي --}}
            <main class="col-lg-8 col-md-10 mx-auto">
                <div class="card registration-card">
                    <div class="card-header-custom text-center">
                        <h4 style="color: white !important; font-weight: bold;">📝التعديل علي : ({{ $worker->name }})</h4>
                    </div>

                    <div class="card-body-custom">
                        {{-- ✅ رسائل النجاح --}}
                        @if (session('success'))
                            <div class="alert alert-success text-center">
                                ✅ {{ session('success') }}
                            </div>
                        @endif

                        {{-- ✅ نموذج التسجيل --}}
                        <form method="POST" action="{{ route('admin.edit-sign', $worker->id) }}"
                            enctype="multipart/form-data" id="registrationForm">
                            @csrf

                            {{-- الاسم الكامل --}}
                            <div class="mb-4">
                                <label class="form-label">👤 الاسم الكامل <span
                                        class="optional-badge">(اختياري)</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ $worker->name }}"
                                    placeholder="أدخل اسمك بالعربية فقط" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- الرقم القومي --}}
                            <div class="mb-4">
                                <label class="form-label">🆔 الرقم القومي <span
                                        class="optional-badge">(اختياري)</span></label>
                                <input type="text" name="national_id" id="national_id" maxlength="14"
                                    class="form-control @error('national_id') is-invalid @enderror"
                                    value="{{ $worker->national_id }}" placeholder="مثال: 29501011234567">
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text">يجب أن يتكون من 14 رقمًا</small>
                            </div>

                            {{-- الوظيفة --}}
                            <div class="mb-4">
                                <label class="form-label">💼 الوظيفة المطلوبة <span
                                        class="optional-badge">(اختياري)</span></label>
                                <select id="job_title" name="job_title"
                                    class="form-select @error('job_title') is-invalid @enderror" required>
                                    <option value="">جاري تحميل الوظائف...</option>
                                </select>
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text" id="jobsLoadingText">
                                    <span class="loading-spinner"></span> جاري تحميل الوظائف المتاحة...
                                </small>
                            </div>

                            {{-- الموظف --}}
                            <div class="mb-4">
                                <label class="form-label">الموظف</label>
                                <select name="user" class="form-select" required>
                                    <option value="">اختر العميل</option>
                                    <option value="خالد علاء"
                                        {{ old('user', $worker->user ?? '') == 'خالد علاء' ? 'selected' : '' }}>خالد علاء
                                    </option>
                                    <option value="احمد بشير"
                                        {{ old('user', $worker->user ?? '') == 'احمد بشير' ? 'selected' : '' }}>احمد بشير
                                    </option>
                                    <option value="احمد محمود"
                                        {{ old('user', $worker->user ?? '') == 'احمد محمود' ? 'selected' : '' }}>احمد محمود
                                    </option>
                                    <option value="اسلام رمضان"
                                        {{ old('user', $worker->user ?? '') == 'اسلام رمضان' ? 'selected' : '' }}>اسلام
                                        رمضان</option>
                                    <option value="خديجة"
                                        {{ old('user', $worker->user ?? '') == 'خديجة' ? 'selected' : '' }}>خديجة</option>
                                    <option value="رؤى"
                                        {{ old('user', $worker->user ?? '') == 'رؤى' ? 'selected' : '' }}>رؤى</option>
                                    <option value="نادين"
                                        {{ old('user', $worker->user ?? '') == 'نادين' ? 'selected' : '' }}>نادين</option>
                                    <option value="احمد طاهر"
                                        {{ old('user', $worker->user ?? '') == 'احمد طاهر' ? 'selected' : '' }}>احمد طاهر
                                    </option>
                                    <option value="مريم"
                                        {{ old('user', $worker->user ?? '') == 'مريم' ? 'selected' : '' }}>مريم</option>
                                </select>
                            </div>

                            {{-- رقم الهاتف --}}
                            <div class="mb-4">
                                <label class="form-label">📱 رقم الهاتف <span class="required-field">*</span></label>
                                <input type="text" name="phone" id="phone" maxlength="11" required
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ $worker->phone }}"
                                    placeholder="مثال: 01012345678">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- الصورة الشخصية --}}
                            <div class="mb-4">
                                <label class="form-label">📸 الصورة الشخصية <span
                                        class="optional-badge">(اختياري)</span></label>
                                <input type="file" name="personal_photo" id="personal_photo"
                                    class="form-control @error('personal_photo') is-invalid @enderror"
                                    accept="image/jpeg,image/png">
                                @error('personal_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- صورة البطاقة --}}
                            <div class="mb-4">
                                <label class="form-label">🪪 صورة البطاقة <span
                                        class="optional-badge">(اختياري)</span></label>
                                <input type="file" name="id_card_photo" id="id_card_photo"
                                    class="form-control @error('id_card_photo') is-invalid @enderror"
                                    accept="image/jpeg,image/png">
                                @error('id_card_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ملاحظات --}}
                            <div class="mb-4">
                                <label class="form-label">💬 ملاحظات أو رسالة إضافية <span
                                        class="optional-badge">(اختياري)</span></label>
                                <textarea name="message" id="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                                    placeholder="اكتب ملاحظاتك أو معلومات إضافية...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- زر التسجيل --}}
                            <button type="submit" class="submit-btn">✨ حفظ </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
        <script>
            // جلب الوظائف من API
            async function loadJobs() {
                const jobSelect = document.getElementById('job_title');
                const loadingText = document.getElementById('jobsLoadingText');

                // ✅ القيمة القديمة أو الحالية من العامل
                const oldValue = "{{ old('job_title', $worker->job_title ?? '') }}";

                try {
                    const response = await fetch('https://mishcrm.com/api/jobs');
                    const jobs = await response.json();

                    // مسح الخيارات القديمة
                    jobSelect.innerHTML = '<option value="">اختر الوظيفة المطلوبة</option>';

                    // إضافة الوظائف المتاحة فقط (show_in_app = yes)
                    jobs.forEach(job => {
                        if (job.show_in_app === 'yes') {
                            const option = document.createElement('option');
                            option.value = job.title;
                            option.textContent = job.title;

                            // ✅ تحديد القيمة القديمة أو الحالية
                            if (oldValue && oldValue === job.title) {
                                option.selected = true;
                            }

                            jobSelect.appendChild(option);
                        }
                    });

                    // إخفاء نص التحميل
                    loadingText.style.display = 'none';

                    // إذا لم يكن هناك وظائف متاحة
                    if (jobSelect.options.length === 1) {
                        jobSelect.innerHTML = '<option value="">لا توجد وظائف متاحة حالياً</option>';
                        loadingText.innerHTML = '⚠️ لا توجد وظائف متاحة في الوقت الحالي';
                        loadingText.style.display = 'block';
                        loadingText.style.color = '#dc3545';
                    }

                } catch (error) {
                    console.error('Error loading jobs:', error);
                    jobSelect.innerHTML = '<option value="">خطأ في تحميل الوظائف</option>';
                    loadingText.innerHTML = '⚠️ حدث خطأ أثناء تحميل الوظائف، يرجى المحاولة لاحقاً';
                    loadingText.style.display = 'block';
                    loadingText.style.color = '#dc3545';
                }
            }

            // تحميل الوظائف عند تحميل الصفحة
            document.addEventListener('DOMContentLoaded', loadJobs);
        </script>
    </div>
@endsection
