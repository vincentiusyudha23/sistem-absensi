<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body style="margin:0; padding:0; background:#1f7a4c; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:30px 10px;">

                <!-- CARD -->
                <table width="400" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:12px; padding:25px;">

                    <!-- LOGO + TITLE -->
                    <tr>
                        <td align="center" style="padding-bottom:15px;">
                            <img src="{{ asset('assets/img/logo-1.png') }}" width="70" style="display:block; margin:auto;">

                            <h3 style="margin:10px 0 5px; color:#1f7a4c; font-size:18px; text-align:center;">
                                ABSENSI PUSPALAD
                            </h3>

                            <div style="font-size:12px; color:#666;">
                                PUSAT PERALATAN ANGKATAN DARAT
                            </div>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="font-size:14px; color:#333; line-height:1.6;">

                            <p style="margin:0 0 10px;">Halo,</p>

                            <p style="margin:0 0 10px;">
                                Kami menerima permintaan untuk reset password akun Anda.
                            </p>

                            <p style="margin:0 0 20px;">
                                Klik tombol di bawah ini untuk melanjutkan:
                            </p>

                        </td>
                    </tr>

                    <!-- BUTTON -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <a href="{{ $actionUrl ?? '#' }}"
                                style="background:#1f7a4c; color:#ffffff; padding:12px 22px;
                          text-decoration:none; border-radius:6px; font-size:14px; display:inline-block;">
                                Reset Password
                            </a>
                        </td>
                    </tr>

                    <!-- FOOTER TEXT -->
                    <tr>
                        <td style="font-size:12px; color:#666; line-height:1.5;">
                            <p style="margin:0 0 5px;">Link ini berlaku selama 60 menit.</p>
                            <p style="margin:0;">Jika Anda tidak meminta reset password, abaikan email ini.</p>
                        </td>
                    </tr>

                    <!-- COPYRIGHT -->
                    <tr>
                        <td align="center" style="padding-top:20px; font-size:11px; color:#aaa;">
                            © 2026 PUSPALAD - TNI AD
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
