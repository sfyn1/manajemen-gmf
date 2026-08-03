<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\Coach;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function index()
    {
        $schedules  = ClassSchedule::with(['classType', 'coach.user'])->orderBy('day_of_week')->orderBy('start_time')->get();
        $classTypes = ClassType::orderBy('name')->get();
        $coaches    = Coach::active()->with('user')->get();

        // Group by day
        $dayOrder  = array_keys(ClassSchedule::DAYS);
        $grouped   = $schedules->groupBy('day_of_week')->sortBy(fn($items, $day) => array_search($day, $dayOrder));

        return view('admin.schedules.index', compact('grouped', 'classTypes', 'coaches'));
    }

    public function create()
    {
        $classTypes = ClassType::orderBy('name')->get();
        $coaches    = Coach::active()->with('user')->get();
        return view('admin.schedules.form', ['schedule' => null, 'classTypes' => $classTypes, 'coaches' => $coaches]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_type_id' => ['required', 'exists:class_types,id'],
            'coach_id'      => ['required', 'exists:coaches,id'],
            'day_of_week'   => ['required', 'in:' . implode(',', array_keys(ClassSchedule::DAYS))],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'max_capacity'  => ['required', 'integer', 'min:1'],
            'session_fee'   => ['required', 'numeric', 'min:0'],
            'is_active'     => ['boolean'],
        ]);

        ClassSchedule::create([...$data, 'is_active' => $request->boolean('is_active', true)]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal kelas berhasil ditambahkan.');
    }

    public function edit(ClassSchedule $schedule)
    {
        $classTypes = ClassType::orderBy('name')->get();
        $coaches    = Coach::active()->with('user')->get();
        return view('admin.schedules.form', compact('schedule', 'classTypes', 'coaches'));
    }

    public function update(ClassSchedule $schedule, Request $request)
    {
        $data = $request->validate([
            'class_type_id' => ['required', 'exists:class_types,id'],
            'coach_id'      => ['required', 'exists:coaches,id'],
            'day_of_week'   => ['required', 'in:' . implode(',', array_keys(ClassSchedule::DAYS))],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'max_capacity'  => ['required', 'integer', 'min:1'],
            'session_fee'   => ['required', 'numeric', 'min:0'],
            'is_active'     => ['boolean'],
        ]);

        $schedule->update([...$data, 'is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(ClassSchedule $schedule)
    {
        if ($schedule->bookings()->upcoming()->exists()) {
            return redirect()->back()->with('error', 'Jadwal masih memiliki booking mendatang. Tidak dapat dihapus.');
        }
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    // ── Class Types ────────────────────────────────────────────────────────

    public function storeType(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:class_types,name']]);
        ClassType::create(['name' => $request->name, 'is_active' => true]);
        return redirect()->route('admin.schedules.index')->with('success', 'Jenis kelas berhasil ditambahkan.');
    }

    public function updateType(ClassType $classType, Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:100', "unique:class_types,name,{$classType->id}"]]);
        $classType->update(['name' => $request->name]);
        return redirect()->route('admin.schedules.index')->with('success', 'Jenis kelas diperbarui.');
    }

    public function destroyType(ClassType $classType)
    {
        if ($classType->schedules()->exists()) {
            return redirect()->back()->with('error', 'Jenis kelas masih digunakan oleh jadwal aktif.');
        }
        $classType->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Jenis kelas dihapus.');
    }
}
