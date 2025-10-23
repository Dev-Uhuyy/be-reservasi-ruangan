<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Reservasi Ditolak</title>
    <!--
      Gaya CSS ini dibuat inline untuk kompatibilitas email client maksimum.
    -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            background-color: #f8fafc;
            color: #3d4852;
            line-height: 1.5;
            margin: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 32px;
        }

        .wrapper {
            background-color: #ffffff;
            border: 1px solid #e8e5ef;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .content {
            padding: 48px;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #3d4852;
            margin-top: 0;
            margin-bottom: 16px;
        }

        p {
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 24px;
        }

        .panel {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
            font-size: 15px;
            line-height: 1.6;
        }

        .button-wrapper {
            text-align: center;
            margin-top: 32px;
            margin-bottom: 32px;
        }

        .button {
            display: inline-block;
            background-color: #2563eb;
            /* Biru */
            border-radius: 8px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            padding: 14px 28px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            padding-top: 32px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="content">
                <!-- Judul Utama -->
                <h1>Reservasi Ditolak</h1>

                <!-- Sapaan -->
                <p>Yth. [Nama Mahasiswa],</p>

                <!-- Isi Email -->
                <p>Dengan menyesal kami informasikan bahwa reservasi Anda telah ditolak.</p>
                <p>Berikut adalah catatan atau alasan penolakan dari admin:</p>

                <!-- Panel Alasan -->
                <div class="panel">
                    [Alasan penolakan akan muncul di sini. Misalnya: Ruangan sudah penuh pada jadwal yang diminta.]
                </div>

                <!-- Paragraf Penutup -->
                <p>Anda dapat mengajukan reservasi baru kapan saja melalui dashboard. Jika ada pertanyaan, jangan ragu
                    untuk menghubungi kami.</p>

                <!-- Tombol Aksi -->
                <div class="button-wrapper">
                    <a href="https://link-dashboard-anda.com" class="button">
                        Kunjungi Dashboard
                    </a>
                </div>

                <!-- Salam Penutup -->
                <p style="margin-bottom: 0;">Terima kasih,<br>
                    Tim Admin Reservasi</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 [Nama Aplikasi Anda]. Semua hak cipta dilindungi.</p>
        </div>
    </div>
</body>

</html>
