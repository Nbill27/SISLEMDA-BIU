<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lupa Password - SISLEMDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('https://images.unsplash.com/photo-1522202195463-8f46b3e5fca3') no-repeat center center / cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            backdrop-filter: blur(5px);
        }

        .card {
            width: 100%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            width: 150px;
            height: auto;
        }

        .card-title {
            font-weight: 700;
            font-size: 22px;
            color: #0ca342;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-align: left;
            display: block;
            margin-top: 10px;
        }

        .form-control {
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-success {
            background-color: #0ca342;
            border: none;
            font-weight: bold;
            font-size: 14px;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            margin-top: 15px;
        }

        .btn-success:hover {
            background-color: #0a8d38;
        }

        .alert {
            font-size: 14px;
            padding: 10px;
            margin-top: 10px;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 14px;
            color: #0ca342;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
            color: #0ca342;

        }
    </style>
</head>

<body>

    <div class="card">
        <div class="logo">
            <img src="<?= base_url('assets/img/logo_binainsani.png') ?>" alt="Logo Binsa Insani">
        </div>
        <h4 class="card-title">Lupa Password</h4>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?= form_open('auth/lupa_password_action'); ?>
        <div class="mb-3 text-start">
            <label for="username" class="form-label">Masukkan Username Anda</label>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        <button type="submit" class="btn btn-success">Lanjutkan</button>
        <?= form_close(); ?>

        <a href="<?= base_url('auth'); ?>" class="back-link">Kembali ke Login</a>
    </div>

</body>

</html>