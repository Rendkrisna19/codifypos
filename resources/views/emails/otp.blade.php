<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f9f9f9; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; border: 1px solid #eeeeee; text-align: center; }
        .logo { font-size: 24px; font-weight: bold; color: #111111; margin-bottom: 20px; }
        .title { font-size: 18px; color: #333333; margin-bottom: 10px; }
        .otp-code { font-size: 36px; font-weight: bold; color: #111111; letter-spacing: 5px; margin: 20px 0; padding: 15px; background-color: #f4f4f4; border-radius: 6px; }
        .footer { font-size: 12px; color: #888888; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">CodifyPOS.</div>
        <div class="title">Verifikasi Email Anda</div>
        <p style="color: #555;">Berikut adalah kode verifikasi Anda untuk mengaktifkan Trial 14 Hari. Kode ini akan diperbarui otomatis dalam 60 detik.</p>
        
        <div class="otp-code">{{ $otpCode }}</div>
        
        <p style="color: #555;">Jika Anda tidak mendaftar di CodifyPOS, abaikan email ini.</p>
        <div class="footer">
            &copy; {{ date('Y') }} CodifyHub. All rights reserved.
        </div>
    </div>
</body>
</html>