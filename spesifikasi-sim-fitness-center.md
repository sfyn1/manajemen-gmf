# Spesifikasi Sistem Informasi Manajemen Fitness Center

## 1. Overview

Sistem informasi manajemen fitness center berbasis web menggunakan **Laravel 13** (rilis Maret 2026, minimum **PHP 8.3**). Tidak ada breaking changes dari Laravel 12, sehingga struktur, routing, dan aturan di dokumen ini tetap berlaku penuh. Sistem mendukung 4 aktor: **Admin**, **Coach (Pelatih)**, **Member**, dan **Owner**. Owner merangkap fungsi *super admin* — pemegang kendali penuh atas manajemen akun staff (Admin, Coach, Owner lain) sekaligus pemilik dashboard analitik bisnis.

Model bisnis: fitness center dengan membership bulanan (reguler & pelajar) dan harian (daily pass), kelas grup (bukan personal training) yang dipimpin coach dan dibayar per sesi mengajar, serta penjualan produk sederhana di tempat.

**Batasan sistem (tidak ada payment gateway)**: semua pembayaran dilakukan secara manual — QRIS statis + upload bukti transfer yang diverifikasi admin, atau cash/QRIS langsung di tempat untuk transaksi tatap muka. Tidak ada integrasi API payment gateway (Midtrans/Xendit/dll).

---

## 2. Autentikasi (berlaku untuk semua aktor)

- Login menggunakan **email + password**.
- Semua aktor bisa: login, logout, lupa password.
- Lupa password memakai **OTP dikirim ke email**.
- Akun **Owner** dibuat manual di awal (seed/inisialisasi sistem), tidak ada mekanisme registrasi untuk role ini.
- Akun **Admin & Coach** dibuat lewat **invite-token self-register**:
  1. **Owner** input email calon staff + pilih role (Admin/Coach) di halaman **Manajemen Akun Staff** (fungsi super admin, tetap di modul Owner).
  2. Sistem generate **token unik** (random string) + waktu kadaluarsa (misal 48 jam), status token: `belum dipakai` / `sudah dipakai` / `kadaluarsa`.
  3. Sistem kirim email otomatis berisi link registrasi bertoken ke email tersebut, contoh: `/register/staff?token=xxx`.
  4. Sistem validasi token saat link dibuka: kalau tidak ada/salah/kadaluarsa/sudah dipakai → akses ditolak. Kalau valid → tampilkan form isi data diri (email terkunci sesuai token, tidak bisa diubah).
  5. Setelah submit, akun langsung **aktif** dengan role sesuai yang ditentukan Owner di langkah 1, token ditandai `sudah dipakai` (tidak bisa dipakai ulang).
  - Ini mencegah orang yang bukan staff asli ikut mendaftar, karena hanya pemilik email yang diundang Owner yang punya akses ke form registrasi.
- Akun **Member** dibuat melalui **self-registration terbuka** (lihat alur di bagian 3.5) — beda dengan Admin/Coach karena member memang publik/calon pelanggan, bukan staff internal.
- Member **tidak bisa login** sebelum akunnya di-approve oleh Admin (status `pending` → `active`).

---

## 3. Aturan Lintas Sistem (Cross-Cutting Rules)

### 3.1 QR Code
- QR **hanya digunakan untuk presensi masuk gym** (pintu masuk), **bukan** untuk presensi kelas.
- Fungsi QR: mengecek apakah masa membership member masih aktif atau sudah habis. Kalau habis, member tidak bisa masuk.
- Member baru mendapat QR **hanya setelah status akunnya `active`** (sudah di-approve admin).
- QR untuk **daily pass expired otomatis dalam 24 jam** dari waktu aktivasi.
- QR untuk member bulanan (reguler/pelajar) berlaku sampai tanggal jatuh tempo membership.

### 3.2 Presensi Kelas vs Presensi Gym
- Presensi masuk gym (via QR) **terpisah** dari presensi/kehadiran di kelas grup.
- Booking kelas grup **hanya untuk member bulanan (reguler & pelajar)**. Member **daily pass tidak bisa booking kelas grup**.
- Kehadiran di kelas dibuktikan lewat verifikasi foto realtime oleh coach (lihat 3.4), bukan lewat QR.

