<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 520px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #f05a2a, #e13b12); padding: 32px 40px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 22px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.85); margin: 6px 0 0; font-size: 13px; }
        .body { padding: 40px; }
        .otp-box { background: #f8f4f2; border: 2px solid #f05a2a; border-radius: 12px; padding: 24px; text-align: center; margin: 24px 0; }
        .otp-code { font-size: 44px; font-weight: 900; letter-spacing: 10px; color: #f05a2a; display: block; }
        .otp-label { font-size: 12px; color: #888; margin-top: 8px; }
        .info { background: #fff8f5; border-left: 4px solid #f05a2a; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: 13px; color: #666; margin: 20px 0; }
        .footer { background: #f8f8f8; padding: 20px 40px; text-align: center; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gintung Master Fitness</h1>
            <p>Kode OTP Pemulihan Kata Sandi</p>
        </div>
        <div class="body">
            <p style="color:#333; font-size:15px;">Halo,</p>
            <p style="color:#555; font-size:14px; line-height:1.6;">
                Permintaan pemulihan kata sandi untuk akun Anda telah diterima. Masukkan kode OTP berikut:
            </p>

            <div class="otp-box">
                <span class="otp-code">{{ $otp }}</span>
                <p class="otp-label">Masa berlaku kode: <strong>10 Menit</strong></p>
            </div>

            <div class="info">
                Dilarang membagikan kode verifikasi ini kepada siapapun demi keamanan akun Anda. Abaikan email ini jika Anda tidak merasa meminta reset kata sandi.
            </div>

            <p style="color:#888; font-size:13px; margin-top:24px;">
                Hormat kami,<br><strong style="color:#333;">Tim Gintung Master Fitness</strong>
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} Gintung Master Fitness Center. All rights reserved.
        </div>
    </div>
</body>
</html>
