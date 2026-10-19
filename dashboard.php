<?php

// =========================================================
// SESSION
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// CEK LOGIN
// =========================================================

if (!isset($_SESSION['login'])) {

    header("Location: login.php");
    exit;
}


// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once 'koneksi.php';


// =========================================================
// DATA USER
// =========================================================

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama_lengkap'] ?? 'User';
$username = $_SESSION['username'] ?? '-';

// Mengambil huruf pertama nama
$inisial = strtoupper(substr($nama, 0, 1));


// =========================================================
// VALIDASI ROLE
// =========================================================

if (
    $role !== 'admin' &&
    $role !== 'petugas' &&
    $role !== 'owner'
) {

    echo "
        <script>
            alert('Role pengguna tidak valid.');
            window.location='logout.php';
        </script>
    ";

    exit;
}


// =========================================================
// STATISTIK DATABASE
// =========================================================


// =========================================================
// TOTAL USER
// =========================================================

$query_user = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM tb_user"
);

$total_user = mysqli_fetch_assoc($query_user)['total'];


// =========================================================
// TOTAL MEMBER
// =========================================================

$query_member = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM tb_member"
);

$total_member = mysqli_fetch_assoc($query_member)['total'];


// =========================================================
// KENDARAAN SEDANG PARKIR
// =========================================================
// Kendaraan dianggap masih parkir jika
// belum memiliki transaksi keluar.

$query_parkir = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM tb_parkir p
     WHERE NOT EXISTS (
         SELECT 1
         FROM tb_transaksi t
         WHERE t.id_parkir = p.id_parkir
     )"
);

$total_parkir = mysqli_fetch_assoc($query_parkir)['total'];


// =========================================================
// TOTAL TRANSAKSI
// =========================================================

$query_transaksi = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM tb_transaksi"
);

$total_transaksi = mysqli_fetch_assoc($query_transaksi)['total'];


// =========================================================
// TOTAL PENDAPATAN
// =========================================================

$query_pendapatan = mysqli_query(
    $koneksi,
    "SELECT COALESCE(SUM(biaya_total), 0) AS total
     FROM tb_transaksi"
);

$total_pendapatan = mysqli_fetch_assoc($query_pendapatan)['total'];


// =========================================================
// KENDARAAN KELUAR HARI INI
// =========================================================

$query_keluar_hari_ini = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM tb_transaksi
     WHERE DATE(waktu_keluar) = CURDATE()"
);

$keluar_hari_ini = mysqli_fetch_assoc($query_keluar_hari_ini)['total'];


// =========================================================
// KENDARAAN MASUK HARI INI
// =========================================================

$query_masuk_hari_ini = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM tb_parkir
     WHERE DATE(waktu_masuk) = CURDATE()"
);

$masuk_hari_ini = mysqli_fetch_assoc($query_masuk_hari_ini)['total'];


// =========================================================
// PENDAPATAN HARI INI
// =========================================================

$query_pendapatan_hari_ini = mysqli_query(
    $koneksi,
    "SELECT COALESCE(SUM(biaya_total), 0) AS total
     FROM tb_transaksi
     WHERE DATE(waktu_keluar) = CURDATE()"
);

