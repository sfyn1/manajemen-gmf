<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function index()
    {
        $member    = auth()->user()->member;
        $documents = $member->documents()->latest()->get();
        $renewals  = $member->renewals()->with(['package', 'processor'])->latest()->get();

        return view('member.invoice.index', compact('member', 'documents', 'renewals'));
    }
}
