<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    // ── Step 1: Data Diri ─────────────────────────────────────────────────────

    public function showStep1()
    {
        return view('member.register.step1');
    }

    public function postStep1(Request $request)
    {
        $data = $request->validate([
            'full_name'  => ['required', 'string', 'max:100'],
            'nik'        => ['required', 'digits:16'],
            'gender'     => ['required', 'in:male,female'],
            'birth_date' => ['required', 'date', 'before:today'],
            'address'    => ['required', 'string', 'max:500'],
            'phone'      => ['required', 'string', 'max:20'],
            'email'      => ['required', 'email', 'max:150'],
        ]);

        // Cek anti-duplikasi Email (User & Member)
        $emailUser   = User::where('email', $data['email'])->exists();
        $emailMember = Member::where('email', $data['email'])
            ->whereIn('status', [Member::STATUS_PENDING, Member::STATUS_ACTIVE])
            ->first();

        if ($emailUser || $emailMember) {
            return back()->withErrors([
                'email' => 'Alamat email ini sudah terdaftar dalam sistem. Gunakan alamat email lain.',
            ])->withInput();
        }

        // Cek anti-duplikasi Nomor Telepon
        $phoneMember = Member::where('phone', $data['phone'])
            ->whereIn('status', [Member::STATUS_PENDING, Member::STATUS_ACTIVE])
            ->first();

        if ($phoneMember) {
            return back()->withErrors([
                'phone' => 'Nomor telepon/WhatsApp ini sudah terdaftar. Gunakan nomor lain.',
            ])->withInput();
        }

        // Cek anti-duplikasi NIK
        $nikMember = Member::where('nik', $data['nik'])
            ->whereIn('status', [Member::STATUS_PENDING, Member::STATUS_ACTIVE])
            ->first();

        if ($nikMember) {
            return back()->withErrors([
                'nik' => 'NIK (16 Digit) ini sudah terdaftar sebagai member aktif/peninjauan.',
            ])->withInput();
        }

        session(['reg_step1' => $data, 'reg_nik' => $data['nik']]);

        return redirect()->route('register.step2');
    }

    // ── Step 2: Pilih Paket ───────────────────────────────────────────────────

    public function showStep2()
    {
        if (! session('reg_step1')) {
            return redirect()->route('register.step1');
        }

        $packages = MembershipPackage::active()->get();
        return view('member.register.step2', compact('packages'));
    }

    public function postStep2(Request $request)
    {
        $data = $request->validate([
            'membership_package_id' => ['required', 'exists:membership_packages,id'],
        ]);

        session(['reg_step2' => $data]);
        return redirect()->route('register.step3');
    }

    // ── Step 3: Upload Dokumen Identitas + Pasfoto Profil ─────────────────────

    public function showStep3()
    {
        if (! session('reg_step2')) {
            return redirect()->route('register.step2');
        }

        $packageId = session('reg_step2.membership_package_id');
        $package   = MembershipPackage::findOrFail($packageId);

        return view('member.register.step3', compact('package'));
    }

    public function postStep3(Request $request)
    {
        if (! session('reg_step2')) {
            return redirect()->route('register.step2');
        }

        $packageId = session('reg_step2.membership_package_id');
        $package   = MembershipPackage::findOrFail($packageId);

        $docLabel = $package->requiresKtm() ? 'KTM/Kartu Pelajar' : 'KTP';

        $request->validate([
            'identity_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'profile_photo'     => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'identity_document.required' => "File {$docLabel} wajib diunggah.",
            'profile_photo.required'     => 'Pasfoto profil wajah wajib diunggah/diambil foto.',
            'profile_photo.image'        => 'File pasfoto profil harus berupa gambar (JPG, JPEG, PNG).',
        ]);

        $identityPath = $request->file('identity_document')->store('documents/identity', 'public');
        $photoPath    = $request->file('profile_photo')->store('profile-photos', 'public');

        session([
            'reg_step3' => [
                'identity_document_path' => $identityPath,
                'identity_document_type' => $package->requiresKtm() ? 'ktm' : 'ktp',
                'profile_photo_path'     => $photoPath,
            ],
        ]);

        return redirect()->route('register.step4');
    }

    // ── Step 4: Upload Bukti Pembayaran ───────────────────────────────────────

    public function showStep4()
    {
        if (! session('reg_step3')) {
            return redirect()->route('register.step3');
        }

        $packageId = session('reg_step2.membership_package_id');
        $package   = MembershipPackage::findOrFail($packageId);

        return view('member.register.step4', compact('package'));
    }

    public function postStep4(Request $request)
    {
        if (! session('reg_step3')) {
            return redirect()->route('register.step3');
        }

        $request->validate([
            'payment_proof'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'payment_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $paymentPath = $request->file('payment_proof')
            ->store('documents/payments', 'public');

        // Kumpulkan semua data dari session
        $step1 = session('reg_step1');
        $step2 = session('reg_step2');
        $step3 = session('reg_step3');

        $nik      = $step1['nik'];
        $existing = Member::where('nik', $nik)->first();

        $memberData = array_merge($step1, [
            'membership_package_id' => $step2['membership_package_id'],
            'profile_photo_path'     => $step3['profile_photo_path'] ?? null,
            'status'                => Member::STATUS_PENDING,
            'rejection_reason'      => null,
        ]);

        if ($existing && $existing->status === Member::STATUS_REJECTED) {
            // Hapus dokumen lama jika ada
            if ($existing->latestDocument) {
                Storage::disk('public')->delete($existing->latestDocument->identity_document_path);
                Storage::disk('public')->delete($existing->latestDocument->payment_proof_path);
                $existing->documents()->delete();
            }

            $existing->update($memberData);
            $member = $existing;
        } else {
            // Buat member baru
            $member = Member::create($memberData);
        }

        // Simpan dokumen
        $member->documents()->create([
            'identity_document_path' => $step3['identity_document_path'],
            'identity_document_type' => $step3['identity_document_type'],
            'payment_proof_path'     => $paymentPath,
            'payment_amount'         => $request->payment_amount,
        ]);

        // Bersihkan session registrasi
        session()->forget(['reg_step1', 'reg_step2', 'reg_step3', 'reg_nik']);

        return redirect()->route('register.success')
            ->with('member_name', $member->full_name);
    }

    public function success()
    {
        return view('member.register.success');
    }
}
