@extends('layouts.app')

@section('title', 'أيام الحضور')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">الأيام التي بها تسجيل حضور</h3>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @forelse ($dates as $d)
                    <li class="list-group-item">
                        <a href="{{ route('attendance.day.detail', $d->date) }}">
                            {{ $d->date }}
                        </a>
                    </li>
                @empty
                    <li class="list-group-item">لا توجد أي تسجيلات حضور</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
