<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Worker;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //
    // صفحة عرض نموذج الحضور
    public function index()
    {
        return view('attendance.index');
    }

    // البحث عن العملاء عبر رقم الهاتف
    public function searchWorker(Request $request)
    {
        $query = $request->input('phone');

        $workers = Worker::where('phone', 'like', "%$query%")->get();

        return response()->json($workers);
    }

    // تسجيل حضور العميل
    public function store(Request $request)
    {

        // return $request->all();
        // إذا كان الطلب JSON
        if ($request->isJson()) {
            $data = $request->json()->all();
        } else {
            $data = $request->all();
        }

        $request->merge($data);

        $request->validate([
            'worker_id' => 'required|exists:workers,id',
        ]);

        $today = date('Y-m-d');

        $attendance = Attendance::firstOrCreate(
            [
                'worker_id' => $request->worker_id,
                'date' => $today,
            ],
            [
                'check_in' => now(),
                'status' => 'present'
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'تم تسجيل حضور العامل بنجاح'
        ]);
    }
    // عرض الأيام التي بها حضور
    public function daysIndex()
    {
        // جلب جميع الأيام المميزة في جدول attendance
        $dates = Attendance::select('date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.days', compact('dates'));
    }

    // عرض الحضور في يوم محدد
    public function dayDetail($date)
    {
        $attendances = Attendance::where('date', $date)
            ->with('worker')
            ->get();

        return view('attendance.day_detail', compact('attendances', 'date'));
    }
}