### 3.3 Pembayaran Kelas (Model B — Bayar di Tempat)
- Booking kelas **gratis di muka** (tidak ada pembayaran saat booking).
- Member membayar biaya kelas **saat datang/hadir di sesi**, seperti transaksi tunai/QRIS biasa yang dicatat admin.
- **Tidak ada fitur refund** karena tidak ada uang dibayar di depan.
- Cancel booking kelas hanya bisa dilakukan **maksimal H-1** sebelum jadwal kelas (tidak bisa cancel di hari H).
- Risiko no-show diterima sebagai keterbatasan sistem (opsional pengembangan lanjutan: batasi jumlah booking aktif per member atau catat histori no-show).

### 3.4 Verifikasi Kehadiran Coach
- Setelah kelas selesai, coach membuka kamera realtime dan mengirim foto bukti sebagai laporan kehadiran mengajar.
- Coach wajib submit foto verifikasi **maksimal 1 jam** setelah kelas selesai.
- Kalau **coach tidak submit sama sekali dalam 1 jam** → sesi otomatis dianggap **tidak terlaksana**, coach **tidak menerima komisi** untuk sesi tersebut (auto, tanpa perlu aksi admin).
- Kalau admin **menolak** foto yang disubmit → coach **bisa submit ulang (resubmit)**.
- Verifikasi ini masuk dalam **Approval Center** (lihat 3.6), bukan halaman terpisah.

### 3.5 Registrasi Member (Self-Service)
Alur:
1. Calon member mengisi data diri sendiri di halaman publik/registrasi.
2. Memilih jenis paket: **Reguler**, **Harian**, atau **Pelajar**.
3. Upload dokumen sesuai jenis paket:
   - **Pelajar** → wajib upload **kartu pelajar/KTM**.
   - **Reguler & Harian** → wajib upload **KTP**.
4. Melakukan pembayaran via **QRIS statis** yang ditampilkan sistem, lalu **upload bukti transfer**.
5. Status akun menjadi `pending_verification`.
6. Admin mengecek kesesuaian data diri dengan KTP/dokumen dan bukti pembayaran, lalu **approve** atau **reject**.
7. Sistem mengirim **email otomatis** (registrasi berhasil/gagal) ke calon member.
8. Kalau **approved** → status jadi `active`, member baru bisa login dan mendapat QR.
9. Kalau **rejected** → member bisa mendaftar ulang; sistem **tidak boleh membuat data duplikat**.

**Kebijakan setelah ditolak (rejected):**
- Email penolakan otomatis mencantumkan alasan penolakan, **dan dua opsi penyelesaian**:
  - **(a) Daftar Ulang** — kalau member bisa memperbaiki sendiri (dokumen buram, data salah), submit ulang secara online. Pembayaran yang sudah ada **tetap dipakai**, tidak perlu bayar lagi (mengikuti rule update-record-lama di tabel anti-duplikasi).
  - **(b) Datang langsung ke gym** — kalau member ingin diselesaikan tatap muka, termasuk kalau tidak ingin melanjutkan dan minta uangnya kembali.
- **Refund dilakukan manual secara tatap muka** di gym (cash atau transfer manual saat itu juga oleh admin) — **tidak ada fitur refund online**, tidak perlu menyimpan nomor rekening member di sistem.
- Setelah refund diberikan secara langsung, admin masuk ke halaman **Manajemen Membership** dan menandai status registrasi tersebut menjadi **"Sudah Direfund"** (toggle + catatan opsional), supaya tercatat untuk kebutuhan laporan.
- Dampak ke laporan: nominal yang sudah direfund **mengurangi angka pemasukan** pada bulan terjadinya di dashboard Owner, supaya total pemasukan yang ditampilkan adalah angka bersih (net), bukan kotor.

