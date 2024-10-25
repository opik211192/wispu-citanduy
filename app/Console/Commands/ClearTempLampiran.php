<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearTempLampiran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp-lampiran:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus file lampiran sementara yang sudah tidak digunakan setiap hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tempLampiranPath = storage_path('app/public/temp_lampiran');
        $files = File::files($tempLampiranPath);

        foreach ($files as $file) {
            // Hapus file
            File::delete($file);
        }

         $this->info('File lampiran sementara berhasil dihapus.');
    }
}
