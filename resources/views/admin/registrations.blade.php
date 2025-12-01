@extends('layouts.app')
@section('title', 'قائمة العمالة المسجلة')

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm" style="border-radius:18px; border:1px solid #e5e7eb;">
            <div class="card-header text-center d-flex justify-content-around align-content-center"
                style="background: linear-gradient(135deg, #174A7C 60%, #B89C5A 100%);
                   color: #fff; border-radius: 18px 18px 0 0;">
                <h4 class="mb-0 fw-bold" style="color: #fff !important;">قائمة العمالة المسجلة</h4>
                <a href="{{ route('admin.signLead') }}"
                    style="display:inline-block; padding:10px 20px; background: linear-gradient(135deg, #174A7C 0%, #1a5a94 50%, #B89C5A 100%);
          color:white !important; text-decoration:none; border-radius:25px; font-weight:700; transition: all 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(23,74,124,0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(23,74,124,0.3)';">
                    ✨ تسجيل
                </a>
            </div>

            <div class="card-body"
                style="background: linear-gradient(135deg, #fff 85%, #f8fafc 100%);
                   border-radius: 0 0 18px 18px;">
                <!-- Search Form -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form action="{{ route('admin.registrations.search') }}" method="GET" class="d-flex">
                            <input type="text" name="phone" class="form-control me-2" placeholder="أدخل رقم الهاتف"
                                required>
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </form>
                    </div>
                    @if (Route::currentRouteName() === 'admin.registrations.search')
                        <a class="btn btn-primary" href="{{ route('admin.registrations') }}">كل العملاء</a>
                    @endif
                </div>

                <!-- Cards -->
                @if (
                    $workers instanceof \Illuminate\Pagination\LengthAwarePaginator &&
                        Route::currentRouteName() !== 'admin.registrations.search')
                    <div class="row text-center mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="p-4 shadow-sm rounded-4 text-white fw-bold"
                                style="background: linear-gradient(135deg, #174A7C 60%, #B89C5A 100%);">
                                <h5 class="mb-1" style="color:#fff !important">العمالة المسجلة اليوم</h5>
                                <h3 class="mb-0" style="color:#fff !important">{{ $todayCount }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-4 shadow-sm rounded-4 text-white fw-bold"
                                style="background: linear-gradient(135deg, #174A7C 60%, #B89C5A 100%);">
                                <h5 class="mb-1" style="color:#fff !important">العمالة المسجلة اون لاين</h5>
                                <h3 class="mb-0" style="color:#fff !important">{{ $todayCountNull }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-4 shadow-sm rounded-4 text-white fw-bold"
                                style="background: linear-gradient(135deg, #174A7C 60%, #B89C5A 100%);">
                                <h5 class="mb-1" style="color:#fff !important">العمالة المسجلة يدوي</h5>
                                <h3 class="mb-0" style="color:#fff !important"><a style="color:#fff !important"
                                        href="{{ route('admin.registrations.userWorker') }}">{{ $todayCountUser }}</a></h3>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-4 shadow-sm rounded-4 text-white fw-bold"
                                style="background: linear-gradient(135deg, #B89C5A 60%, #174A7C 100%);">
                                <h5 class="mb-1" style="color:#fff !important">إجمالي العمالة المسجلة</h5>
                                <h3 class="mb-0" style="color:#fff !important">{{ $totalCount }}</h3>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <button id="exportExcel" class="btn btn-success mb-3">📤 تصدير Excel</button>

                    <table class="table table-bordered align-middle text-center table-hover bg-white mb-0">
                        <thead
                            style="background: linear-gradient(135deg, #174A7C 60%, #B89C5A 100%);
                                  color: #fff;">
                            <tr>
                                <th>الصورة الشخصية</th>
                                <th>الاسم</th>
                                <th>الرقم القومي</th>
                                <th>الوظيفة</th>
                                <th>رقم الهاتف</th>
                                <th>صورة البطاقة</th>
                                <th>العميل</th>
                                <th>ملاحظات</th>
                                <th>تاريخ التسجيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workers as $worker)
                                <tr>
                                    <td>
                                        @if ($worker->personal_photo)
                                            <a href="{{ asset('storage/' . $worker->personal_photo) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $worker->personal_photo) }}"
                                                    alt="صورة العامل" class="rounded-circle"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold" style="color:#174A7C;">{{ $worker->name }}</td>
                                    <td>{{ $worker->national_id }}</td>
                                    <td>{{ $worker->job_title }}</td>
                                    <td dir="ltr">
                                        <a href="https://wa.me/2{{ preg_replace('/\D/', '', $worker->phone) }}"
                                            target="_blank" class="text-decoration-none text-success">
                                            {{ $worker->phone }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($worker->id_card_photo)
                                            <a href="{{ asset('storage/' . $worker->id_card_photo) }}" target="_blank"
                                                class="btn btn-sm fw-bold text-white"
                                                style="background: linear-gradient(135deg, #174A7C 60%, #B89C5A 100%);
                                                  border-radius: 50px; padding: 4px 12px;">
                                                عرض
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $worker->user ?? '-' }}</td>
                                    <td class="text-muted d-flex">
                                        <input type="text" value="{{ $worker->message }}"
                                            class="form-control form-control-sm note-input" data-id="{{ $worker->id }}">
                                        <button type="button" class="btn btn-sm btn-primary save-note-btn"
                                            data-id="{{ $worker->id }}" style="border-radius: 0">
                                            حفظ
                                        </button>
                                        <a class="btn btn-sm btn-primary save-note-btn"
                                            style="border-radius: 0; margin-left:5px; background-color: #B89C5A !important;"
                                            href="{{ route('admin.update-sign', $worker->id) }}" target="_blank">
                                            تعديل
                                        </a>
                                    </td>
                                    <td>{{ $worker->updated_at ? $worker->updated_at->setTimezone('Africa/Cairo')->format('Y-m-d H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted fw-bold">
                                        لا توجد بيانات عمالة مسجلة حالياً 📄
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @php
                    $currentRoute = Route::currentRouteName();
                @endphp

                @if (
                    $workers instanceof \Illuminate\Pagination\LengthAwarePaginator &&
                        Route::currentRouteName() !== 'admin.registrations.search')
                    <div class="mt-4">
                        {{ $workers->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const buttons = document.querySelectorAll('.save-note-btn');

                buttons.forEach(button => {
                    button.addEventListener('click', async function() {
                        const id = this.dataset.id;
                        const input = document.querySelector(`.note-input[data-id="${id}"]`);
                        const note = input.value;

                        try {
                            const response = await fetch(`/workers/${id}/save-note`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    notes: note
                                })
                            });

                            const data = await response.json();
                            console.log(data);

                            if (data.success) {
                                alert('✅ تم حفظ الملاحظة بنجاح');
                            } else {
                                alert('⚠️ حدث خطأ أثناء الحفظ');
                            }

                        } catch (error) {
                            console.error(error);
                            alert('❌ فشل الاتصال بالخادم');
                        }
                    });
                });
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script>
            document.getElementById("exportExcel").addEventListener("click", function() {

                // الحصول على الجدول
                const table = document.querySelector("table");

                // تحويل الجدول إلى Sheet
                const workbook = XLSX.utils.table_to_book(table, {
                    sheet: "Workers"
                });

                // تنزيل الملف
                XLSX.writeFile(workbook, "workers.xlsx");
            });
        </script>

    </div>
@endsection
