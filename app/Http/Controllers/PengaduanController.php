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
use App\Services\WhatsAppService;

class PengaduanController extends Controller
{
    protected $whatsappService;

public function __construct(WhatsAppService $whatsappService)
{
    $this->middleware('auth')->except([
        'index',
        'store',
        'uploadTempLampiran',
        'uploadTempLampiranClear',
        'uploadClearRefresh',
    ]);

    $this->whatsappService = $whatsappService;
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = $this->kategoriPilihan();
        return view('pengaduan.pengaduan', compact('kategoris'));
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

                'pihak_terlibat' => 'nullable|array',
                'pihak_terlibat.*.nama' => 'nullable|string',
                'pihak_terlibat.*.jabatan' => 'nullable|string',
                'pihak_terlibat.*.klasifikasi' => 'nullable|string',
                'pihak_terlibat.*.alamat' => 'nullable|string',
                'pihak_terlibat.*.no_telpon' => 'nullable|string',
                'pihak_terlibat.*.instansi' => 'nullable|string',
                'pihak_terlibat.*.paket_kegiatan' => 'nullable|string',
                'pihak_terlibat.*.peran' => 'nullable|string',

                'lampiran' => 'required|array',
                'lampiran.*.file_lampiran' => 'required',
                'lampiran.*.keterangan' => 'required|string',

                'identitas' => 'required|array',

                'identitas.nama_identitas' => 'required|string|max:255',
                'identitas.no_ktp' => 'required|digits:16',
                'identitas.email_identitas' => 'required|email|max:255',
                'identitas.no_telpon_identitas' => 'required|string|max:15',

                'identitas.foto_identitas' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
                'identitas.foto_ktp' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            ], [
                'lampiran.*.keterangan.required' => 'Keterangan lampiran tidak boleh kosong',
            ]);

            // Tentukan nomor laporan berdasarkan tanggal hari ini
            $nomorLaporan = (Pengaduan::whereDate('created_at', today())
                ->max('nomor_laporan') ?? 0) + 1;

            $kodeLaporan = now()->format('dmY') .
                        str_pad($nomorLaporan, 3, '0', STR_PAD_LEFT);

            $pengaduan = Pengaduan::create([
                'nomor_laporan' => $nomorLaporan,
                'kode_laporan' => $kodeLaporan,
                'judul' => $validatedData['judul'],
                'uraian' => $validatedData['uraian'],
                'kategori_id' => $validatedData['kategori_id'],

                // Status asli pengaduan
                'status' => $validatedData['status'],

                // Status proses admin
                'status_proses' => 'Menunggu',

                'tanggal_kejadian' => $validatedData['tanggal_kejadian'],
                'tempat_kejadian' => $validatedData['tempat_kejadian'],
            ]);

            // //simpan ke tabel uraian pihak (opsional, boleh kosong)
            foreach ($validatedData['pihak_terlibat'] ?? [] as $pihak) {
                Uraian_pihak::create([
                    'pengaduan_id' => $pengaduan->id,
                    'nama' => $pihak['nama'] ?? '',
                    'jabatan' => $pihak['jabatan'] ?? '',
                    'klasifikasi' => $pihak['klasifikasi'] ?? '',
                    'alamat' => $pihak['alamat'] ?? '',
                    'no_telpon' => $pihak['no_telpon'] ?? '',
                    'instansi' => $pihak['instansi'] ?? '',
                    'paket_kegiatan' => $pihak['paket_kegiatan'] ?? '',
                    'peran' => $pihak['peran'] ?? '',
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
                $identitas = $validatedData['identitas'];

                // Simpan file foto identitas & foto KTP ke storage
                $fotoIdentitasName = time() . '_identitas_' . $identitas['foto_identitas']->getClientOriginalName();
                $identitas['foto_identitas']->storeAs('public/identitas', $fotoIdentitasName);

                $fotoKtpName = time() . '_ktp_' . $identitas['foto_ktp']->getClientOriginalName();
                $identitas['foto_ktp']->storeAs('public/identitas', $fotoKtpName);

                IdentitasDiri::create([
                    'pengaduan_id' => $pengaduan->id,
                    'nama_identitas' => $identitas['nama_identitas'],
                    'no_ktp' => $identitas['no_ktp'],
                    'email_identitas' => $identitas['email_identitas'],
                    'no_telpon_identitas' => $identitas['no_telpon_identitas'],
                    'foto_identitas' => $fotoIdentitasName,
                    'foto_ktp' => $fotoKtpName,
                ]);
                // =====================================================
                // NOTIFIKASI WHATSAPP
                // =====================================================

                try {
                    $whatsapp = app(WhatsAppService::class);

                // ==========================================
                // 1. NOTIFIKASI KE PELAPOR
                // ==========================================

                $nomorPelapor = $identitas['no_telpon_identitas'];

                // Mengubah 08xxxxxxxx menjadi 628xxxxxxxx
                if (str_starts_with($nomorPelapor, '0')) {
                    $nomorPelapor = '62' . substr($nomorPelapor, 1);
                }

                $pesan =
                    "Halo {$pengaduan->identitasDiri->nama_identitas},\n\n" .
                    "Kami informasikan bahwa pengaduan Anda telah berhasil diterima oleh BBWS Citanduy.\n\n" .
                    "Berikut rincian laporan Anda:\n\n" .
                    "Kode Laporan: #{$pengaduan->kode_laporan}\n" .
                    "Judul: {$pengaduan->judul}\n\n" .
                    "Laporan Anda telah tercatat dalam sistem Pengaduan BBWS Citanduy dan akan dilakukan pemeriksaan sesuai dengan proses yang berlaku.\n\n" .
                    "Mohon menyimpan kode laporan tersebut untuk keperluan informasi dan tindak lanjut laporan.\n\n" .
                    "Terima kasih atas laporan dan partisipasi Anda.\n\n" .
                    "BBWS Citanduy";

                $whatsapp->sendToPelapor(
                    $nomorPelapor,
                    $pesan
                );


                // ==========================================
                // 2. NOTIFIKASI KE ADMIN
                // ==========================================

                $pesanAdmin =
                    "🔔 LAPORAN PENGADUAN BARU\n\n" .
                    "Telah masuk laporan pengaduan baru melalui website " .
                    "Pengaduan BBWS Citanduy.\n\n" .
                    "Kode Laporan: {$pengaduan->kode_laporan}\n" .
                    "Judul: {$pengaduan->judul}\n" .
                    "Pelapor: {$identitas['nama_identitas']}\n" .
                    "No. Telp: {$identitas['no_telpon_identitas']}\n" .
                    "Status: {$pengaduan->status}\n" .
                    "Tanggal Kejadian: {$pengaduan->tanggal_kejadian}\n" .
                    "Tempat Kejadian: {$pengaduan->tempat_kejadian}\n\n" .
                    "Silakan periksa laporan pada sistem admin " .
                    "Pengaduan BBWS Citanduy.";

                $whatsapp->sendToAdmin(
                    $pesanAdmin
                );

                } catch (\Exception $e) {

                    // Jika WhatsApp gagal dikirim,
                    // laporan tetap dianggap berhasil tersimpan.
                    \Log::error(
                        'Gagal mengirim notifikasi WhatsApp: ' .
                        $e->getMessage()
                    );
                }
            }

            // Penggunaan model dan metode untuk menyimpan data dapat disesuaikan dengan kebutuhan.

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil disimpan',
                'data' => ['pengaduan_id' => $pengaduan->id]
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
        ], [
            'keterangan.required' => 'Keterangan tidak boleh kosong',
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

public function dataPengaduan(Request $request)
{
    $search = $request->input('search');
    $tanggalMulai = $request->input('tanggal_mulai');
    $tanggalAkhir = $request->input('tanggal_akhir');

    $pengaduans = Pengaduan::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('uraian', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('kode_laporan', 'like', "%{$search}%");
            });
        })

        // Filter tanggal mulai
        ->when($tanggalMulai, function ($query, $tanggalMulai) {
            $query->whereDate('created_at', '>=', $tanggalMulai);
        })

        // Filter tanggal akhir
        ->when($tanggalAkhir, function ($query, $tanggalAkhir) {
            $query->whereDate('created_at', '<=', $tanggalAkhir);
        })

        // Pengaduan terbaru di paling atas
        ->orderBy('created_at', 'desc')

        ->paginate(10)
        ->withQueryString();

    if ($request->ajax()) {
        return view('admin.pengaduan._table', compact(
            'pengaduans',
            'search',
            'tanggalMulai',
            'tanggalAkhir'
        ))->render();
    }

    return view('admin.pengaduan.index', compact(
        'pengaduans',
        'search',
        'tanggalMulai',
        'tanggalAkhir'
    ));
}

    /**
     * Data notifikasi laporan baru (belum dibaca) untuk navbar admin.
     */
    public function notifikasi()
    {
        $unread = Pengaduan::where('is_read', false)
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $count = Pengaduan::where('is_read', false)->count();

        $dropdown = view('admin.pengaduan._notif_dropdown', compact('unread', 'count'))->render();

        return response()->json([
            'label' => $count,
            'label_color' => 'danger',
            'dropdown' => $dropdown,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan)
    {
        // Tandai laporan sudah dibaca saat dibuka admin
        if (! $pengaduan->is_read) {
            $pengaduan->update(['is_read' => true]);
        }

        $pengaduan->load('kategori', 'uraianPihaks', 'lampirans', 'identitasDiri');

        // Mengarahkan langsung ke tampilan cetak / tindak lanjut
        return view('admin.pengaduan.print', compact('pengaduan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengaduan $pengaduan)
    {
        $kategoris = $this->kategoriPilihan();

        return view('admin.pengaduan.edit', compact('pengaduan', 'kategoris'));
    }

    private function kategoriPilihan()
    {
        $urutan = [
            'Perencanaan Kegiatan',
            'Pasca Pelaksanaan',
            'Barang Milik Negara (BMN)',
            'Tender/Seleksi',
            'Pelayanan Publik',
            'Lahan',
            'Pelaksanaan Kegiatan',
            'Kode Etik/Perilaku Kepegawaian',
            'Lain-lain',
        ];

        $tersedia = Kategori::orderBy('id')->get()->keyBy(function ($kategori) {
            $nama = trim(preg_replace('/\s+/', ' ', $kategori->nama));
            return $nama === 'BMN' ? 'Barang Milik Negara (BMN)' : $nama;
        });

        return collect($urutan)
            ->filter(fn ($nama) => $tersedia->has($nama))
            ->map(function ($nama) use ($tersedia) {
                $kategori = $tersedia->get($nama);
                $kategori->nama = $nama;
                return $kategori;
            })
            ->values();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'uraian' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'status' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'tempat_kejadian' => 'required|string',
        ]);

        $pengaduan->update($validatedData);

        return redirect()->route('data-pengaduan')->with('success', 'Pengaduan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Pengaduan $pengaduan)
        {
            // Simpan tanggal laporan sebelum data dihapus
            $tanggalLaporan = $pengaduan->created_at->toDateString();

            // Simpan nomor laporan yang dihapus
            $nomorDihapus = $pengaduan->nomor_laporan;

            // Hapus file lampiran dari storage
            foreach ($pengaduan->lampirans as $lampiran) {
                Storage::delete('public/lampiran/' . $lampiran->file_lampiran);
            }

            // Hapus file foto identitas & foto KTP dari storage
            $identitas = $pengaduan->identitasDiri;

            if ($identitas) {
                if ($identitas->foto_identitas) {
                    Storage::delete('public/identitas/' . $identitas->foto_identitas);
                }

                if ($identitas->foto_ktp) {
                    Storage::delete('public/identitas/' . $identitas->foto_ktp);
                }
            }

            // Hapus data pengaduan
            $pengaduan->delete();

            // Rapikan nomor laporan pada tanggal yang sama
            Pengaduan::whereDate('created_at', $tanggalLaporan)
                ->where('nomor_laporan', '>', $nomorDihapus)
                ->orderBy('nomor_laporan')
                ->get()
                ->each(function ($item) {
                    $item->decrement('nomor_laporan');
                });

            return redirect()
                ->route('data-pengaduan')
                ->with('success', 'Pengaduan berhasil dihapus.');
        }
    /**
 * Halaman print/tindak lanjut pengaduan.
 */
public function print(Pengaduan $pengaduan)
{
    $pengaduan->load(
        'kategori',
        'uraianPihaks',
        'lampirans',
        'identitasDiri'
    );

    return view('admin.pengaduan.print', compact('pengaduan'));
}


/**
 * Kirim notifikasi WhatsApp kepada pelapor
 * bahwa laporan sedang ditindaklanjuti.
 */
    public function tindakLanjut(Pengaduan $pengaduan)
    {
        try {
            $pengaduan->load('identitasDiri');

            // Pastikan identitas pelapor tersedia
        if ($pengaduan->status_proses === 'Ditolak') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan sudah ditolak dan tidak dapat ditindaklanjuti.'
            ], 400);
        }

        if ($pengaduan->status_proses === 'Ditindaklanjuti') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan sudah ditindaklanjuti.'
            ], 400);
        }
        // =====================================================
        // NOMOR WHATSAPP PELAPOR
        // =====================================================

        $nomorPelapor = $pengaduan->identitasDiri->no_telpon_identitas;

        // Ubah format 08xxxxxxxx menjadi 628xxxxxxxx
        if (str_starts_with($nomorPelapor, '0')) {
            $nomorPelapor = '62' . substr($nomorPelapor, 1);
        }

        // =====================================================
        // PESAN WHATSAPP
        // =====================================================

        $kode_laporan = $pengaduan->created_at->format('dmY') .
               str_pad($pengaduan->kode_laporan, 3, '0', STR_PAD_LEFT);

        $pesan =
            "Halo {$pengaduan->identitasDiri->nama_identitas},\n\n" .
            "Kami informasikan bahwa laporan pengaduan Anda dengan rincian berikut:\n\n" .
            "Kode Laporan: #{$pengaduan->kode_laporan}\n" .
            "Judul: {$pengaduan->judul}\n\n" .
            "Laporan pengaduan Anda telah ditindaklanjuti oleh BBWS Citanduy.\n\n" .
            "Mohon menunggu proses penanganan selanjutnya. " .
            "Informasi perkembangan laporan akan disampaikan sesuai proses yang berlaku.\n\n" .
            "Terima kasih atas laporan dan partisipasi Anda.\n\n" .
            "BBWS Citanduy";

        // =====================================================
        // KIRIM WHATSAPP
        // =====================================================

        $whatsapp = app(WhatsAppService::class);

        $response = $whatsapp->sendToPelapor(
            $nomorPelapor,
            $pesan
        );

        // Ambil response dari API WatZap
        $responseData = $response->json();

        \Log::info('RESPONSE WATZAP:', $responseData);

        // =====================================================
        // JIKA BERHASIL
        // =====================================================

        if (
            $response &&
            $response->successful() &&
            isset($responseData['status']) &&
            $responseData['status'] == '200'
        ) {

            $pengaduan->update([
                'status_proses' => 'Ditindaklanjuti',
                'notifikasi_wa_terkirim_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengaduan berhasil ditindaklanjuti dan notifikasi WhatsApp telah dikirim.'
            ]);
        }
        // =====================================================
        // JIKA GAGAL
        // =====================================================

        return response()->json([
            'success' => false,
            'message' => 'Notifikasi WhatsApp gagal dikirim. Laporan belum dicetak.'
        ], 500);

    } catch (\Exception $e) {

        \Log::error(
            'Gagal mengirim notifikasi tindak lanjut WhatsApp: ' .
            $e->getMessage()
        );

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mengirim notifikasi WhatsApp.'
        ], 500);

    }
}

