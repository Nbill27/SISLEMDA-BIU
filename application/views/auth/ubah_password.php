<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ubah Password - SISLEMDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('https://images.unsplash.com/photo-1522202195463-8f46b3e5fca3') no-repeat center center / cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        .card {
            width: 100%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.95);
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

        .form-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 40px;
            cursor: pointer;
            color: #888;
            font-size: 18px;
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

        <h4 class="card-title">Ubah Password</h4>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <?= form_open('auth/ubah_password_action'); ?>
        <input type="hidden" name="username" value="<?= $username; ?>">

        <div class="mb-3 form-group text-start">
            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="new_password" name="password" required minlength="6">
            <i class="bi bi-eye-slash toggle-password" onclick="togglePassword('new_password', this)"></i>
        </div>

        <div class="mb-3 form-group text-start">
            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
            <i class="bi bi-eye-slash toggle-password" onclick="togglePassword('confirm_password', this)"></i>
        </div>

        <button type="submit" class="btn btn-success">Simpan Password Baru</button>
        <?= form_close(); ?>

        <a href="<?= base_url('auth'); ?>" class="back-link">Kembali ke Login</a>
    </div>

    <script>
        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }
    </script>

</body>

</html>