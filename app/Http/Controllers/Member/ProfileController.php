<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member->load(['package', 'user', 'latestDocument']);
        return view('member.profile.index', compact('member'));
    }
}
