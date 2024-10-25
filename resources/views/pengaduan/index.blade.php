<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .custom-header {
            /* background-color: #374979; */
            background-color: #fbbc2c;
            /* Ganti warna header */
            color: white;
            /* Warna teks putih */
        }

        .data-diri {
            display: none; /* Sembunyikan form data diri secara default */
            margin-top: 10px; /* Jarak atas */
        }

        /* Perbesar ukuran checkbox dan radio button */
        input[type="checkbox"], input[type="radio"] {
            transform: scale(1.5); /* Perbesar ukuran */
            margin-right: 10px; /* Beri jarak dari label */
        }

        /* Tambahkan warna latar belakang atau border saat dipilih */
        input[type="checkbox"]:checked, input[type="radio"]:checked {
            background-color: #fbbc2c; /* Ubah warna sesuai tema */
            border-color: #fbbc2c; /* Ubah warna border */
        }

        /* Untuk memastikan tampilan input terlihat lebih menarik di semua browser */
        input[type="checkbox"] {
            accent-color: #fbbc2c; /* Warna aksen untuk checkbox */
        }

        input[type="radio"] {
            accent-color: #fbbc2c; /* Warna aksen untuk radio button */
        }

    </style>
</head>
<body class="" style="background-color: #1d2a5b">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/citanduy.png') }}" alt="Logo" height="50"
                    class="d-inline-block align-text-center">
                &nbsp;<strong style="color: #1d2a5b">BBWS Citanduy</strong>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}"><strong style="color: #1d2a5b">Home</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><strong style="color: #1d2a5b">FAQ</strong></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-white">Form Pengaduan :</h2>
        <div class="card mb-4 mt-4">
            <div class="card-header custom-header" style="color: black;">
                <i class="fas fa-info-circle"></i> <strong>Uraian Pengaduan</strong>
            </div>
            <div class="card-body">
                <i><p>Jelaskan kasus dugaan pelanggaran secara rinci. 
                    <br>Selanjutnya silakan diuraikan mengenai apa dugaan
                    pelanggaran, siapa yang diduga terlibat, di mana dan kapan terjadinya pelanggaran tersebut.</p></i>
                <input class="form-control mb-2" placeholder="Judul Pengaduan" type="text" name="judul" />
                <textarea class="form-control" placeholder="Uraian Pengaduan" rows="5" name="uraian"></textarea>
            </div>
        </div>

       <div class="card mb-4">
        <div class="card-header custom-header" style="color: black;">
            <i class="fas fa-exclamation-triangle"></i> <strong>Kategori Pelanggaran</strong>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap">
                @foreach($kategoris as $kategori)
                <div class="form-check me-3 mb-2" style="flex: 0 0 30%;">
                    <input class="form-check-input" id="kategori{{ $kategori->id }}" name="kategori_id" type="radio"
                        value="{{ $kategori->id }}" />
                    <label class="form-check-label" for="kategori{{ $kategori->id }}">{{ $kategori->nama }}</label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

        <div class="card mb-4">
            <div class="card-header custom-header" style="color: black;">
                <i class="fas fa-user-check"></i> <strong>Status Pegawai Pelapor</strong>
            </div>
            <div class="card-body">
                <select class="form-select" style="width: 250px;" name="status" id="status">
                    <option selected value="Bukan Pegawai PUPR">Bukan Pegawai PUPR</option>
                    <option value="Pegawai PUPR">Pegawai PUPR</option>
                </select>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header custom-header" style="color: black;">
                <i class="fas fa-users"></i> <strong>Uraian Pihak Yang Diduga Terlibat</strong>
            </div>
            <div class="card-body">
                <button id="btn-add-uraian" class="btn btn-success mb-2"><i class="fas fa-plus"></i></button>
                <table class="table table-bordered table-striped" id="tableUraian">
                    <thead>
                        <tr>
                            <th>Nama Terlibat</th>
                            <th>Jabatan Terlibat</th>
                            <th>Klasifikasi</th>
                            <th>Instansi</th>
                            <th>Paket Kegiatan</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Peran</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                {{-- <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled"><a class="page-link" href="#">Pertama</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Selanjutnya</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Terakhir</a></li>
                    </ul>
                </nav> --}}
            </div>
        </div>
        <!-- Modal untuk form Uraian Pihak -->
        <div class="modal  fade" id="modalAddUraian" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Pihak Terlibat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formTambahUraian">
                            <div class="mb-3 row">
                                <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama" name="nama" required />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="jabatan"  required />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="klasifikasi" class="col-sm-3 col-form-label ">Klasifikasi</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="klasifikasi" id="klasifikasi" required>
                                        <option selected value="">Pilih Klasifikasi</option>
                                        <option value="pejabat">Pejabat</option>
                                        <option value="staff">Staff</option>
                                        <option value="penyedia barang/jasa">Penyedia Barang/Jasa</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="no_telpon" class="col-sm-3 col-form-label">No. Telpon</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="no_telpon" name="no_telpon" required />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="instansi" class="col-sm-3 col-form-label">Instansi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="instansi" name="instansi" required />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="paket_kegiatan" class="col-sm-3 col-form-label">Paket Kegiatan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="paket_kegiatan" name="paket_kegiatan" required />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="peran" class="col-sm-3 col-form-label">Peran</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="peran" name="peran" required />
                                </div>
                            </div>
                            <div class="mt-5">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning btn-sm">Simpan</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-header custom-header" style="color: black;">
                <i class="fas fa-paperclip"></i> <strong>Lampiran</strong>
            </div>
            <div class="card-body">
                <p>Anda dapat melampirkan file data/dokumen pendukung.</p>
                <button id="btn-add-lampiran" class="btn btn-success mb-2"><i class="fas fa-plus"></i></button>
                <table class="table table-bordered table-striped" id="tableLampiran">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Keterangan</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                
                    </tbody>
                </table>
                {{-- <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled"><a class="page-link" href="#">Pertama</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Selanjutnya</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Terakhir</a></li>
                    </ul>
                </nav> --}}
                <p><i>Catatan:</i></p>
                <ul>
                    <li><i>Ukuran Total File maksimal: 50 MB</i></li>
                    <li><i>Format file yang dapat dikirimkan: zip | rar | doc | docx | xls | xlsx | pdf | jpg | png | ppt |
                    pptx</i></li>
                </ul>
            </div>
        </div>
        <!-- Modal untuk form Lampiran -->
        <div class="modal  fade" id="modalAddLampiran" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Lampiran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formTambahLampiran" enctype="multipart/form-data">                            
                            <div class="mb-3 row">
                                <label for="file_lampiran" class="col-sm-3 col-form-label">File Lampiran</label>
                                <div class="col-sm-9">
                                    <input type="file" accept=".zip, .rar, .doc, .docx, .xls, .xlsx, .pdf, .jpg, .jpeg, .png, .ppt, .pptx" class="form-control" id="file_lampiran" name="file_lampiran" required />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea name="keterangan" class="form-control" id="keterangan" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="mt-5">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning btn-sm">Simpan</button>
                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-body">
                <label for="" class="form-label "><strong>Tanggal Kejadian</strong></label>
                <input style="width: 200px;" class="form-control mb-4" placeholder="Tanggal Kejadian" type="date" name="tanggal_kejadian" />
                <label for="" class="form-label "><strong>Tempat Kejadian</strong></label>
                <textarea class="form-control" name="tempat_kejadian" placeholder="Tempat Kejadian" id="" cols="30" rows="5"></textarea>
                
            </div>
        </div>

      <div class="card mb-4">
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" id="identitas" type="checkbox" />
                <label class="form-check-label" for="identitas"><strong>Apakah anda ingin mencantumkan identitas diri anda?</strong>
                    (abaikan jika anda hendak melapor secara anonim)</label>
            </div>
            <div id="dataDiriForm" class="data-diri" style="padding-left: 50px;">
                <h5 class="mt-2"></h5>
                <div class="form-group row mb-3">
                    <label for="nama_identitas" class="col-sm-2 col-form-label"><strong>Nama Lengkap</strong></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="nama_identitas" name="nama_identitas" placeholder="Masukkan nama lengkap">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="alamat_identitas" class="col-sm-2 col-form-label"><strong>Alamat</strong></label>
                    <div class="col-sm-4">
                        <textarea class="form-control" id="alamat_identitas" name="alamat_identitas" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="email_identitas" class="col-sm-2 col-form-label"><strong>Email</strong></label>
                    <div class="col-sm-4">
                        <input type="email" class="form-control" id="email_identitas" name="email_identitas" placeholder="Masukkan email">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="no_telpon_identitas" class="col-sm-2 col-form-label"><strong>No. telpon</strong></label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" id="no_telpon_identitas" name="no_telpon_identitas" placeholder="Masukkan nomor telepon">
                    </div>
                </div>
            </div>
        </div>
      </div>
        <div class="card mb-4">
            <div class="card-header custom-header" style="color: black;"><i class="fas fa-paper-plane"></i><strong> Kirim Pengaduan</strong></div>
            <div class="card-body">
                <button id="btn-kirim-pengaduan" class="btn btn-success"><i class="fas fa-paper-plane"></i> Kirim</button>
            </div>
        </div>
        
    </div>
