<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Verifikasi Gagal' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .error-container {
            background: white;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .icon {
            font-size: 70px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        p {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="icon">⚠️</div>
        <h2><?= $title ?? 'Verifikasi Gagal' ?></h2>
        <p><?= $message ?? 'Terjadi kesalahan saat memproses verifikasi QR Code.' ?></p>
        
        <a href="<?php echo base_url();?>" class="btn">
            ← Kembali ke Beranda
        </a>
    </div>

</body>
</html>