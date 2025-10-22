<!DOCTYPE html>
<html>
<head>
    <title>Reservasi Disetujui</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6;">
    <h1>Kabar Baik, {{ $reservation->student->name }}!</h1>
    <p>Reservasi Anda untuk tujuan "<strong>{{ $reservation->purpose }}</strong>" telah disetujui.</p>
    <p>Silakan unduh surat persetujuan resmi melalui tautan di bawah ini. Mohon tunjukkan surat ini kepada staff saat akan menggunakan ruangan.</p>

    <a href="{{ $downloadUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 15px;">
        Unduh Surat Persetujuan
    </a>

    <p style="margin-top: 20px;">Terima kasih.</p>
</body>
</html>