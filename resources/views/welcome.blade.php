<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Pengaduan
        BBWS Citanduy
    </title>
    <link rel="icon" href="{{ asset('img/citanduy.png') }}" type="image/png">
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body>
    <!-- Overlay semi-transparan -->
    <div class="overlay">
    </div>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img alt="Logo" height="40" src="{{ asset('img/citanduy.png') }}" width="50" />
                <span class="bbws-citanduy" data-text="BBWS Citanduy">
                    BBWS Citanduy
                </span>
            </a>
            <button class="navbar-toggler border-warning" type="button" data-bs-toggle="collapse"
                data-bs-target="#welcomeNavbar" aria-controls="welcomeNavbar" aria-expanded="false"
                aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="welcomeNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                          <a class="nav-link" href="{{ route('faq') }}">
                            FAQ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content">
       <h1 class="mb-5">
        Selamat Datang di
        <br />
            <span class="text-pengaduan">Pengaduan</span> BBWS Citanduy
        </h1>
       <p class="mb-4">
        Layanan ini disediakan oleh BBWS Citanduy sebagai sarana bagi masyarakat untuk menyampaikan aduan,
        laporan, maupun informasi terkait pelayanan dan kegiatan di lingkungan BBWS Citanduy.
    </p>
        <p>
            <strong>
                Ayo Lapor!
            </strong>
            Satu laporan anda bermakna untuk perbaikan BBWS Citanduy.
        </p>
        <a href="{{ route('pengaduan.index') }}" class="btn btn-custom mt-2">
            <i class="fas fa-bullhorn"></i>
            LAPOR ADUAN
        </a>
    </div>

    {{-- <div class="footer mt-5">
        <p>
            INTEGRITAS
            <span>
                TANPA BATAS
            </span>
        </p>
    </div> --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>