@extends('layouts.app')

@section('title', 'تسجيل الحضور')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">تسجيل الحضور</h3>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" id="phone" class="form-control" placeholder="اكتب رقم الهاتف">
            </div>

            <table class="table table-bordered mt-3" id="workersTable">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الرقم القومي</th>
                        <th>رقم الهاتف</th>
                        <th>تسجيل حضور</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- البيانات ستمتلئ عبر AJAX -->
                </tbody>
            </table>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // البحث أثناء الكتابة
                var phoneInput = document.getElementById('phone');
                // console.log(phoneInput);
                var workersTableBody = document.querySelector('#workersTable tbody');

                phoneInput.addEventListener('keyup', function() {
                    var query = phoneInput.value.trim();
                    console.log(query);

                    if (query.length <= 10) {
                        workersTableBody.innerHTML = '';
                        return;
                    }

                    // AJAX باستخدام fetch
                    fetch("{{ route('attendance.search') }}?phone=" + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(workers => {
                            var html = '';

                            if (workers.length > 0) {
                                workers.forEach(function(worker) {
                                    html += `
                        <tr>
                            <td>${worker.name}</td>
                            <td>${worker.national_id}</td>
                            <td>${worker.phone}</td>
                            <td>
                                <button class="btn btn-success btn-sm checkinBtn" data-id="${worker.id}">
                                    حضور
                                </button>
                            </td>
                        </tr>
                    `;
                                });
                            } else {
                                html = '<tr><td colspan="4">لا يوجد نتائج</td></tr>';
                            }

                            workersTableBody.innerHTML = html;
                        })
                        .catch(() => {
                            workersTableBody.innerHTML =
                                '<tr><td colspan="4">حدث خطأ أثناء البحث</td></tr>';
                        });
                });

                // تسجيل الحضور عند الضغط على زر
                workersTableBody.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('checkinBtn')) {
                        var btn = e.target;
                        var worker_id = btn.getAttribute('data-id');

                        fetch("{{ route('attendance.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    worker_id: worker_id
                                })
                            })
                            .then(response => response.json())
                            .then(res => {
                                console.log(res);
                                if (res.status) {
                                    btn.disabled = true;
                                    btn.textContent = 'تم الحضور';
                                    alert(res.message);
                                }
                            })
                            .catch(() => {
                                alert('حدث خطأ أثناء تسجيل الحضور');
                            });
                    }
                });

            });
        </script>
    </div>
@endsection