**Aturan anti-duplikasi (berdasarkan NIK sebagai unique identifier):**
| Kondisi NIK di sistem | Aksi saat submit registrasi baru |
|---|---|
| Belum pernah terdaftar | Buat data registrasi baru |
| Sudah ada, status `rejected` | **Update record lama** (timpa data & dokumen), status kembali `pending` — **jangan buat baris baru** |
| Sudah ada, status `pending` | **Tolak submit**, tampilkan pesan "pendaftaran masih dalam proses peninjauan" |
| Sudah ada, status `active` | **Tolak submit**, tampilkan pesan "NIK sudah terdaftar sebagai member aktif" |

### 3.6 Approval Center (Gabungan)
Satu halaman/menu terpusat di sisi Admin yang menggabungkan:
- Approval **registrasi member baru** (cek data diri, dokumen, bukti pembayaran)
- Approval **verifikasi kehadiran coach** (foto realtime)

Dilengkapi **notifikasi real-time dengan suara** setiap ada item baru yang masuk antrian approval (butuh arsitektur WebSocket, lihat bagian 6).

### 3.7 Edit Profil Member
- Setelah status `active`, **data profil member terkunci total** — tidak bisa diedit sendiri oleh member (nama, NIK, dokumen, paket, dll mengikuti data KTP yang sudah diverifikasi).
- Perubahan data (jika diperlukan) harus melalui Admin secara manual di modul Manajemen Membership.

---

## 4. Modul Admin

1. **Dashboard Analytics** — ringkasan seluruh aktivitas sistem dalam satu halaman (fleksibel, berkembang mengikuti fitur baru).
2. **Manajemen Membership** — kelola data member terdaftar (bulanan & harian), termasuk approval registrasi baru (bagian dari Approval Center).
3. **Scan QR** — halaman scan QR via kamera untuk presensi masuk gym + riwayat kunjungan member hari itu.
4. **Manajemen Pelatih**:
   - Data pelatih
   - Jenis kelas (mis. Zumba, Pound Fit — kelas grup, bukan personal training)
   - Jadwal kelas, termasuk **kapasitas maksimal peserta per kelas**
   - Approval absensi/kehadiran pelatih (bagian dari Approval Center)
5. **Manajemen Paket Membership** — kelola paket Reguler, Harian, Pelajar beserta syarat dokumen masing-masing dan harga.
6. **Manajemen Penjualan Produk** — pencatatan stok manual, riwayat penjualan, metode bayar (cash/QRIS), tanpa scan barcode.
7. **Payroll Pelatih** — admin set rate per sesi, sistem akumulasi otomatis per bulan berdasarkan sesi yang **approved**, riwayat pembayaran payroll.
8. **CMS Landing Page** — kelola foto-foto landing page dan harga paket yang tampil di landing page (idealnya harga ditarik otomatis dari data paket di poin 5, agar tidak ada duplikasi/inkonsistensi data).
9. **Approval Center** — lihat bagian 3.6.
10. **Notifikasi** — notifikasi real-time bersuara untuk setiap item baru di Approval Center.

---

## 5. Modul Member

1. **Dashboard** — kartu QR, tanggal expired membership, riwayat kunjungan gym.
2. **Booking Kelas** — pilih & booking kelas grup (hanya member bulanan/reguler & pelajar; daily pass tidak bisa akses fitur ini). Bayar di tempat saat hadir (lihat 3.3).
3. **Riwayat Kelas Diambil** — daftar kelas yang sudah dibooking beserta tanggal & jam mulai.
4. **Riwayat Pembayaran/Invoice** — histori transaksi member (pembayaran membership, dll).
5. **Cancel Booking Kelas** — hanya bisa maksimal H-1 sebelum jadwal kelas.
6. **Profil** — data terkunci (read-only) setelah status `active`.

---

## 6. Modul Coach

1. **Dashboard** — jadwal kelas yang akan dibimbing.
2. **Verifikasi Kehadiran** — buka kamera realtime, kirim foto ke Admin untuk approval (maksimal 1 jam setelah kelas selesai, bisa resubmit jika ditolak).
3. **Riwayat Sesi Mengajar** — daftar sesi beserta status approval (approved/rejected/tidak terlaksana).
4. **Riwayat/Slip Komisi** — histori komisi yang sudah diterima per bulan.
5. **Roster Peserta** — daftar member yang booking kelasnya, untuk estimasi kapasitas kehadiran.

