<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Whistleblowing System
        BBWS Citanduy
    </title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: white;
            background: url('{{ asset('img/background.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        /* Overlay Transparan */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .navbar {
            background: transparent;
            z-index: 2;
        }

        .navbar-nav .nav-link {
            color: #FFD700;
            font-weight: bold;
        }

        .navbar-nav .nav-link:hover {
            color: #FFA500;
        }

        .content {
            position: relative;
            z-index: 2;
            padding-top: 8vh;
            padding-left: 10%;
            color: white;
            text-align: left;
        }

        .content h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .content p {
            font-size: 1.2rem;
            margin-bottom: 20px;
            max-width: 600px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        }

        .btn-custom {
            background-color: #FFD700;
            color: black;
            font-weight: bold;
            border-radius: 50px;
            padding: 10px 20px;
            transition: background-color 0.3s;
            z-index: 2;
        }

        .btn-custom:hover {
            background-color: #FFA500;
            color: white;
        }

        /* Footer Styling */
        .footer {
            background-color: #333;
            color: white;
            padding: 15px 0;
            text-align: center;
            font-weight: bold;
            z-index: 2;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.5);
        }

        .footer p {
            margin: 0;
            font-size: 0.9rem;
            color: #bbb;
        }

        .footer span {
            color: #FFD700;
        }

       .bbws-citanduy {
            font-weight: bold; /* Tebalkan teks */
            font-size: 1rem; /* Sesuaikan ukuran font */
            text-transform: uppercase; /* Ubah menjadi huruf kapital semua */
            letter-spacing: 2px; /* Beri jarak antar huruf */
            color: #FFD700; /* Warna emas */
            position: relative; /* Untuk posisi border */
            display: inline-block; /* Membuat border bisa sesuai teks */
        }
            
            /* Menambahkan outline pada teks */
        .bbws-citanduy:before {
            content: attr(data-text); /* Menggunakan data-text untuk menambahkan border */
            position: absolute; /* Posisi absolute agar tidak mempengaruhi layout */
            top: 0;
            left: 0;
            color: transparent; /* Buat teks menjadi transparan */
            font-weight: bold; /* Tetap tebal */
            font-size: 1rem; /* Sama dengan ukuran asli */
            letter-spacing: 2px; /* Jarak antar huruf */
            background: linear-gradient(90deg, #FFA500, #FFD700); /* Gradien yang kontras */
            -webkit-background-clip: text; /* Agar gradient hanya pada teks */
            -webkit-text-fill-color: transparent; /* Teks transparan untuk efek border */
            z-index: -1; /* Pastikan berada di belakang teks asli */
        }
    </style>
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
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
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
        <h1>
            Selamat Datang di
            <br />
            Whistleblowing System
            <br />
            BBWS Citanduy
        </h1>
        <p>
            Disediakan oleh BBWS Citanduy bagi Anda yang ingin melaporkan suatu perbuatan berindikasi pelanggaran
            yang terjadi di lingkungan BBWS Citanduy.
        </p>
        <p>
            <strong>
                Ayo Lapor!
            </strong>
            Satu laporan anda bermakna untuk perbaikan BBWS Citanduy.
        </p>
        <a href="{{ route('pengaduan.index') }}" class="btn btn-custom">
            <i class="fas fa-bullhorn"></i>
            LAPOR PELANGGARAN
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