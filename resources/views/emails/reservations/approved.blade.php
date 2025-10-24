<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reservasi Disetujui</title>
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        
        /* Ganti warna background utama halaman agar sedikit kontras dengan footer */
        body { background-color: #f4f4f4; }
    </style>
</head>

<body style="margin: 0 !important; padding: 0 !important; background-color: #f4f4f4; font-family: Arial, sans-serif;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#eaf6ff">
        <tr>
            <td align="center" style="padding: 40px 15px 30px 15px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center">
                            <img src="https://i.imgur.com/g05Z1Bq.png" alt="DinusSpace Logo" width="150" style="display: block; width: 150px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-top: 25px;">
                            <h1 style="font-size: 28px; font-weight: bold; color: #1a1a1a; margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                Reservasi anda telah Disetujui!
                            </h1>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
        <tr>
            <td align="center" style="padding: 40px 15px 40px 15px;">

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 18px; line-height: 24px; color: #333333; padding-bottom: 10px;">
                            Kabar Baik,
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 22px; font-weight: bold; line-height: 28px; color: #1a1a1a; padding-bottom: 20px;">
                            {{ $reservation->student->name }}!
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #555555;">
                            Reservasi Anda untuk tujuan "<b>{{ $reservation->purpose }}</b>" disetujui. Mohon tunjukkan surat ini kepada staff saat akan menggunakan ruangan.
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" style="padding-top: 30px;">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="border-radius: 5px;" bgcolor="#003D73">
                                        <a href="{{ $downloadUrl }}" target="_blank" style="font-size: 16px; font-family: Arial, sans-serif; font-weight: bold; color: #ffffff; text-decoration: none; padding: 12px 25px; border: 1px solid #003D73; display: inline-block; border-radius: 5px;">
                                            Unduh Surat Persetujuan
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" style="padding-top: 40px;">
                            
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="font-family: Arial, sans-serif; font-size: 16px; color: #555555;">
                                        Follow us at
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td align="center" style="padding-top: 15px;">
                                        
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 0 10px;">
                                                    <a href="https://instagram.com/bengkelkoding" target="_blank">
                                                        <img src="https://i.imgur.com/a4c1iAc.png" alt="Instagram" width="32" height="32" style="display: block;">
                                                    </a>
                                                </td>
                                                
                                                <td style="padding: 0 10px;">
                                                    <a href="https://facebook.com/your-page" target="_blank">
                                                        <img src="https://i.imgur.com/u1wA4S9.png" alt="Facebook" width="32" height="32" style="display: block;">
                                                    </a>
                                                </td>

                                                <td style="padding: 0 10px;">
                                                    <a href="https://twitter.com/your-handle" target="_blank">
                                                        <img src="https://i.imgur.com/m31eD20.png" alt="Twitter" width="32" height="32" style="display: block;">
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#eeeeee">
        <tr>
            <td align="center" style="padding: 30px 15px 30px 15px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td width="80" valign="top" align="left">
                            <img src="https://i.imgur.com/rN9xVj8.png" alt="Bengkel Koding Logo" width="70" style="display: block; width: 70px;">
                        </td>
                        <td valign="top" align="left" style="padding-left: 20px; font-family: Arial, sans-serif; font-size: 12px; line-height: 18px; color: #555555;">
                            <b>Bengkel Koding Space:</b><br>
                            Gedung H, Lantai 6,<br>
                            Jl. Imam Bonjol No.207, Pendrikan Kidul, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50131.<br>
                            Univesitas Dian Nuswantoro Semarang.
                            <br><br>
                            &copy;2025 Bengkel Koding
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f4f4f4">
        <tr>
            <td align="center" style="padding: 20px 15px 20px 15px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" style="font-family: Arial, sans-serif; font-size: 12px; line-height: 18px; color: #999999;">
                            Email ini dikirim secara otomatis. Mohon untuk tidak membalas email ini.
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>

</body>
</html>