---

## 7. Modul Owner

1. **Dashboard Analitik** — total **pemasukan** (bukan pengeluaran/P&L) dalam rentang waktu, dengan **breakdown per sumber**: membership, kelas, penjualan produk. Mendukung **custom date range** (bukan cuma per bulan).
2. **Unduh Laporan** — laporan pemasukan dengan rentang tanggal custom.
3. **Manajemen Akun Staff** — kirim undangan (invite-token) untuk akun Admin & Coach baru, lihat status undangan (belum dipakai/sudah dipakai/kadaluarsa), serta kelola (nonaktifkan) akun staff yang sudah ada. Fungsi super admin, lihat detail alur di bagian 2.

> **Catatan Batasan Masalah**: dashboard Owner hanya mencakup pemasukan, **tidak mencakup pencatatan pengeluaran operasional** (sewa, listrik, maintenance, dll) di luar payroll. Ini adalah batasan sistem yang disengaja, bukan bug — perlu dicantumkan eksplisit di bab Batasan Masalah skripsi.

---

## 8. State / Status yang Perlu Dimodelkan

| Entitas | Status yang mungkin |
|---|---|
| Member | `pending_verification` → `active` / `rejected`, lalu `expired` setelah masa membership habis |
| Booking Kelas | `booked` → `attended` / `no_show` / `cancelled` |
| Verifikasi Kehadiran Coach | `pending` → `approved` / `rejected` (bisa resubmit) / `auto_failed` (tidak submit dalam 1 jam → tidak dibayar) |
| Produk | stok tersedia (manual, tanpa barcode) |
| QR Member | aktif mengikuti masa berlaku membership (bulanan: sampai tanggal jatuh tempo; harian: 24 jam) |

---

## 9. Kebutuhan Teknis Tambahan (di luar CRUD biasa)

- **Notifikasi real-time bersuara ke Admin**: butuh **Laravel Reverb** (atau Pusher) + **Laravel Echo** di frontend untuk broadcast event, dikombinasikan dengan browser Notification API + audio.
- **Email reminder** (H-1 kelas, membership akan habis, hasil registrasi): butuh **Laravel Task Scheduling** (cron job harian) dikombinasikan dengan **Queue + Mail** agar pengiriman tidak memblokir request.
- **OTP lupa password**: dikirim via email, disarankan pakai Queue juga.
- **Role & Permission**: disarankan pakai package `spatie/laravel-permission` untuk mengelola 4 role (Admin, Coach, Member, Owner) secara terstruktur.

---

## 10. Struktur Folder & Arsitektur Kode

Tujuan: kode rapi, gampang dibaca, dan jelas pemisahan antar 4 aktor serta antar modul/fitur. Prinsip yang dipakai: **pisah berdasarkan Role dulu di level Controller & View, lalu pisah berdasarkan Module/Fitur di dalamnya.** Model & migration tetap mengikuti domain data (bukan per role, karena satu entitas seperti `Member` dipakai lintas role).

### 10.1 Routing
Pisahkan file route per role, jangan ditumpuk semua di `web.php`:
```
routes/
├── web.php          # hanya landing page publik + include file lain
├── auth.php          # login, logout, forgot password (shared 4 aktor)
├── admin.php          # semua route prefix /admin, middleware role:admin
├── coach.php          # semua route prefix /coach, middleware role:coach
├── member.php          # semua route prefix /member, middleware role:member
└── owner.php          # semua route prefix /owner, middleware role:owner
```
Gunakan **route group** dengan prefix + name + middleware per role, misal:
```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    require __DIR__.'/admin.php';
});
```

