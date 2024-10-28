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
        Kategori::create([
            'nama' => 'Perencanaan Kegiatan',
        ]);

        Kategori::create([
            'nama' => 'Pasca Pelaksanaan',
        ]);

        Kategori::create([
            'nama' => 'Keuangan dan BMN',
        ]);

        Kategori::create([
            'nama' => 'Tender/Seleksi',
        ]);

        Kategori::create([
            'nama' => 'Pelayanan Publik',
        ]);

        Kategori::create([
            'nama' => 'Lahan',
        ]);

        Kategori::create([
            'nama' => 'Pelaksan Kegiatan',
        ]);

        Kategori::create([
            'nama' => 'Kode Etik/Perilaku Kepegawaian',
        ]);

        Kategori::create([
            'nama' => 'Lain-lain',
        ]);
    }
}
