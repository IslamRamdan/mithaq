@extends('layouts.app', ['hideHeaderFooter' => true])

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="pt-4">
                    <h2 class="mb-4">عدد العملاء المسجلين اليوم وكل عميل سجلهم</h2>
                    <table class="table table-bordered table-striped bg-white">
                        <thead class="table-dark">
                            <tr>
                                <th>الاسم</th>
                                <th>عدد العمالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($counts as $item)
                                <tr>
                                    <td>{{ $item->user }}</td>
                                    <td>{{ $item->total_customers }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
@endsection