public function ditolak(Request $request, Pengaduan $pengaduan)
    
    {
        
        try {

            $request->validate([
                'pesan_penolakan' => 'required|string|min:3',
            ]);

            // Ambil data identitas pelapor
            $identitas = $pengaduan->identitasDiri;

            if (!$identitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data identitas pelapor tidak ditemukan.',
                ], 500);
            }

            $namaPelapor = $identitas->nama_identitas;

            // Pesan yang ditulis admin dari popup
            $pesanPenolakan = trim($request->pesan_penolakan);

            // ==========================================
            // PESAN WHATSAPP KE PELAPOR
            // ==========================================

            $pesanPelapor =
                "Halo {$namaPelapor},\n\n" .
                "Kami informasikan bahwa pengaduan Anda dengan rincian berikut:\n\n" .
                "Kode Laporan: #{$pengaduan->kode_laporan}\n" .
                "Judul: {$pengaduan->judul}\n\n" .
                "{$pesanPenolakan}\n\n" .
                "Terima kasih atas perhatian dan kerja sama Anda.\n\n" .
                "BBWS Citanduy";


            // ==========================================
            // KIRIM WHATSAPP
            // ==========================================

            $responsePelapor = $this->whatsappService->sendToPelapor(
                $identitas->no_telpon_identitas,
                $pesanPelapor
            );


            // ==========================================
            // CEK HASIL WHATSAPP
            // ==========================================

            if (!$responsePelapor || !$responsePelapor->successful()) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Notifikasi WhatsApp gagal dikirim. Status pengaduan belum diubah.'
                ], 500);
            }


            // ==========================================
            // UPDATE STATUS
            // ==========================================

            $pengaduan->update([
                'status_proses' => 'Ditolak',
                'notifikasi_wa_terkirim_at' => now(),
            ]);


            // ==========================================
            // BERHASIL
            // ==========================================

            return response()->json([
                'success' => true,
                'message' =>
                    'Pengaduan berhasil ditolak dan notifikasi WhatsApp telah dikirim.',
            ]);


        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Pesan penolakan wajib diisi.',
            ], 422);


        } catch (\Throwable $e) {

            \Log::error(
                'Gagal memproses pengaduan ditolak',
                [
                    'pengaduan_id' => $pengaduan->id,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses penolakan pengaduan.',
            ], 500);

        }
    }
}
