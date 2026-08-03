<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\AttendanceVerification;
use App\Models\Coach;

class TeachingHistoryController extends Controller
{
    public function index()
    {
        $coach = Coach::where('user_id', auth()->id())->firstOrFail();

        $verifications = AttendanceVerification::where('coach_id', $coach->id)
            ->with(['schedule.classType'])
            ->orderByDesc('session_date')
            ->paginate(20);

        return view('coach.history.index', compact('coach', 'verifications'));
    }
}
