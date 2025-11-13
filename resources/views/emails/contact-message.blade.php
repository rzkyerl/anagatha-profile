<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Kontak Baru</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            padding: 24px;
            color: #1a1f29;
        }
        .card {
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 12px 30px rgba(31, 75, 153, 0.08);
        }
        h1 {
            font-size: 20px;
            margin-bottom: 18px;
            color: #1F4B99;
        }
        .section {
            margin-bottom: 18px;
        }
        .label {
            font-weight: 600;
            color: #3f4a5a;
        }
        p {
            margin: 6px 0 0;
            line-height: 1.6;
        }
        .footer {
            margin-top: 32px;
            font-size: 14px;
            color: #6a7385;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Pesan kontak baru</h1>
        <div class="section">
            <div class="label">Nama</div>
            <p>{{ $data['name'] }}</p>
        </div>
        <div class="section">
            <div class="label">Email</div>
            <p>{{ $data['email'] }}</p>
        </div>
        @if(!empty($data['phone']))
            <div class="section">
                <div class="label">Telepon</div>
                <p>{{ $data['phone'] }}</p>
            </div>
        @endif
        <div class="section">
            <div class="label">Pesan</div>
            <p>{{ nl2br(e($data['message'])) }}</p>
        </div>
        <div class="footer">
            Pesan ini dikirim melalui form kontak Anagata Executive.
        </div>
    </div>
</body>
</html>
