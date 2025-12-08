@extends('layouts.app')

@section('title', 'تفاصيل الحضور')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">تفاصيل الحضور ليوم: {{ $date }}</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>رقم الهاتف</th>
                        <th>العميل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->worker->name }}</td>
                            <td>{{ $attendance->worker->phone }}</td>
                            <td>{{ $attendance->worker->user ?? '_' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">لا يوجد حضور في هذا اليوم</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <a href="{{ route('attendance.days') }}" class="btn btn-secondary mt-3">العودة للأيام</a>
        </div>
    </div>
@endsection
