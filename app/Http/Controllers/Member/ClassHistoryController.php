<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;

class ClassHistoryController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        $bookings = $member->bookings()
            ->with(['schedule.classType', 'schedule.coach'])
            ->whereIn('status', [ClassBooking::STATUS_ATTENDED, ClassBooking::STATUS_NO_SHOW, ClassBooking::STATUS_CANCELLED])
            ->orderByDesc('booking_date')
            ->paginate(15);

        return view('member.class-history.index', compact('bookings', 'member'));
    }
}
