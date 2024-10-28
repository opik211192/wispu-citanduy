<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Lampiran;
use App\Models\Pengaduan;
use Illuminate\Support\Str;
use App\Models\Uraian_pihak;
use Illuminate\Http\Request;
use App\Models\IdentitasDiri;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::all();
        return view('pengaduan.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
        // Validasi data
            $validatedData = $request->validate([
                    'judul' => 'required|string|max:255',
                    'uraian' => 'required|string',
                    'kategori_id' => 'required',
                    'status' => 'required|string',
                    'tanggal_kejadian' => 'required|date',
                    'tempat_kejadian' => 'required|string',
                    'pihak_terlibat' => 'required|array',
                    'pihak_terlibat.*.nama' => 'required|string',
                    'pihak_terlibat.*.jabatan' => 'required|string',  // Pastikan semua data yang diperlukan ada
                    'pihak_terlibat.*.klasifikasi' => 'required|string',
                    'pihak_terlibat.*.alamat' => 'required|string',
                    'pihak_terlibat.*.no_telpon' => 'required|string',
                    'pihak_terlibat.*.instansi' => 'required|string',
                    'pihak_terlibat.*.paket_kegiatan' => 'required|string',
                    'pihak_terlibat.*.peran' => 'required|string',
                    'lampiran' => 'required|array',
                    'lampiran.*.file_lampiran' => 'required',
                    'lampiran.*.keterangan' => 'required|string',
                    'identitas' => 'nullable|array', // Jadikan identitas opsional
                    'identitas.nama_identitas' => 'nullable|string|max:255',
                    'identitas.alamat_identitas' => 'nullable|string|max:255',
                    'identitas.email_identitas' => 'nullable|email',
                    'identitas.no_telpon_identitas' => 'nullable|string|max:15', 
                ]);

            // Simpan ke tabel pengaduan
             $pengaduan = Pengaduan::create([
                'judul' => $validatedData['judul'],
                'uraian' => $validatedData['uraian'],
                'kategori_id' => $validatedData['kategori_id'],
                'status' => $validatedData['status'],
                'tanggal_kejadian' => $validatedData['tanggal_kejadian'],
                'tempat_kejadian' => $validatedData['tempat_kejadian'],
            ]);

            // //simpan ke tabel uraian pihak
            foreach ($validatedData['pihak_terlibat'] as $pihak) {
                Uraian_pihak::create([
                    'pengaduan_id' => $pengaduan->id,
                    'nama' => $pihak['nama'],
                    'jabatan' => $pihak['jabatan'],
                    'klasifikasi' => $pihak['klasifikasi'],
                    'alamat' => $pihak['alamat'],
                    'no_telpon' => $pihak['no_telpon'],
                    'instansi' => $pihak['instansi'],
                    'paket_kegiatan' => $pihak['paket_kegiatan'],
                    'peran' => $pihak['peran'],
                ]);
            }

             // Pindahkan dan simpan ke tabel lampiran
            foreach ($validatedData['lampiran'] as $file) {
                // Path file dari direktori sementara
                $tempFilePath = 'public/temp_lampiran/' . $file['file_lampiran'];
                $fileName = time() . '_' . $file['file_lampiran']; // Tambahkan timestamp untuk nama unik
                $destinationPath = 'public/lampiran/' . $fileName; // Path tujuan

                // Memeriksa apakah file ada di direktori sementara
                if (Storage::exists($tempFilePath)) {
                    // Pindahkan file ke folder lampiran
                    if (Storage::move($tempFilePath, $destinationPath)) {
                        // Simpan ke tabel lampiran
                        Lampiran::create([
                            'pengaduan_id' => $pengaduan->id,
                            'file_lampiran' => $fileName, // Simpan nama file yang baru
                            'keterangan' => $file['keterangan'],
                        ]);
                    } else {
                        // Menangani kesalahan jika file tidak dapat dipindahkan
                        return response()->json(['success' => false, 'message' => 'Gagal memindahkan file: ' . $file['file_lampiran']], 500);
                    }
                } else {
                    // Menangani kesalahan jika file tidak ditemukan
                    return response()->json(['success' => false, 'message' => 'File tidak ditemukan: ' . $file['file_lampiran']], 404);
                }
            }


            //simpan ke tabel identitas
            if (isset($validatedData['identitas'])) {
                IdentitasDiri::create([
                    'pengaduan_id' => $pengaduan->id,
                    'nama_identitas' => $validatedData['identitas']['nama_identitas'],
                    'alamat_identitas' => $validatedData['identitas']['alamat_identitas'],
                    'email_identitas' => $validatedData['identitas']['email_identitas'],
                    'no_telpon_identitas' => $validatedData['identitas']['no_telpon_identitas'],
                ]);
            }

            // Penggunaan model dan metode untuk menyimpan data dapat disesuaikan dengan kebutuhan.

            return response()->json([
                'success' => true, 
                'message' => 'Laporan berhasil disimpan',
                'data' => $validatedData
            ], 200);
        }

        return response()->json(['success' => false, 'message' => 'Permintaan tidak valid'], 400);
    }


    public function uploadTempLampiran(Request $request)
    {
        // Validasi input file dan keterangan
        $request->validate([
            'file_lampiran' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:2048', // Sesuaikan tipe dan ukuran file sesuai kebutuhan
            'keterangan' => 'required|string'
        ]);

        // Simpan file di storage sementara
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('temp_lampiran', $fileName, 'public'); // folder `temp_lampiran` di storage

            // Kirim respon JSON ke AJAX dengan nama file dan keterangan
            return response()->json([
                'file_name' => $fileName,
                'keterangan' => $request->keterangan,
                'file_path' => Storage::url($filePath) // URL akses file jika dibutuhkan
            ]);

            return response()->json(['error' => 'Gagal mengunggah lampiran'], 500);
        }
    }

    public function uploadTempLampiranClear(Request $request)
    {
        $fileName = $request->input('file_name'); // Mendapatkan nama file dari request
        $tempLampiranPath = storage_path('app/public/temp_lampiran/' . $fileName); // Path ke file yang ingin dihapus

        // Menghapus file tertentu
        if (File::exists($tempLampiranPath)) {
            File::delete($tempLampiranPath);
            return response()->json(['message' => 'Lampiran sementara dihapus.'], 200);
        }

        return response()->json(['message' => 'File tidak ditemukan.'], 404);
    }

    public function uploadClearRefresh(Request $request)
    {
        $tempLampiranPath = storage_path('app/public/temp_lampiran');

        // Menghapus semua file dalam folder temp_lampiran
        if (File::exists($tempLampiranPath)) {
            $files = File::files($tempLampiranPath);
            foreach ($files as $file) {
                File::delete($file);
            }
        }

        //return response()->json(['message' => 'Lampiran sementara dihapus.'], 200);
    }


    //-----------------------------------------ADMIN CONTROLLER-----------------------------------------//

    public function dataPengaduan()
    {
        //buatkan data pengaduan berdasarkan order by tanggal sekarang paginasi 10
        $pengaduans = Pengaduan::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pengaduan.index', compact('pengaduans'));

    }
    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengaduan $pengaduan)
    {
        //
    }
}
