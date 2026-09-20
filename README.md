# Ovu — Tracking Menstruasi, Kesuburan & Kesehatan

Aplikasi web PWA untuk tracking menstruasi, siklus kesuburan, pencatatan harian, dan analitik siklus. Gratis penuh, privacy-first, mobile-first.

## Tech Stack (locked)

- PHP 8.3+ (dev di 8.3.30, prod anjuran 8.5.x)
- Laravel 13.32.0 (PHP 8.3-8.5)
- MySQL 8.4 LTS prod / SQLite dev
- Nginx 1.30.5 stable prod
- Tailwind CSS v4.3.3 + Alpine.js v3.16.3 + Chart.js v4.5.1
- Push: minishlink/web-push v11.0.0 (VAPID) | PDF: barryvdh/laravel-dompdf v3.1.2

## Full Feature

1. Auth email+password, verifikasi email, 2 consent zero-use, Privasi & Syarat
2. Profil: nama, tgl lahir (umur+zodiac), TB, BB + reminder bulanan, penyakit bawaan, obat, riwayat KB/hamil, tujuan promil/KB/kesehatan
3. Impor historis haid (mulai–selesai) dari ingatan
4. Kalender + dashboard status hari ini + hormone scope edukasi
5. Log harian: darah, kram 1-5, mood, energi, tidur, lendir serviks, BBT, tes LH/testpack, intercourse, BB, diary
6. Engine prediksi: moving avg 3–6 siklus, ovulasi, fertile window, confidence, flag telat
7. Analytics: grafik siklus, pola gejala, BB vs siklus, akurasi, insight + disclaimer medis
8. Push PWA generik (tanpa data sensitif di lockscreen)
9. Sharing pasangan via kode invite 6 digit, view-only, bisa cabut
10. Laporan dokter print/PDF
11. Settings: export JSON/CSV, hapus akun total, PIN, log out semua perangkat
12. PWA offline: form log antri → sync

## Privacy

- Zero-use: data tidak dilatih ke AI, tidak dijual, tidak dibagikan tanpa izin eksplisit.
- Owner-only by default. Akses pasangan/dokter hanya via undangan, bisa dicabut.
- Tanpa tracker/iklan pihak-3. Password di-hash, HTTPS wajib.

## Dev

```bash
composer install
npm install
cp .env.example .env
# dev: DB_CONNECTION=sqlite (default)
php artisan migrate
npm run build
php artisan serve
# test: php artisan test
# vapid di server prod: php artisan ovu:vapid
```

Prod (VPS): Nginx 1.30.5 + PHP 8.3/8.5 + MySQL 8.4 + SSL + cron `php artisan schedule:run` tiap menit + backup harian.
