# Ovu — Tracking Menstruasi, Kesuburan & Kesehatan

Aplikasi web PWA ala Flo untuk tracking menstruasi, ovulasi, kehamilan, dan kesehatan harian. Gratis penuh tanpa paywall, privacy-first, mobile-first, Bahasa Indonesia.

## Tech Stack (locked)

- PHP 8.3+ (dev di 8.3.30, prod anjuran 8.5.x)
- Laravel 13.32.0 (PHP 8.3-8.5)
- MySQL 8.4 LTS prod / SQLite dev
- Nginx 1.30.5 stable prod
- Tailwind CSS v4.3.3 + Alpine.js v3.16.3 + Chart.js v4.5.1
- Push: minishlink/web-push v11.0.0 (VAPID) | PDF: barryvdh/laravel-dompdf v3.1.2

## Fitur (remake ala Flo)

1. Onboarding wizard 5 langkah: tujuan, haid terakhir, siklus biasa, profil, kesehatan dasar
2. Beranda: ring siklus SVG, hitung mundur ovulasi/haid, peluang hamil, strip 7/14 hari, tombol Catat Haid, cerita harian, kartu Asisten Ovu, widget Siklus Saya standar ACOG
3. Katalog 71 gejala dalam 11 kategori + bottom sheet catat + toggle kategori per user
4. Kalender konvensi Flo (merah solid/putus, teal subur/ovulasi, luteal gelap) + edit haid + navigasi 5 tahun
5. Proyeksi rolling 5 tahun berjangkar data real: bergeser otomatis tiap ada haid baru
6. Prediksi dari 1 data haid via angka siklus tipikal + confidence jujur
7. Wawasan: grafik siklus dan gejala, pemeriksa pola PCOS/endometriosis, laporan dokter PDF
8. 4 tujuan: pantau, promil, KB (bahasa eksplisit + pengingat pil harian), hamil (usia, HPL, checklist trimester)
9. Mode remaja edukatif: bahasa aman, tanpa konten intim eksplisit
10. Pasangan: explainer + kode undangan 24 jam + batas sensitif + cabut akses
11. Kunci PIN aplikasi, push PWA generik (tanpa data sensitif di lockscreen), export JSON, hapus akun total
12. PWA offline: form log antri lalu sync. Data di server per akun, ganti HP aman

## Privacy

- Zero-use: tidak dilatih ke AI, tidak dijual, nol dibagikan ke pihak ketiga, tanpa tracker/iklan.
- Owner-only default. Pasangan hanya via undangan, bisa dicabut.
- Password hash, PIN opsional, HTTPS wajib.

## Dev

```bash
composer install
npm install
cp .env.example .env
# dev: DB_CONNECTION=sqlite (default)
php artisan migrate
npm run build
php artisan serve
# test: php artisan test (19 tests, 104 assertions)
# vapid di server prod: php artisan ovu:vapid
```

Prod (VPS): Nginx 1.30.5 + PHP 8.3/8.5 + MySQL 8.4 + SSL + cron `php artisan schedule:run` tiap menit + backup harian.
