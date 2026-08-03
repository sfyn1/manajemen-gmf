<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Member Disetujui</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 520px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #10b981, #059669); padding: 32px 40px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 20px; font-weight: 800; }
        .body { padding: 40px; }
        .badge { display: inline-block; background: #ecfdf5; border: 1px solid #10b981; color: #047857; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 99px; margin-bottom: 20px; }
        .cta { display: block; margin: 28px auto 0; padding: 14px 32px; background: linear-gradient(135deg, #f05a2a, #e13b12); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; text-align: center; font-size: 14px; }
        .footer { background: #f8f8f8; padding: 20px 40px; text-align: center; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pendaftaran Member Disetujui</h1>
        </div>
        <div class="body">
            <span class="badge">STATUS VERIFIKASI: DISETUJUI</span>
            <p style="color:#333; font-size:15px;">Halo, <strong>{{ $member->full_name }}</strong>,</p>
            <p style="color:#555; font-size:14px; line-height:1.7;">
                Pendaftaran Anda sebagai member <strong>Gintung Master Fitness</strong> telah diverifikasi dan secara resmi disetujui.
            </p>
            <table style="width:100%; border-collapse:collapse; margin:20px 0; font-size:14px;">
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:10px 0; color:#888;">Paket Membership</td>
                    <td style="padding:10px 0; font-weight:700; color:#333;">{{ $member->package->name }}</td>
                </tr>
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:10px 0; color:#888;">Masa Berlaku</td>
                    <td style="padding:10px 0; font-weight:700; color:#333;">
                        {{ $member->membership_start_date?->format('d M Y') }} — {{ $member->membership_end_date?->format('d M Y') }}
                    </td>
                </tr>
            </table>

            @if($tempPassword)
            <div style="background:#f8fafc; border:1px border #cbd5e1; padding:16px; border-radius:12px; margin:20px 0;">
                <p style="margin:0 0 8px; font-weight:700; color:#1e293b; font-size:14px;">Kredensial Akses Akun Anda:</p>
                <p style="margin:4px 0; font-size:13px; color:#475569;">Email: <strong>{{ $member->email }}</strong></p>
                <p style="margin:4px 0; font-size:13px; color:#475569;">Password Sementara: <strong style="color:#f05a2a; font-size:15px; font-family:monospace;">{{ $tempPassword }}</strong></p>
                <p style="margin:8px 0 0; font-size:11px; color:#94a3b8;">*Gunakan kredensial di atas untuk masuk ke portal member pertama kali.</p>
            </div>
            @endif

            <p style="color:#555; font-size:14px;">Silakan login untuk mengakses Kode QR Presensi dan reservasi sesi kelas.</p>
            <a href="{{ route('login') }}" class="cta">Masuk ke Portal Member →</a>
        </div>
        <div class="footer">© {{ date('Y') }} Gintung Master Fitness Center</div>
    </div>
</body>
</html>
