<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Perencanaan Kegiatan',
            'Pasca Pelaksanaan',
            'Barang Milik Negara (BMN)',
            'Tender/Seleksi',
            'Pelayanan Publik',
            'Lahan',
            'Pelaksanaan Kegiatan',
            'Kode Etik/Perilaku Kepegawaian',
            'Lain-lain',
        ] as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }
    }
}