$pendapatan_hari_ini = mysqli_fetch_assoc(
    $query_pendapatan_hari_ini
)['total'];

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Dashboard <?= ucfirst(htmlspecialchars($role)); ?>
        - Sistem Informasi Parkir
    </title>


    <style>
        /* =====================================================
           RESET
        ====================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* =====================================================
           BODY
        ====================================================== */

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f5f7;
            color: #20242b;
        }


        /* =====================================================
           SIDEBAR
        ====================================================== */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;

            width: 250px;
            height: 100vh;

            background: #20242b;
            color: white;

            padding: 22px 16px;

            overflow-y: auto;

            transition: 0.3s;
        }


        /* =====================================================
           BRAND
        ====================================================== */

        .brand {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 10px;
            padding-bottom: 20px;

            margin-bottom: 25px;

            border-bottom: 1px solid #363b43;
        }


        .brand-icon {
            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: white;
            color: #20242b;

            border-radius: 12px;

            font-size: 22px;
            font-weight: bold;
        }


        .brand strong {
            display: block;
            font-size: 18px;
        }


        .brand small {
            display: block;

            color: #9ca3af;

            font-size: 12px;

            margin-top: 4px;
        }


        /* =====================================================
           PROFIL
        ====================================================== */

        .sidebar-profile {
            display: flex;
            align-items: center;

            gap: 12px;

            background: #2b3038;

            padding: 13px;

            border-radius: 12px;

            margin-bottom: 25px;
        }


        .profile-avatar {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: white;
            color: #20242b;

            border-radius: 50%;

            font-weight: bold;
        }


        .sidebar-profile strong {
            display: block;

            font-size: 14px;

            margin-bottom: 4px;
        }


        .sidebar-profile span {
            color: #aeb4bd;

            font-size: 12px;
        }


        /* =====================================================
           MENU
        ====================================================== */

        .menu-title {
            color: #858c97;

            font-size: 11px;

            font-weight: bold;

            letter-spacing: 1px;

            margin: 22px 10px 9px;

            text-transform: uppercase;
        }


        .menu-item {
            display: flex;
            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 12px 13px;

            margin-bottom: 5px;

            border-radius: 9px;

            color: #cdd1d7;

            text-decoration: none;

            font-size: 14px;

            transition: 0.2s;
        }


        .menu-item span {
            width: 24px;

            text-align: center;

            font-size: 16px;
        }


        .menu-item:hover {
            background: #343a43;

            color: white;

            transform: translateX(3px);
        }


        .menu-item.active {
            background: white;

            color: #20242b;

            font-weight: bold;
        }


        /* =====================================================
           LOGOUT
        ====================================================== */

        .sidebar-bottom {
            margin-top: 25px;

            padding-top: 15px;

            border-top: 1px solid #363b43;
        }


        .logout-menu {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 12px 13px;

            border-radius: 9px;

            color: #f1a6a6;

            text-decoration: none;

            font-size: 14px;
        }


        .logout-menu:hover {
            background: #642f2f;

            color: white;
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main-content {
            margin-left: 250px;

            min-height: 100vh;

            padding: 30px 35px;
        }


        /* =====================================================
           TOPBAR
        ====================================================== */

        .topbar {
            display: flex;
            align-items: center;

            justify-content: space-between;

            margin-bottom: 25px;
        }


        .breadcrumb {
            display: block;

            color: #8a919c;

            font-size: 13px;

            margin-bottom: 5px;
        }


        .topbar h1 {
            font-size: 28px;
        }


        .top-user {
            display: flex;
            align-items: center;

            gap: 10px;

            background: white;

            padding: 8px 13px;

            border-radius: 12px;

            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }


        .top-avatar {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #20242b;

            color: white;

            border-radius: 50%;

            font-weight: bold;
        }


        .top-user strong {
            display: block;

            font-size: 13px;
        }


        .top-user span {
            display: block;

            color: #8a919c;

            font-size: 11px;

            margin-top: 3px;
        }


        /* =====================================================
           WELCOME
        ====================================================== */

        .welcome-card {
            display: flex;
            align-items: center;

            justify-content: space-between;

            background: white;

            padding: 30px;

            border-radius: 18px;

            margin-bottom: 25px;

            border: 1px solid #e8eaed;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }


        .welcome-label {
            display: block;

            color: #8a919c;

            font-size: 11px;

            font-weight: bold;

            letter-spacing: 1.5px;

            margin-bottom: 9px;
        }


        .welcome-card h2 {
            font-size: 25px;

            margin-bottom: 10px;
        }


        .welcome-card p {
            color: #6b7280;

            font-size: 14px;
        }


        .welcome-icon {
            width: 75px;
            height: 75px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f0f1f3;

            border-radius: 20px;

            font-size: 35px;
        }


        /* =====================================================
           STATISTIK
        ====================================================== */

        .stats {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 18px;

            margin-bottom: 28px;
        }


        .stat-card {
            background: white;

            padding: 22px;

            border-radius: 15px;

            border: 1px solid #e8eaed;

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);

            transition: 0.2s;
        }


        .stat-card:hover {
            transform: translateY(-4px);

            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }


        .stat-icon {
            font-size: 25px;

            margin-bottom: 15px;
        }


        .stat-card span {
            display: block;

            color: #8a919c;

            font-size: 12px;

            margin-bottom: 6px;
        }


        .stat-card strong {
            display: block;

            font-size: 23px;
        }


        /* =====================================================
           CONTENT CARD
        ====================================================== */

        .content-card {
            background: white;

            padding: 25px;

            border-radius: 17px;

            border: 1px solid #e8eaed;

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }


        .section-heading {
            margin-bottom: 20px;
        }


        .section-heading h2 {
            font-size: 20px;
        }


        .section-label {
            display: block;

            color: #8a919c;

            font-size: 10px;

            font-weight: bold;

            letter-spacing: 1.5px;

            margin-bottom: 5px;
        }


        /* =====================================================
           QUICK MENU
        ====================================================== */

        .quick-menu {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 15px;
        }


        .quick-card {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 17px;

            background: #f8f9fa;

            border: 1px solid #e9ebee;

            border-radius: 12px;

            text-decoration: none;

            color: #20242b;

            transition: 0.2s;
        }


        .quick-card:hover {
            background: #20242b;

            color: white;

            transform: translateY(-4px);
        }


        .quick-icon {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: white;

            border-radius: 10px;

            font-size: 19px;
        }


        .quick-card div:nth-child(2) {
            flex: 1;
        }


        .quick-card strong {
            display: block;

            font-size: 13px;

            margin-bottom: 4px;
        }


        .quick-card span {
            display: block;

            color: #8a919c;

            font-size: 11px;
        }


        .quick-card b {
            font-size: 18px;

            color: #8a919c;
        }


        /* =====================================================
           WARNA ADMIN - MERAH
        ====================================================== */

        .role-admin .sidebar {
            background: #7f1d1d;
        }

        .role-admin .brand {
            border-bottom-color: #991b1b;
        }

        .role-admin .brand-icon {
            color: #7f1d1d;
        }

        .role-admin .sidebar-profile {
            background: #991b1b;
        }

        .role-admin .menu-item:hover {
            background: #991b1b;
        }

        .role-admin .menu-item.active {
            background: #fecaca;
            color: #7f1d1d;
        }

        .role-admin .top-avatar {
            background: #991b1b;
        }

        .role-admin .welcome-icon {
            background: #fee2e2;
        }

        .role-admin .stat-icon {
            color: #991b1b;
        }

        .role-admin .quick-card:hover {
            background: #7f1d1d;
        }

        .role-admin .quick-icon {
            color: #7f1d1d;
        }

        .role-admin .section-label {
            color: #991b1b;
        }


        /* =====================================================
           WARNA PETUGAS - HIJAU
        ====================================================== */

        .role-petugas .sidebar {
            background: #14532d;
        }

        .role-petugas .brand {
            border-bottom-color: #166534;
        }

        .role-petugas .brand-icon {
            color: #14532d;
        }

        .role-petugas .sidebar-profile {
            background: #166534;
        }

        .role-petugas .menu-item:hover {
            background: #166534;
        }

        .role-petugas .menu-item.active {
            background: #bbf7d0;
            color: #14532d;
        }

        .role-petugas .top-avatar {
            background: #166534;
        }

        .role-petugas .welcome-icon {
            background: #dcfce7;
        }

        .role-petugas .stat-icon {
            color: #166534;
        }

        .role-petugas .quick-card:hover {
            background: #14532d;
        }

        .role-petugas .quick-icon {
            color: #166534;
        }

        .role-petugas .section-label {
            color: #166534;
        }


        /* =====================================================
           WARNA OWNER - BIRU
        ====================================================== */

        .role-owner .sidebar {
            background: #1e3a8a;
        }

        .role-owner .brand {
            border-bottom-color: #2563eb;
        }

        .role-owner .brand-icon {
            color: #1e3a8a;
        }

        .role-owner .sidebar-profile {
            background: #2563eb;
        }

        .role-owner .menu-item:hover {
            background: #2563eb;
        }

        .role-owner .menu-item.active {
            background: #bfdbfe;
            color: #1e3a8a;
        }

        .role-owner .top-avatar {
            background: #2563eb;
        }

        .role-owner .welcome-icon {
            background: #dbeafe;
        }

        .role-owner .stat-icon {
            color: #2563eb;
        }

        .role-owner .quick-card:hover {
            background: #1e3a8a;
        }

        .role-owner .quick-icon {
            color: #2563eb;
        }

        .role-owner .section-label {
            color: #2563eb;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 1100px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .quick-menu {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media (max-width: 700px) {

            .sidebar {
                position: relative;

                width: 100%;

                height: auto;
            }

            .main-content {
                margin-left: 0;

                padding: 20px;
            }

            .topbar {
                flex-direction: column;

                align-items: flex-start;

                gap: 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .quick-menu {
                grid-template-columns: 1fr;
            }

            .welcome-icon {
                display: none;
            }

        }
    </style>

</head>


<!-- =====================================================
     CLASS ROLE MENENTUKAN WARNA DASHBOARD
====================================================== -->

<body class="role-<?= htmlspecialchars($role); ?>">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">


        <!-- BRAND -->

        <div class="brand">

            <div class="brand-icon">
                P
            </div>

            <div>

                <strong>PARKIR</strong>

                <small>
                    Sistem Informasi
                </small>

            </div>

        </div>


        <!-- PROFIL -->

        <div class="sidebar-profile">

            <div class="profile-avatar">
                <?= htmlspecialchars($inisial); ?>
            </div>

            <div>

                <strong>
                    <?= htmlspecialchars($nama); ?>
                </strong>

                <span>
                    <?= ucfirst(htmlspecialchars($role)); ?>
                </span>

            </div>

        </div>


        <!-- =================================================
             MENU UTAMA
        ================================================== -->

        <nav>

            <p class="menu-title">
                MENU UTAMA
            </p>


            <a href="dashboard.php" class="menu-item active">
                <span>⌂</span>
                Dashboard
            </a>


            <!-- =================================================
                 MENU ADMIN
            ================================================== -->

            <?php if ($role == 'admin') { ?>

                <p class="menu-title">
                    ADMINISTRASI
                </p>


                <!-- DATA USER -->

                <a href="user/tampil_user.php" class="menu-item">
                    <span>👤</span>
                    Data User
                </a>


                <!-- DATA MEMBER -->

                <a href="member/tampil_member.php" class="menu-item">
                    <span>👥</span>
                    Data Member
                </a>


                <!-- AREA PARKIR -->

                <a href="area/tampil_area_parkir.php" class="menu-item">
                    <span>▦</span>
                    Area Parkir
                </a>


                <!-- DATA TARIF -->

                <a href="tarif/tampil_tarif.php" class="menu-item">
                    <span>Rp</span>
                    Data Tarif
                </a>


                <p class="menu-title">
                    PARKIR
                </p>


                <!-- DATA PARKIR -->

                <a href="parkir/tampil_parkir.php" class="menu-item">
                    <span>🚗</span>
                    Data Parkir
                </a>


                <!-- TRANSAKSI -->

                <a href="transaksi/tampil_transaksi.php" class="menu-item">
                    <span>▤</span>
                    Transaksi
                </a>


                <!-- HISTORI TRANSAKSI -->

                <a href="transaksi/tampil_transaksi.php" class="menu-item">
                    <span>◷</span>
                    Histori Transaksi
                </a>


                <!-- LOG AKTIVITAS -->

                <a href="log_aktivitas/tampil_log_aktivitas.php" class="menu-item">
                    <span>◉</span>
                    Log Aktivitas
                </a>

            <?php } ?>


            <!-- =================================================
                 MENU PETUGAS
                 HANYA DATA PARKIR DAN TRANSAKSI
            ================================================== -->

            <?php if ($role == 'petugas') { ?>

                <p class="menu-title">
                    OPERASIONAL
                </p>


                <!-- DATA PARKIR -->

                <a href="parkir/tampil_parkir.php" class="menu-item">
                    <span>🚗</span>
                    Data Parkir
                </a>


                <!-- TRANSAKSI -->

                <a href="transaksi/tampil_transaksi.php" class="menu-item">
                    <span>▤</span>
                    Transaksi
                </a>

            <?php } ?>


            <!-- =================================================
                 MENU OWNER
            ================================================== -->

            <?php if ($role == 'owner') { ?>

                <p class="menu-title">
                    INFORMASI
                </p>


                <!-- TRANSAKSI -->

                <a href="transaksi/tampil_transaksi.php" class="menu-item">
                    <span>▤</span>
                    Transaksi
                </a>


                <!-- HISTORI TRANSAKSI -->

                <a href="transaksi/tampil_transaksi.php" class="menu-item">
                    <span>◷</span>
                    Histori Transaksi
                </a>


                <!-- LOG AKTIVITAS -->

                <a href="log_aktivitas/tampil_log_aktivitas.php" class="menu-item">
                    <span>◉</span>
                    Log Aktivitas
                </a>

            <?php } ?>

        </nav>


        <!-- =================================================
             LOGOUT
        ================================================== -->

        <div class="sidebar-bottom">

            <a href="logout.php" class="logout-menu" onclick="return confirm('Yakin ingin logout?')">
                <span>↪</span>
                Logout
            </a>

        </div>

    </aside>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content">


        <!-- TOPBAR -->

        <header class="topbar">

            <div>

                <span class="breadcrumb">
                    Sistem Informasi Parkir
                </span>

                <h1>
                    Dashboard <?= ucfirst(htmlspecialchars($role)); ?>
                </h1>

            </div>


            <div class="top-user">

                <div class="top-avatar">
                    <?= htmlspecialchars($inisial); ?>
                </div>

                <div>

                    <strong>
                        <?= htmlspecialchars($nama); ?>
                    </strong>

                    <span>
                        <?= ucfirst(htmlspecialchars($role)); ?>
                    </span>

                </div>

            </div>

        </header>


        <!-- =====================================================
             WELCOME
        ====================================================== -->

        <section class="welcome-card">

            <div>

                <span class="welcome-label">
                    DASHBOARD <?= strtoupper(htmlspecialchars($role)); ?>
                </span>


                <?php if ($role == 'admin') { ?>

                    <h2>
                        Selamat datang, Admin 👋
                    </h2>

                    <p>
                        Kelola pengguna, member, area, tarif,
                        parkir, transaksi dan aktivitas sistem.
                    </p>


                <?php } elseif ($role == 'petugas') { ?>

                    <h2>
                        Selamat datang, Petugas 👋
                    </h2>

                    <p>
                        Kelola kendaraan masuk, kendaraan keluar
                        dan transaksi parkir.
                    </p>


                <?php } elseif ($role == 'owner') { ?>

                    <h2>
                        Selamat datang, Owner 👋
                    </h2>

                    <p>
                        Pantau transaksi, histori parkir
                        dan pendapatan sistem.
                    </p>

                <?php } ?>

            </div>


            <div class="welcome-icon">

                <?php

                if ($role == 'admin') {

                    echo "⚙️";

                } elseif ($role == 'petugas') {

                    echo "🚗";

                } else {

                    echo "📊";

                }

                ?>

            </div>

        </section>


        <!-- =====================================================
             DASHBOARD ADMIN
        ====================================================== -->

        <?php if ($role == 'admin') { ?>

            <section class="stats">


                <!-- TOTAL USER -->

                <div class="stat-card">

                    <div class="stat-icon">
                        👤
                    </div>

                    <span>
                        TOTAL USER
                    </span>

                    <strong>
                        <?= $total_user; ?>
                    </strong>

                </div>


                <!-- TOTAL MEMBER -->

                <div class="stat-card">

                    <div class="stat-icon">
                        👥
                    </div>

                    <span>
                        TOTAL MEMBER
                    </span>

                    <strong>
                        <?= $total_member; ?>
                    </strong>

                </div>


                <!-- SEDANG PARKIR -->

                <div class="stat-card">

                    <div class="stat-icon">
                        🚗
                    </div>

                    <span>
                        SEDANG PARKIR
                    </span>

                    <strong>
                        <?= $total_parkir; ?>
                    </strong>

                </div>


                <!-- TOTAL TRANSAKSI -->

                <div class="stat-card">

                    <div class="stat-icon">
                        ▤
                    </div>

                    <span>
                        TOTAL TRANSAKSI
                    </span>

                    <strong>
                        <?= $total_transaksi; ?>
                    </strong>

                </div>

            </section>


            <section class="content-card">

                <div class="section-heading">

                    <span class="section-label">
                        ADMINISTRASI
                    </span>

                    <h2>
                        Akses Cepat Admin
                    </h2>

                </div>


                <div class="quick-menu">


                    <!-- USER -->

                    <a href="user/tampil_user.php" class="quick-card">

                        <div class="quick-icon">
                            👤
                        </div>

                        <div>

                            <strong>
                                Data User
                            </strong>

                            <span>
                                Kelola pengguna
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- MEMBER -->

                    <a href="member/tampil_member.php" class="quick-card">

                        <div class="quick-icon">
                            👥
                        </div>

                        <div>

                            <strong>
                                Data Member
                            </strong>

                            <span>
                                Kelola member
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- AREA -->

                    <a href="area/tampil_area_parkir.php" class="quick-card">

                        <div class="quick-icon">
                            ▦
                        </div>

                        <div>

                            <strong>
                                Area Parkir
                            </strong>

                            <span>
                                Kelola area
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- TARIF -->

                    <a href="tarif/tampil_tarif.php" class="quick-card">

                        <div class="quick-icon">
                            Rp
                        </div>

                        <div>

                            <strong>
                                Data Tarif
                            </strong>

                            <span>
                                Kelola tarif
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- PARKIR -->

                    <a href="parkir/tampil_parkir.php" class="quick-card">

                        <div class="quick-icon">
                            🚗
                        </div>

                        <div>

                            <strong>
                                Data Parkir
                            </strong>

                            <span>
                                Kelola kendaraan
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>

                </div>

            </section>

        <?php } ?>


        <!-- =====================================================
             DASHBOARD PETUGAS
        ====================================================== -->

        <?php if ($role == 'petugas') { ?>

            <section class="stats">


                <!-- SEDANG PARKIR -->

                <div class="stat-card">

                    <div class="stat-icon">
                        🚗
                    </div>

                    <span>
                        SEDANG PARKIR
                    </span>

                    <strong>
                        <?= $total_parkir; ?>
                    </strong>

                </div>


                <!-- MASUK HARI INI -->

                <div class="stat-card">

                    <div class="stat-icon">
                        📥
                    </div>

                    <span>
                        MASUK HARI INI
                    </span>

                    <strong>
                        <?= $masuk_hari_ini; ?>
                    </strong>

                </div>


                <!-- KELUAR HARI INI -->

                <div class="stat-card">

                    <div class="stat-icon">
                        📤
                    </div>

                    <span>
                        KELUAR HARI INI
                    </span>

                    <strong>
                        <?= $keluar_hari_ini; ?>
                    </strong>

                </div>


                <!-- TRANSAKSI -->

                <div class="stat-card">

                    <div class="stat-icon">
                        ▤
                    </div>

                    <span>
                        TRANSAKSI
                    </span>

                    <strong>
                        <?= $total_transaksi; ?>
                    </strong>

                </div>

            </section>


            <section class="content-card">

                <div class="section-heading">

                    <span class="section-label">
                        OPERASIONAL
                    </span>

                    <h2>
                        Akses Cepat Petugas
                    </h2>

                </div>


                <div class="quick-menu">


                    <!-- DATA PARKIR -->

                    <a href="parkir/tampil_parkir.php" class="quick-card">

                        <div class="quick-icon">
                            🚗
                        </div>

                        <div>

                            <strong>
                                Data Parkir
                            </strong>

                            <span>
                                Kendaraan masuk
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- TRANSAKSI -->

                    <a href="transaksi/tampil_transaksi.php" class="quick-card">

                        <div class="quick-icon">
                            ▤
                        </div>

                        <div>

                            <strong>
                                Transaksi
                            </strong>

                            <span>
                                Data transaksi
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>

                </div>

            </section>

        <?php } ?>


        <!-- =====================================================
             DASHBOARD OWNER
        ====================================================== -->

        <?php if ($role == 'owner') { ?>

            <section class="stats">


                <!-- TOTAL TRANSAKSI -->

                <div class="stat-card">

                    <div class="stat-icon">
                        ▤
                    </div>

                    <span>
                        TOTAL TRANSAKSI
                    </span>

                    <strong>
                        <?= $total_transaksi; ?>
                    </strong>

                </div>


                <!-- TOTAL PENDAPATAN -->

                <div class="stat-card">

                    <div class="stat-icon">
                        💰
                    </div>

                    <span>
                        TOTAL PENDAPATAN
                    </span>

                    <strong>
                        Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>
                    </strong>

                </div>


                <!-- MASUK HARI INI -->

                <div class="stat-card">

                    <div class="stat-icon">
                        📥
                    </div>

                    <span>
                        MASUK HARI INI
                    </span>

                    <strong>
                        <?= $masuk_hari_ini; ?>
                    </strong>

                </div>


                <!-- KELUAR HARI INI -->

                <div class="stat-card">

                    <div class="stat-icon">
                        📤
                    </div>

                    <span>
                        KELUAR HARI INI
                    </span>

                    <strong>
                        <?= $keluar_hari_ini; ?>
                    </strong>

                </div>

            </section>


            <section class="content-card">

                <div class="section-heading">

                    <span class="section-label">
                        LAPORAN
                    </span>

                    <h2>
                        Informasi Pendapatan
                    </h2>

                </div>


                <div class="quick-menu">


                    <!-- TRANSAKSI -->

                    <a href="transaksi/tampil_transaksi.php" class="quick-card">

                        <div class="quick-icon">
                            ▤
                        </div>

                        <div>

                            <strong>
                                Transaksi
                            </strong>

                            <span>
                                Lihat transaksi
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- HISTORI -->

                    <a href="transaksi/tampil_transaksi.php" class="quick-card">

                        <div class="quick-icon">
                            ◷
                        </div>

                        <div>

                            <strong>
                                Histori Transaksi
                            </strong>

                            <span>
                                Riwayat transaksi parkir
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- LOG AKTIVITAS -->

                    <a href="log_aktivitas/tampil_log_aktivitas.php" class="quick-card">

                        <div class="quick-icon">
                            ◉
                        </div>

                        <div>

                            <strong>
                                Log Aktivitas
                            </strong>

                            <span>
                                Aktivitas sistem
                            </span>

                        </div>

                        <b>
                            →
                        </b>

                    </a>


                    <!-- PENDAPATAN HARI INI -->

                    <div class="quick-card">

                        <div class="quick-icon">
                            💰
                        </div>

                        <div>

                            <strong>
                                Pendapatan Hari Ini
                            </strong>

                            <span>
                                Total pemasukan
                            </span>

                        </div>

                        <b>
                            Rp <?= number_format($pendapatan_hari_ini, 0, ',', '.'); ?>
                        </b>

                    </div>

                </div>

            </section>

        <?php } ?>


    </main>


</body>

</html>