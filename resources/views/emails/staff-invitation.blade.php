<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Registrasi Staff — Gintung Master Fitness</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #08090d; color: #e2e8f0; margin: 0; padding: 20px; }
        .container { max-width: 560px; margin: 0 auto; background-color: #0f121a; border: 1px solid #1e293b; border-radius: 16px; padding: 32px; }
        .header { text-align: center; margin-bottom: 24px; }
        .logo { font-weight: 800; font-size: 20px; color: #ffffff; }
        .accent { color: #f05a2a; }
        .content { line-height: 1.6; font-size: 14px; color: #cbd5e1; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; background-color: rgba(240, 90, 42, 0.15); color: #f05a2a; margin-bottom: 16px; }
        .btn { display: block; width: 100%; text-align: center; background-color: #f05a2a; color: #ffffff; font-weight: 700; text-decoration: none; padding: 14px 0; border-radius: 12px; margin: 24px 0; font-size: 15px; }
        .footer { font-size: 12px; color: #64748b; text-align: center; margin-top: 24px; border-top: 1px solid #1e293b; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Gintung Master <span class="accent">Fitness</span></div>
        </div>
        <div class="content">
            <span class="badge">Undangan Akses Staff</span>
            <h2 style="color: #ffffff; margin-top: 0;">Halo! Anda Diundang Bergabung</h2>
            <p>Pemilik Gintung Master Fitness mengundang Anda untuk bergabung ke dalam sistem sebagai <strong style="color: #f05a2a;">{{ ucfirst($invitation->role) }}</strong>.</p>
            <p>Silakan klik tombol di bawah untuk melengkapi pendaftaran akun staff Anda. Link undangan ini berlaku selama <strong>48 jam</strong> (hingga {{ $invitation->expired_at->format('d M Y, H:i') }} WIB).</p>
            
            <a href="{{ $registrationUrl }}" class="btn">Lengkapi Registrasi Staff →</a>

            <p style="font-size: 12px; color: #94a3b8;">Jika tombol tidak dapat diklik, salin link berikut ke browser Anda:<br>
            <span style="word-break: break-all; color: #f05a2a;">{{ $registrationUrl }}</span></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Gintung Master Fitness Center. All Rights Reserved.
        </div>
    </div>
</body>
</html>
