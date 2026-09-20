<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateVapidKeys extends Command
{
    protected $signature = 'ovu:vapid';
    protected $description = 'Buat VAPID key untuk push PWA (jalankan di server prod)';

    public function handle(): int
    {
        try {
            $keys = \Minishlink\WebPush\VAPID::createVapidKeys();
        } catch (\Throwable $e) {
            $this->error('Gagal membuat kunci di server ini: '.$e->getMessage());
            $this->line('Coba jalankan di VPS prod atau buat manual dengan pustaka web-push di Node.');

            return self::FAILURE;
        }

        $this->info('VAPID_PUBLIC_KEY='.$keys['publicKey']);
        $this->info('VAPID_PRIVATE_KEY='.$keys['privateKey']);
        $this->line('Salin dua nilai di atas ke file .env server prod.');

        return self::SUCCESS;
    }
}