### 10.2 Controllers — dipisah per Role, lalu per Modul
```
app/Http/Controllers/
├── Auth/
│   ├── LoginController.php
│   ├── LogoutController.php
│   └── ForgotPasswordController.php
├── Admin/
│   ├── DashboardController.php
│   ├── MembershipController.php
│   ├── ScanQrController.php
│   ├── CoachManagementController.php
│   ├── ClassScheduleController.php
│   ├── PackageController.php
│   ├── ProductSaleController.php
│   ├── PayrollController.php
│   ├── LandingPageContentController.php
│   └── ApprovalCenterController.php
├── Coach/
│   ├── DashboardController.php
│   ├── AttendanceVerificationController.php
│   ├── TeachingHistoryController.php
│   ├── CommissionController.php
│   └── RosterController.php
├── Member/
│   ├── DashboardController.php
│   ├── RegistrationController.php      (self-register, sebelum login)
│   ├── ClassBookingController.php
│   ├── ClassHistoryController.php
│   ├── InvoiceController.php
│   └── ProfileController.php
└── Owner/
    ├── DashboardController.php
    ├── ReportController.php
    └── StaffAccountController.php
```
Namespace mengikuti folder, contoh: `App\Http\Controllers\Admin\MembershipController`.

### 10.3 Models — dikelompokkan per domain data (bukan per role)
```
app/Models/
├── User.php                  # akun login (semua role), field: role, email, password
├── StaffInvitation.php       # token undangan Admin/Coach: email, role, token, status (belum dipakai/sudah dipakai/kadaluarsa), expired_at
├── Member.php                # profil member + status (pending/active/rejected) + refund_status (null/refunded) untuk registrasi yang ditolak
├── MembershipPackage.php     # paket: reguler, harian, pelajar
├── MembershipDocument.php    # dokumen upload (KTP/KTM) + bukti transfer
├── Coach.php
├── ClassType.php             # jenis kelas (Zumba, Pound Fit, dll)
├── ClassSchedule.php         # jadwal + kapasitas maksimal
├── ClassBooking.php          # booking kelas oleh member
├── AttendanceVerification.php # foto verifikasi kehadiran coach + status approval
├── Payroll.php
├── Product.php
├── ProductSale.php
├── Visit.php                 # riwayat presensi QR masuk gym
└── LandingPageContent.php
```

### 10.4 Views — Blade, dipisah per Role dengan Layout Sidebar masing-masing
```
resources/views/
├── layouts/
│   ├── guest.blade.php         # landing page, login, register
│   ├── admin.blade.php         # layout + sidebar Admin
│   ├── coach.blade.php         # layout + sidebar Coach
│   ├── member.blade.php        # layout + sidebar Member
│   └── owner.blade.php         # layout + sidebar Owner
├── components/
│   ├── sidebar/
│   │   ├── admin-sidebar.blade.php
│   │   ├── coach-sidebar.blade.php
│   │   ├── member-sidebar.blade.php
│   │   └── owner-sidebar.blade.php
│   ├── navbar.blade.php
│   └── ui/                     # komponen reusable: button, card, modal, badge, dll
├── auth/
│   ├── login.blade.php
│   └── forgot-password.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── membership/index.blade.php
│   ├── scan-qr/index.blade.php
│   ├── coach-management/{index,schedule}.blade.php
│   ├── packages/index.blade.php
│   ├── products/{index,history}.blade.php
│   ├── payroll/{index,history}.blade.php
│   ├── landing-content/index.blade.php
│   └── approval-center/index.blade.php
├── coach/
│   ├── dashboard.blade.php
│   ├── attendance/verify.blade.php
│   ├── history/index.blade.php
│   ├── commission/index.blade.php
│   └── roster/index.blade.php
├── member/
│   ├── dashboard.blade.php
│   ├── booking/index.blade.php
│   ├── class-history/index.blade.php
│   ├── invoice/index.blade.php
│   └── profile/index.blade.php
└── owner/
    ├── dashboard.blade.php
    ├── report/index.blade.php
    └── staff/index.blade.php
```
Setiap layout (`admin.blade.php`, `coach.blade.php`, dst) memuat 1 sidebar tetap + `@yield('content')` / `{{ $slot }}`, supaya setiap halaman modul tinggal `@extends('layouts.admin')` tanpa menulis ulang sidebar.

