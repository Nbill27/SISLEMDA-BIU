<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pilih Role - SISLEMDA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

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

        .login-wrapper {
            width: 750px;
            height: 400px;
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }

        .login-section,
        .brand-section {
            flex: 1;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-section {
            background-color: rgba(255, 255, 255, 0.9);
            position: relative;
        }

        .brand-section {
            background: #0ca342;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .brand-section::before {
            content: '';
            position: absolute;
            background: white;
            top: 60px;
            left: 70px;
            width: 120%;
            height: 85%;
            transform: skew(-15deg);
            z-index: 0;
            border-radius: 30px 0 30px 30px;
        }

        .brand-content {
            position: relative;
            z-index: 1;
        }

        .brand-content img {
            width: 100px;
        }

        .brand-content h2 {
            margin: 8px 0 0;
            font-size: 22px;
            color: #0ca342;
        }

        .brand-content p {
            font-size: 13px;
            color: #0ca342;
        }

        form {
            max-width: 240px;
            margin: auto;
        }

        h2 {
            margin-bottom: 15px;
            text-align: center;
            font-size: 20px;
        }

        label {
            font-weight: bold;
            font-size: 13px;
        }

        .form-group select {
            width: 100%;
            padding: 8px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 13px;
        }

        .btn {
            width: 100%;
            background-color: #0ca342;
            color: white;
            padding: 8px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #0a8d38;
        }

        .alert {
            margin-bottom: 15px;
        }

        .mobile-brand {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }

        .mobile-brand img {
            width: 150px;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column-reverse;
                width: 90%;
                height: auto;
                margin: 20px 0;
            }

            .login-section {
                padding: 30px 20px;
            }

            .brand-section {
                display: none !important;
            }

            .mobile-brand {
                display: block !important;
            }

            form {
                max-width: 100%;
                width: 100%;
            }

            h2 {
                font-size: 18px;
            }

            label {
                font-size: 12px;
            }

            .form-group select {
                font-size: 13px;
                padding: 10px;
            }

            .btn {
                font-size: 13px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-section">
            <!-- Logo untuk mobile -->
            <div class="mobile-brand">
                <img src="<?= base_url('assets/img/logo_binainsani.png'); ?>" alt="Logo Bina Insani">
            </div>

            <!-- Form Pilih Role -->
            <form method="post" action="<?= base_url('auth/choose_role'); ?>">
                <h2>Pilih Role</h2>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                <?php endif; ?>

                <div class="form-group mb-3">
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['nama_role']; ?>"><?php echo ucfirst($role['nama_role']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn">Masuk</button>
            </form>
        </div>

        <div class="brand-section">
            <div class="brand-content">
                <img src="<?= base_url('assets/img/logo_binainsani.png'); ?>" alt="Logo SIMLEMDA">
                <h2>SISLEMDA</h2>
                <p>Sistem Lembar Kendali</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>