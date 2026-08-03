<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Pengingat Membership</title>
<style>body{font-family:'Segoe UI',sans-serif;background:#f4f4f4;margin:0;padding:0}.container{max-width:520px;margin:40px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08)}.header{background:linear-gradient(135deg,#f59e0b,#d97706);padding:32px 40px;text-align:center}.header h1{color:white;margin:0;font-size:20px;font-weight:800}.body{padding:40px}.info{background:#fffbeb;border:1px solid #fcd34d;border-radius:10px;padding:16px;margin:20px 0;font-size:14px;color:#92400e}.cta{display:block;margin:24px auto 0;padding:14px 32px;background:linear-gradient(135deg,#f05a2a,#c8451a);color:white;text-decoration:none;border-radius:12px;font-weight:700;text-align:center}.footer{background:#f8f8f8;padding:20px 40px;text-align:center;font-size:12px;color:#aaa}</style>
</head>
<body>
<div class="container">
    <div class="header"><h1>⏰ Pengingat Membership GMF</h1></div>
    <div class="body">
        <p style="color:#333;font-size:15px">Halo, <strong>{{ $member->full_name }}</strong>!</p>
        <div class="info">
            Membership Anda akan berakhir dalam <strong>{{ $daysLeft }} hari</strong> ({{ $member->membership_end_date->format('d M Y') }}).
        </div>
        <p style="color:#555;font-size:14px;line-height:1.7">Segera perpanjang membership Anda agar tidak terputus akses ke fasilitas Gintung Master Fitness!</p>
        <a href="{{ route('home') }}" class="cta">Perpanjang Sekarang →</a>
    </div>
    <div class="footer">© {{ date('Y') }} Gintung Master Fitness</div>
</div>
</body>
</html>