### 10.5 Bagian pendukung lain
```
app/Http/Requests/{Role}/{Module}/StoreXRequest.php   # validasi per modul
app/Services/{Module}Service.php                        # logika bisnis kompleks (mis. hitung payroll, cek kuota kelas)
app/Notifications/                                       # email & broadcast notification
app/Events/ + app/Listeners/                              # untuk notifikasi real-time (Reverb)
database/migrations/                                      # urutan sesuai dependency antar tabel
database/seeders/                                          # data dummy per role untuk testing
```

---

## 11. UI/UX — Modern, Responsive, Sidebar Layout

- **Frontend stack yang disarankan**: Blade + **Tailwind CSS** (bawaan starter kit Laravel 12) + **Alpine.js** untuk interaktivitas ringan (toggle sidebar, dropdown, modal) tanpa perlu build SPA penuh.
- **Sidebar sebagai layout tetap**, bukan diulang di tiap halaman:
  - Desktop (≥1024px / `lg:`): sidebar tampil permanen di kiri, konten di kanan.
  - Tablet/mobile (< 1024px): sidebar disembunyikan default, muncul sebagai **off-canvas drawer** yang dibuka lewat tombol hamburger, dengan overlay gelap di belakangnya (pakai Alpine.js `x-data`/`x-show` + transition).
- Konsisten pakai **container/grid responsive** Tailwind (`grid-cols-1 md:grid-cols-2 lg:grid-cols-3`, dst) untuk card dashboard, tabel di-scroll horizontal di mobile (`overflow-x-auto`) supaya tabel data (mis. riwayat transaksi) tidak pecah di layar kecil.
- Desain per role bisa dibedakan lewat **aksen warna sidebar** (misal Admin biru, Coach hijau, Member ungu, Owner gelap/emas) supaya user langsung sadar sedang berada di area siapa — membantu juga saat demo sidang skripsi.
- Komponen UI umum (button, badge status, card, modal, toast notification) dibuat sebagai **Blade component** reusable di `resources/views/components/ui/`, dipakai di semua role, supaya tampilan konsisten dan tidak copy-paste style berulang.
- Untuk notifikasi real-time bersuara di Admin (bagian 9), badge counter di sidebar/navbar Admin sebaiknya update otomatis via Laravel Echo tanpa reload halaman.

---

## 12. Ringkasan Aturan Bisnis Penting (Quick Reference)

- Tidak ada payment gateway — semua manual (QRIS statis + bukti transfer / cash di tempat).
- QR hanya untuk presensi gym, cek validitas membership saja.
- Kelas grup: booking gratis, bayar di tempat, tanpa refund, cancel maksimal H-1.
- Daily pass: tidak bisa booking kelas, QR expired 24 jam, tetap wajib self-register (data diri + KTP).
- Paket Pelajar: wajib upload KTM/kartu pelajar. Paket Reguler & Harian: wajib upload KTP.
- Registrasi member: validasi NIK unik, anti-duplikasi sesuai tabel di bagian 3.5.
- Registrasi ditolak: member bisa daftar ulang online (pakai pembayaran lama) atau datang langsung ke gym untuk refund manual tatap muka; admin tandai "Sudah Direfund" di sistem, nominal refund mengurangi angka pemasukan Owner di bulan itu.
- Profil member terkunci total setelah aktif.
- Verifikasi kehadiran coach: 1 jam window, resubmit jika ditolak, auto-gagal (tidak dibayar) jika tidak submit sama sekali.
- Payroll: per sesi (rate ditentukan admin), akumulasi bulanan dari sesi yang approved.
- Approval member baru + approval kehadiran coach digabung dalam satu Approval Center dengan notifikasi real-time bersuara.
- Owner: dashboard income-only (breakdown per sumber), custom date range, plus kelola akun staff (fungsi super admin).
- Admin & Coach: akun dibuat via invite-token dari Owner (email + link registrasi unik terbatas waktu), bukan self-register bebas — mencegah orang yang bukan staff asli ikut mendaftar. 