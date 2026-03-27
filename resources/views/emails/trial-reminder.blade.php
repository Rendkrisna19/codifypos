<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Space Grotesk', Arial, sans-serif; background-color: #f8fafc; padding: 20px; }
        .box { background: #ffffff; border: 1px solid #e2e8f0; padding: 30px; border-radius: 8px; max-width: 500px; margin: 0 auto; color: #0f172a; }
        .title { font-size: 20px; font-weight: 900; text-transform: uppercase; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 20px; }
        .bold { font-weight: 900; }
    </style>
</head>
<body>
    <div class="box">
        <div class="title">CodifyPOS System</div>
        <p>Halo <span class="bold">{{ $owner->name }}</span>,</p>
        
        @if($daysLeft < 0)
            <p>Masa aktif sistem POS untuk bisnis <span class="bold">{{ $tenant->name }}</span> telah <span style="color:red; font-weight:bold;">EXPIRED</span> sejak {{ abs($daysLeft) }} hari yang lalu.</p>
            <p>Sistem Anda saat ini ditangguhkan. Harap segera melakukan perpanjangan paket agar dapat digunakan kembali.</p>
        @else
            <p>Ini adalah pengingat otomatis bahwa masa trial untuk <span class="bold">{{ $tenant->name }}</span> akan berakhir dalam <span class="bold">{{ $daysLeft }} hari</span>.</p>
            <p>Harap persiapkan perpanjangan layanan sebelum masa trial habis agar operasional kasir tidak terganggu.</p>
        @endif

        <p style="margin-top: 30px; font-size: 12px; color: #64748b;">Pesan otomatis dari CodifyPOS Administrator.</p>
    </div>
</body>
</html>