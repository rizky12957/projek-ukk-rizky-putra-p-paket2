<?php

// Memulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengambil pesan error login jika ada
$error = $_SESSION['error_login'] ?? '';

// Setelah diambil, hapus pesan agar tidak muncul terus
unset($_SESSION['error_login']);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Informasi Parkir</title>


    <!-- =====================================================
         CSS LANGSUNG DI DALAM LOGIN
         Tidak membutuhkan style.css
    ====================================================== -->

    <style>
        /* ================================
           RESET
        ================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* ================================
           BODY
        ================================= */

        body {
            font-family: Arial, Helvetica, sans-serif;

            min-height: 100vh;

            background: #f1f3f5;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 20px;
        }


        /* ================================
           CONTAINER LOGIN
        ================================= */

        .login-container {

            width: 950px;

            max-width: 100%;

            min-height: 560px;

            display: grid;

            grid-template-columns: 1fr 1fr;

            background: white;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.12);
        }


        /* ================================
           BAGIAN KIRI
        ================================= */

        .login-left {

            background: #20242b;

            color: white;

            padding: 55px;

            display: flex;

            flex-direction: column;

            justify-content: center;
        }


        /* Logo */

        .logo {

            display: flex;

            align-items: center;

            gap: 14px;

            margin-bottom: 45px;
        }


        .logo-icon {

            width: 55px;

            height: 55px;

            background: white;

            color: #20242b;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 15px;

            font-size: 25px;

            font-weight: bold;
        }


        .logo-text strong {

            display: block;

            font-size: 21px;

            letter-spacing: 1px;
        }


        .logo-text span {

            display: block;

            color: #aeb4bd;

            font-size: 12px;

            margin-top: 5px;
        }


        /* Judul */

        .login-left h1 {

            font-size: 38px;

            line-height: 1.2;

            margin-bottom: 18px;
        }


        .login-left>p {

            color: #c5c9cf;

            line-height: 1.7;

            font-size: 14px;

            margin-bottom: 30px;
        }


        /* List fitur */

        .features {

            list-style: none;
        }


        .features li {

            color: #e0e3e7;

            margin-bottom: 13px;

            font-size: 14px;
        }


        .features li::before {

            content: "✓";

            display: inline-flex;

            align-items: center;

            justify-content: center;

            width: 22px;

            height: 22px;

            margin-right: 10px;

            background: #343a43;

            border-radius: 50%;

            font-size: 12px;
        }


        /* ================================
           BAGIAN KANAN
        ================================= */

        .login-right {

            padding: 55px;

            display: flex;

            flex-direction: column;

            justify-content: center;
        }


        .login-right h2 {

            font-size: 28px;

            color: #20242b;

            margin-bottom: 10px;
        }


        .login-description {

            color: #7b828c;

            font-size: 14px;

            margin-bottom: 30px;

            line-height: 1.6;
        }


        /* ================================
           ERROR
        ================================= */

        .error-box {

            background: #fff1f1;

            border: 1px solid #f0b5b5;

            color: #a52a2a;

            padding: 12px 15px;

            border-radius: 9px;

            margin-bottom: 20px;

            font-size: 13px;
        }


        /* ================================
           FORM
        ================================= */

        .form-group {

            margin-bottom: 18px;
        }


        .form-group label {

            display: block;

            font-size: 13px;

            font-weight: bold;

            color: #343a43;

            margin-bottom: 8px;
        }


        .form-group input {

            width: 100%;

            height: 48px;

            padding: 0 15px;

            border: 1px solid #d9dde2;

            border-radius: 10px;

            outline: none;

            font-size: 14px;

            color: #20242b;

            transition: 0.2s;

            background: #fafafa;
        }


        .form-group input:focus {

            border-color: #20242b;

            background: white;

            box-shadow:
                0 0 0 3px rgba(32, 36, 43, 0.08);
        }


        /* ================================
           TOMBOL LOGIN
        ================================= */

        .login-button {

            width: 100%;

            height: 48px;

            border: none;

            border-radius: 10px;

            background: #20242b;

            color: white;

            font-size: 14px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;

            margin-top: 5px;
        }


        .login-button:hover {

            background: #343a43;

            transform: translateY(-2px);

            box-shadow:
                0 7px 15px rgba(0, 0, 0, 0.15);
        }


        /* ================================
           FOOTER LOGIN
        ================================= */

        .login-footer {

            margin-top: 30px;

            text-align: center;

            color: #9aa0a8;

            font-size: 12px;
        }


        /* ================================
           RESPONSIVE
        ================================= */

        @media (max-width: 750px) {

            .login-container {

                grid-template-columns: 1fr;

            }

            .login-left {

                padding: 35px;

            }

            .login-left h1 {

                font-size: 30px;

            }

            .login-left {

                min-height: 300px;

            }

            .login-right {

                padding: 35px;

            }

        }


        @media (max-width: 450px) {

            body {

                padding: 10px;

            }

            .login-left,
            .login-right {

                padding: 25px;

            }

        }
    </style>

</head>


<body>


    <!-- =====================================================
     CONTAINER LOGIN
====================================================== -->

    <div class="login-container">


        <!-- =================================================
         BAGIAN KIRI
    ================================================== -->

        <div class="login-left">


            <!-- LOGO -->

            <div class="logo">

                <div class="logo-icon">
                    P
                </div>

                <div class="logo-text">

                    <strong>
                        PARKIR
                    </strong>

                    <span>
                        Sistem Informasi
                    </span>

                </div>

            </div>


            <!-- JUDUL -->

            <h1>
                Sistem Parkir
            </h1>


            <p>
                Kelola kendaraan, transaksi parkir,
                member, tarif, dan aktivitas
                dengan lebih mudah.
            </p>


            <!-- FITUR -->

            <ul class="features">

                <li>
                    Pengelolaan parkir
                </li>

                <li>
                    Pencatatan transaksi
                </li>

                <li>
                    Laporan aktivitas
                </li>

            </ul>


        </div>



        <!-- =================================================
         BAGIAN KANAN
    ================================================== -->

        <div class="login-right">


            <h2>
                Login ke Sistem
            </h2>


            <p class="login-description">
                Masukkan username dan password kamu
                untuk melanjutkan.
            </p>


            <!-- PESAN ERROR -->

            <?php if (!empty($error)) { ?>

                <div class="error-box">

                    <?= htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <!-- FORM LOGIN -->

            <form action="proses_login.php" method="POST">


                <!-- USERNAME -->

                <div class="form-group">

                    <label>
                        Username
                    </label>

                    <input type="text" name="username" placeholder="Masukkan username" required autocomplete="username">

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label>
                        Password
                    </label>

                    <input type="password" name="password" placeholder="Masukkan password" required
                        autocomplete="current-password">

                </div>


                <!-- TOMBOL -->

                <button type="submit" name="login" class="login-button">
                    Masuk ke Sistem →
                </button>


            </form>


            <!-- FOOTER -->

            <div class="login-footer">

                Sistem Informasi Parkir

            </div>


        </div>


    </div>


</body>

</html>