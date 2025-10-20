// resources/views/pdfs/approval-letter.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>Surat Persetujuan Reservasi</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 0; }
        .content { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SURAT PERSETUJUAN PEMINJAMAN RUANGAN</h2>
        <h3>Gedung H - Universitas Dian Nuswantoro</h3>
    </div>
    <hr>
    <div class="content">
        <p>Dengan ini menyatakan bahwa pengajuan peminjaman ruangan oleh:</p>
        <table>
            <tr>
                <th style="width: 150px;">Nama</th>
                <td>{{ $reservation->student->name }}</td>
            </tr>
            <tr>
                <th>NIM</th>
                <td>{{ $reservation->student->nim }}</td>
            </tr>
            <tr>
                <th>Tujuan Peminjaman</th>
                <td>{{ $reservation->purpose }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Telah <strong>DISETUJUI</strong> dengan rincian sebagai berikut:</p>

        <table>
            <thead>
                <tr>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservation->details as $detail)
                    <tr>
                        <td>{{ optional($detail->room)->room_name ?? 'N/A' }}</td>
                        <td>{{ optional($detail->schedule)->date ? \Carbon\Carbon::parse($detail->schedule->date)->format('d F Y') : 'N/A' }}</td>
                        <td>{{ optional($detail->schedule)->start_time ? \Carbon\Carbon::parse($detail->schedule->start_time)->format('H:i') : 'N/A' }} - {{ optional($detail->schedule)->end_time ? \Carbon\Carbon::parse($detail->schedule->end_time)->format('H:i') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 30px;">Surat ini sah dan dapat digunakan sebagai bukti persetujuan saat akan menggunakan ruangan.</p>
    </div>
</body>
</html>