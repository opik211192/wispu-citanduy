<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FAQ - WISPU BBWS Citanduy</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #333;
        }

        .header {
            background: #1c2b52;
            color: white;
            padding: 20px;
        }

        .header-container {
            max-width: 1000px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.5);
            padding: 8px 15px;
            border-radius: 6px;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
        }

        .title h1 {
            color: #1c2b52;
            margin-bottom: 8px;
        }

        .title p {
            color: #666;
        }

        /* FAQ */
        .faq-item {
            background: white;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .faq-question {
            width: 100%;
            border: none;
            background: white;
            padding: 20px;
            text-align: left;
            font-size: 16px;
            font-weight: bold;
            color: #1c2b52;
            cursor: pointer;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .faq-question:hover {
            background: #f8f9fc;
        }

        .icon {
            font-size: 22px;
            transition: transform 0.3s ease;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            padding: 0 20px;
            color: #555;
            line-height: 1.7;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
            padding: 0 20px 20px;
        }

        .faq-item.active .icon {
            transform: rotate(180deg);
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #777;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }

            .container {
                margin-top: 25px;
            }

            .faq-question {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

<header class="header">
    <div class="header-container">
        <div class="logo">
            WISPU - BBWS Citanduy
        </div>

        <a href="{{ url('/') }}" class="back-btn">
            Kembali
        </a>
    </div>
</header>


<main class="container">

    <div class="title">
        <h1>Frequently Asked Questions</h1>
        <p>Pertanyaan yang sering ditanyakan mengenai layanan pengaduan WISPU.</p>
    </div>


    <!-- FAQ 1 -->
    <div class="faq-item">
        <button class="faq-question">
            <span>Apakah saya harus memiliki akun untuk membuat pengaduan?</span>
            <span class="icon">⌄</span>
        </button>

        <div class="faq-answer">
            <strong>J:</strong>
            Tidak. Masyarakat umum dapat langsung mengisi dan mengirim formulir
            pengaduan tanpa harus login atau mendaftar akun.
        </div>
    </div>


    <!-- FAQ 2 -->
    <div class="faq-item">
        <button class="faq-question">
            <span>Apakah identitas saya akan dirahasiakan?</span>
            <span class="icon">⌄</span>
        </button>

        <div class="faq-answer">
            <strong>J:</strong>
            Ya. Sesuai informasi keamanan data pada formulir, data identitas dan
            dokumen yang diunggah akan dijaga kerahasiaannya dan hanya digunakan
            untuk keperluan verifikasi serta tindak lanjut laporan.
        </div>
    </div>


    <!-- FAQ 3 -->
    <div class="faq-item">
        <button class="faq-question">
            <span>Apa saja format lampiran yang didukung?</span>
            <span class="icon">⌄</span>
        </button>

        <div class="faq-answer">
            <strong>J:</strong>
            Lampiran data/dokumen pendukung mendukung format
            zip, rar, doc, docx, xls, xlsx, pdf, jpg, png, ppt, dan pptx,
            dengan ukuran total maksimal 50 MB.
        </div>
    </div>


    <!-- FAQ 4 -->
    <div class="faq-item">
        <button class="faq-question">
            <span>Mengapa No. KTP harus 16 digit?</span>
            <span class="icon">⌄</span>
        </button>

        <div class="faq-answer">
            <strong>J:</strong>
            Karena Nomor Kartu Tanda Penduduk (KTP) di Indonesia memang terdiri
            dari 16 digit angka; pengisian yang tidak sesuai akan ditolak oleh sistem.
        </div>
    </div>


    <!-- FAQ 5 -->
    <div class="faq-item">
        <button class="faq-question">
            <span>Apa fungsi menu Login pada beranda?</span>
            <span class="icon">⌄</span>
        </button>

        <div class="faq-answer">
            <strong>J:</strong>
            Menu Login digunakan oleh petugas/administrator BBWS Citanduy untuk
            masuk ke panel pengelolaan data pengaduan, bukan untuk masyarakat umum
            yang menyampaikan laporan.
        </div>
    </div>

</main>


<footer class="footer">
    © {{ date('Y') }} BBWS Citanduy - WISPU
</footer>


<script>
    const questions = document.querySelectorAll('.faq-question');

    questions.forEach(question => {
        question.addEventListener('click', () => {

            const currentItem = question.parentElement;

// Buka/tutup FAQ yang diklik
            currentItem.classList.toggle('active');
        });
    });
</script>

</body>
</html>
