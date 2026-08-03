<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Member Ditolak — Gintung Master Fitness</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Segoe UI', Arial, sans-serif; background: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .container { max-width: 540px; margin: 40px auto; background: #ffffff; border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { color: rgba(255,255,255,0.85); margin: 6px 0 0 0; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .body { padding: 36px 40px; }
        .reason-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 16px; padding: 18px 20px; margin: 20px 0; }
        .refund-box { background: #fff8f6; border: 1.5px dashed #f05a2a; border-radius: 16px; padding: 20px; margin: 24px 0; }
        .cta { display: block; margin: 24px auto 0; padding: 14px 32px; background: #f05a2a; color: #ffffff !important; text-decoration: none; border-radius: 14px; font-weight: 700; font-size: 14px; text-align: center; box-shadow: 0 4px 12px rgba(240, 90, 42, 0.25); }
        .cta:hover { background: #d9481c; }
        .footer { background: #f8fafc; padding: 20px 40px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pendaftaran Belum Berhasil</h1>
            <p>GINTUNG MASTER FITNESS</p>
        </div>
        <div class="body">
            <p style="color:#0f172a; font-size:15px; margin-top:0;">Halo, <strong>{{ $member->full_name }}</strong>,</p>
            <p style="color:#475569; font-size:14px; line-height:1.6;">
                Pendaftaran Anda sebagai member Gintung Master Fitness <strong>belum dapat disetujui</strong> oleh administrator.
            </p>
            
            <div class="reason-box">
                <p style="margin:0 0 6px 0; font-size:12px; font-weight:800; color:#dc2626; text-transform:uppercase; tracking-wider">Catatan Alasan Penolakan:</p>
                <p style="margin:0; color:#7f1d1d; font-size:14px; font-weight:600; line-height:1.5;">{{ $reason }}</p>
            </div>

            <div class="refund-box">
                <p style="margin:0 0 8px 0; font-weight:800; color:#f05a2a; font-size:13px; text-transform:uppercase; letter-spacing:0.5px;">PROSEDUR PENGEMBALIAN DANA (REFUND) & PENDAFTARAN:</p>
                <p style="margin:0 0 8px 0; color:#334155; font-size:13px; line-height:1.5;">
                    Jika Anda telah mengirimkan pembayaran transfer QRIS / Kasir:
                </p>
                <ol style="margin:0; padding-left:18px; color:#475569; font-size:13px; line-height:1.6;">
                    <li style="margin-bottom:6px;">Silakan datang langsung ke <strong>Meja Kasir Admin Gintung Master Fitness</strong> (Tangerang Selatan).</li>
                    <li style="margin-bottom:6px;">Tunjukkan bukti transfer asli beserta pesan email ini kepada petugas kami.</li>
                    <li>Staf kasir kami akan memproses <strong>pengembalian dana (refund) 100%</strong> secara tunai/QRIS atau membantu <strong>pendaftaran secara langsung di tempat</strong>.</li>
                </ol>
            </div>

            <p style="color:#475569; font-size:13px; line-height:1.6;">
                Anda juga dapat mendaftar ulang secara mandiri melalui website dengan melengkapi data atau bukti transfer yang sesuai.
            </p>
            
            <a href="{{ route('register.step1') }}" class="cta">Daftar Ulang Mandiri →</a>
        </div>
        <div class="footer">© {{ date('Y') }} Gintung Master Fitness • Tangerang Selatan</div>
    </div>
</body>
</html>