</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearTempLampiran(fileName) {
            $.ajax({
                url: '{{ route('pengaduan.clear-temp-lampiran') }}',
                type: 'POST',
                data: {
                    file_name: fileName // Kirim nama file yang ingin dihapus
                },
                success: function (response) {
                    console.log("File sementara dihapus:", response);
                },
                error: function (xhr, status, error) {
                    console.error("Gagal menghapus file sementara:", error);
                }
            });
        }

        function clearRefresh()
        {
             $.ajax({
                url: '{{ route('pengaduan.clear-temp-lampiran.refresh') }}', // Endpoint untuk menghapus lampiran sementara
                type: 'DELETE',
                success: function (response) {
                    console.log("File sementara dihapus:", response);
                },
                error: function (xhr, status, error) {
                    console.error("Gagal menghapus file sementara:", error);
                }
            });
        }


        $(document).ready(function () {
            $('#identitas').change(function() {
                $('#dataDiriForm').slideToggle(this.checked);
            });
    
            // Fungsi untuk menampilkan modal tambah uraian
            $("#btn-add-uraian").click(function () {
                $("#modalAddUraian").modal("show");
            });
    
            // Fungsi untuk menambah uraian baru ke tabel saat form di-submit
            $("#formTambahUraian").submit(function (e) {
                e.preventDefault();
                var nama = $("#nama").val();
                var jabatan = $("#jabatan").val();
                var klasifikasi = $("#klasifikasi").val();
                var instansi = $("#instansi").val();
                var paket_kegiatan = $("#paket_kegiatan").val();
                var alamat = $("#alamat").val(); 
                var no_telp = $("#no_telpon").val(); 
                var peran = $("#peran").val();
    
                if (nama === '' || jabatan === '' || klasifikasi === '' || instansi === '' || paket_kegiatan === '') {
                    alert("Semua kolom harus diisi!");
                    return;
                }
    
                var newRow = `<tr>
                                <td>${nama}</td>
                                <td>${jabatan}</td>
                                <td>${klasifikasi}</td>
                                <td>${instansi}</td>
                                <td>${paket_kegiatan}</td>
                                <td>${alamat}</td>
                                <td>${no_telp}</td>
                                <td>${peran}</td>
                                <td><button class="btn btn-danger btn-sm btn-delete" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></button></td>
                            </tr>`;
                $("#tableUraian tbody").append(newRow);
                $("#no-data-message").remove();
                $("#modalAddUraian").modal("hide");
                $("#formTambahUraian")[0].reset();
                checkTableEmpty();
            });
    
            $("#tableUraian").on("click", ".btn-delete", function (e) {
                e.preventDefault();
                var result = confirm("Apakah Anda yakin ingin menghapus baris ini?");
                if (result) {
                    $(this).closest("tr").remove();
                    checkTableEmpty();
                }
            });
    
            function checkTableEmpty() {
                if ($("#tableUraian tbody tr").length === 0) {
                    $("#tableUraian tbody").html('<tr id="no-data-message"><td colspan="9" class="text-center">Tidak ada data</td></tr>');
                }
            }
            checkTableEmpty();
    
            //---------------------------Modal Lampiran-----------------------------
            var tempLampiran = [];
    
            $("#btn-add-lampiran").click(function () {
                $("#modalAddLampiran").modal("show");
            });
    
            // Fungsi untuk upload lampiran saat form di-submit
            $("#formTambahLampiran").submit(function (e) {
                e.preventDefault();
    
                var formData = new FormData(this);
    
                $.ajax({
                    url: '{{ route('pengaduan.upload-temp-lampiran') }}', // Endpoint sementara untuk upload
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        var newRow = `<tr>
                                        <td>${response.file_name}</td>
                                        <td>${response.keterangan}</td>
                                        <td><button class="btn btn-danger btn-sm btn-delete-lampiran">Hapus</button></td>
                                    </tr>`;
                        $("#tableLampiran tbody").append(newRow);
                        tempLampiran.push({ file_lampiran: response.file_name, keterangan: response.keterangan });
                        $("#no-data-message-lampiran").remove();
                        $("#modalAddLampiran").modal("hide");
                        $("#formTambahLampiran")[0].reset();
                        checkLampiranTableEmpty();
                    },
                    error: function (xhr, status, error) {
                        alert("Gagal mengunggah lampiran: " + error);
                    }
                });
            });
    
            $("#tableLampiran").on("click", ".btn-delete-lampiran", function (e) {
                e.preventDefault();
                var result = confirm("Apakah Anda yakin ingin menghapus lampiran ini?");
                if (result) {
                    var row = $(this).closest("tr");
                    var fileName = row.find("td:eq(0)").text(); // Ambil nama file dari kolom pertama
                    var rowIndex = row.index();

                    // Hapus baris dari tabel
                    row.remove();
                    tempLampiran.splice(rowIndex, 1); // Hapus dari array
                    checkLampiranTableEmpty();

                    // Panggil clearTempLampiran dengan nama file yang dihapus
                    clearTempLampiran(fileName);
                }
            });
    
            function checkLampiranTableEmpty() {
                if ($("#tableLampiran tbody tr").length === 0) {
                    $("#tableLampiran tbody").html('<tr id="no-data-message-lampiran"><td colspan="4" class="text-center">Tidak ada data</td></tr>');
                }
            }
            checkLampiranTableEmpty();
    
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            // Fungsi untuk menangani klik tombol Kirim Pengaduan
            $("#btn-kirim-pengaduan").click(function (e) {
                e.preventDefault();
    
                var judulPengaduan = $("input[name='judul']").val();
                var uraianPengaduan = $("textarea[name='uraian']").val();
                var kategoriId = $("input[name='kategori_id']:checked").val();
                var status = $("#status").val();
                var tanggalKejadian = $("input[name='tanggal_kejadian']").val();
                var tempatKejadian = $("textarea[name='tempat_kejadian']").val();
    
                if (!judulPengaduan || !uraianPengaduan || !kategoriId || !status || !tanggalKejadian || !tempatKejadian) {
                    alert("Semua kolom harus diisi!");
                    return;
                }
    
                var pihakTerlibat = [];
                $("#tableUraian tbody tr").each(function () {
                    var nama = $(this).find("td:eq(0)").text();
                    var jabatan = $(this).find("td:eq(1)").text();
                    var klasifikasi = $(this).find("td:eq(2)").text();
                    var instansi = $(this).find("td:eq(3)").text();
                    var paketKegiatan = $(this).find("td:eq(4)").text();
                    var alamat = $(this).find("td:eq(5)").text();
                    var no_telpon = $(this).find("td:eq(6)").text();
                    var peran = $(this).find("td:eq(7)").text();
    
                    if (nama && jabatan && klasifikasi && instansi && paketKegiatan) {
                        pihakTerlibat.push({
                            nama: nama,
                            jabatan: jabatan,
                            klasifikasi: klasifikasi,
                            instansi: instansi,
                            paket_kegiatan: paketKegiatan,
                            alamat: alamat,
                            no_telpon: no_telpon,
                            peran: peran
                        });
                    } else {
                        alert("Data pihak terlibat tidak lengkap!");
                        return;
                    }
                });
    
                var dataPengaduan = {
                    judul: judulPengaduan,
                    uraian: uraianPengaduan,
                    kategori_id: kategoriId,
                    status: status,
                    tanggal_kejadian: tanggalKejadian,
                    tempat_kejadian: tempatKejadian,
                    pihak_terlibat: pihakTerlibat,
                    lampiran: tempLampiran
                };
    
                if ($('#identitas').is(":checked")) {
                    var namaIdentitas = $("#nama_identitas").val();
                    var alamatIdentitas = $("#alamat_identitas").val();
                    var emailIdentitas = $("#email_identitas").val();
                    var noTelponIdentitas = $("#no_telpon_identitas").val();
    
                    dataPengaduan.identitas = {
                        nama_identitas: namaIdentitas,
                        alamat_identitas: alamatIdentitas,
                        email_identitas: emailIdentitas,
                        no_telpon_identitas: noTelponIdentitas
                    };
                }
    
                $.ajax({
                    url: '{{ route('pengaduan.store') }}',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(dataPengaduan),
                    success: function (response) {
                        console.log(response);
                        alert("Pengaduan berhasil dikirim!");
                        window.location.href = "{{ route('pengaduan.index') }}";
                    },
                    error: function (xhr, status, error) {
                        alert("Terjadi kesalahan: " + status);
                    }
                });
            });

            $(window).on("beforeunload", function() {
                clearRefresh();
            });
        });
    </script>
</html>