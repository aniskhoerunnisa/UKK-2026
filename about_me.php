
<?php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>About Me - Sistem Pelanggaran Siswa</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f5f7ff;
    font-family: 'Segoe UI', sans-serif;
    color: #26324b;
}

.sidebar {
    min-height: 100vh;
    background: #202b46;
    padding: 25px 15px !important;
}

.brand {
    color: white;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 35px;
}

.brand span {
    color: #91a7ff;
}

.sidebar .nav-link {
    color: #c4cce0;
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 7px;
    transition: .2s;
}

.sidebar .nav-link i {
    width: 25px;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background: #5268e8;
    color: white;
}

main {
    padding: 30px !important;
    min-width: 0;
}

.top-title {
    margin-bottom: 25px;
}

.top-title h2 {
    font-weight: 750;
    color: #202b46;
}

.top-title p {
    color: #8490a8;
}

.hero {
    position: relative;
    overflow: hidden;
    border-radius: 22px;
    padding: 38px;
    color: white;
    background: linear-gradient(120deg, #5268e8, #8264e8);
    box-shadow: 0 12px 30px rgba(82,104,232,.18);
}

.hero:after {
    content: "";
    position: absolute;
    width: 220px;
    height: 220px;
    border: 35px solid rgba(255,255,255,.09);
    border-radius: 50%;
    right: -55px;
    top: -75px;
    pointer-events: none;
}

.hero h1 {
    position: relative;
    font-weight: 800;
    font-size: 34px;
}

.hero p {
    position: relative;
    max-width: 550px;
    line-height: 1.8;
    color: #f0efff;
}

.hero-label {
    position: relative;
    display: inline-block;
    background: rgba(255,255,255,.17);
    border-radius: 30px;
    padding: 7px 14px;
    font-size: 12px;
    margin-bottom: 15px;
}

.section-card {
    background: white;
    border-radius: 18px;
    padding: 25px;
    height: 100%;
    min-width: 0;
    border: 1px solid #edf0f8;
    box-shadow: 0 6px 20px rgba(35,48,85,.04);
    overflow-wrap: anywhere;
}

.section-card h5 {
    font-weight: 750;
    margin-bottom: 15px;
}

.section-card p {
    color: #7b879e;
    line-height: 1.8;
    margin-bottom: 0;
}

.profile-avatar {
    width: 85px;
    height: 85px;
    border-radius: 24px;
    background: linear-gradient(135deg,#5268e8,#a18aff);
    color: white;
    font-size: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
}

.profile-name {
    font-size: 23px;
    font-weight: 750;
}

.profile-role {
    color: #7d8aa4;
    font-size: 14px;
}

.info-row {
    padding: 13px 0;
    border-bottom: 1px solid #eef0f6;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #8a95aa;
    font-size: 12px;
    margin-bottom: 4px;
}

.info-value {
    font-weight: 600;
    overflow-wrap: anywhere;
}

.mini-icon {
    display: inline-flex;
    width: 40px;
    height: 40px;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #eef0ff;
    color: #596be8;
    margin-right: 10px;
    flex-shrink: 0;
}

.skill-tag {
    display: inline-block;
    padding: 9px 13px;
    background: #f0f2ff;
    color: #596be8;
    border-radius: 9px;
    margin: 4px;
    font-size: 13px;
    font-weight: 600;
}

.quote-box {
    background: #202b46;
    color: white;
    border-radius: 18px;
    padding: 27px;
    height: 100%;
    min-width: 0;
    overflow-wrap: anywhere;
}

.quote-box h5 {
    line-height: 1.5;
}

.quote-box p {
    color: #dce2f5;
    line-height: 1.8;
}

.footer {
    text-align: center;
    color: #9aa4b8;
    padding-top: 12px;
    padding-bottom: 10px;
    font-size: 13px;
}

/* Perbaikan layout agar card tidak bertabrakan */
.container-fluid > .row {
    align-items: stretch;
}

main > .row {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
}

main > .row > [class*="col-"] {
    display: flex;
    flex-direction: column;
    min-width: 0;
    margin-bottom: 20px;
}

main > .row > [class*="col-"] > .section-card,
main > .row > [class*="col-"] > .quote-box {
    width: 100%;
    height: 100%;
    flex: 1 1 auto;
}

@media (max-width: 767px) {
    main {
        padding: 18px !important;
    }

    .sidebar {
        min-height: auto;
    }

    .hero {
        padding: 25px;
    }

    .hero h1 {
        font-size: 27px;
    }

    main > .row > [class*="col-"] {
        margin-bottom: 16px;
    }

    .section-card,
    .quote-box {
        height: auto;
    }
}
</style>
</head>

<body>
<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<aside class="col-md-3 col-lg-2 sidebar">
    <div class="brand">
        <i class="fas fa-layer-group"></i>
        My<span>Website.</span>
    </div>

    <p class="text-uppercase small text-secondary mb-3">Menu Utama</p>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="kelola_siswa.php" class="nav-link">
                <i class="fas fa-user-graduate"></i> Kelola Siswa
            </a>
        </li>
        <li class="nav-item">
            <a href="kelola_guru.php" class="nav-link">
                <i class="fas fa-chalkboard-teacher"></i> Kelola Guru
            </a>
        </li>
        <li class="nav-item">
            <a href="kelola_wali_kelas.php" class="nav-link">
                <i class="fas fa-user-tie"></i> Wali Kelas
            </a>
        </li>
        <li class="nav-item">
            <a href="kelola_kelas.php" class="nav-link">
                <i class="fas fa-school"></i> Kelola Kelas
            </a>
        </li>
        <li class="nav-item">
            <a href="kelola_tahun_ajaran.php" class="nav-link">
                <i class="fas fa-calendar-alt"></i> Tahun Ajaran
            </a>
        </li>
        <li class="nav-item">
            <a href="about_me.php" class="nav-link active">
                <i class="fas fa-user-circle"></i> About Me
            </a>
        </li>
    </ul>

    <hr style="border-color:#3c4761;">

    <a href="logout.php" class="nav-link text-danger">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</aside>

<!-- KONTEN UTAMA -->
<main class="col-md-9 col-lg-10">

    <div class="top-title">
        <h2>About Me <span style="color:#6575e8;">.</span></h2>
        <p>Kenalan lebih dekat dengan orang di balik project ini ✨</p>
    </div>

    <!-- HERO -->
    <section class="hero mb-4">
        <span class="hero-label">
            <i class="fas fa-heart"></i> WELCOME TO MY PAGE
        </span>

        <h1>Hai, kenalin aku anis khoerunnisa 👋</h1>

        <p>
            Selamat datang di halaman kecil tentang aku.
            Aku masih terus belajar, mencoba hal baru, dan
            mengembangkan kemampuan di dunia teknologi.
            Buat aku, setiap proses punya cerita dan setiap
            tantangan adalah kesempatan buat jadi lebih baik.
        </p>

        <span class="font-weight-bold">
            Keep learning, keep growing! 🚀
        </span>
    </section>

    <div class="row">

        <!-- PROFIL -->
        <div class="col-lg-5 mb-4">
            <div class="section-card">
                <div class="profile-avatar">
                    <i class="fas fa-user-astronaut"></i>
                </div>

                <div class="profile-name">Anis khoerunnisa N.R</div>
                <div class="profile-role mb-4">
                    Student · Rekayasa Perangkat Lunak
                </div>

                <div class="info-row">
                    <div class="info-label">NAMA LENGKAP</div>
                    <div class="info-value">Anis khoerunnisa N.R</div>
                </div>

                <div class="info-row">
                    <div class="info-label">KELAS</div>
                    <div class="info-value">XII RPL 1</div>
                </div>

                <div class="info-row">
                    <div class="info-label">JURUSAN</div>
                    <div class="info-value">Rekayasa Perangkat Lunak</div>
                </div>

                <div class="info-row">
                    <div class="info-label">STATUS</div>
                    <div class="info-value">
                        <span style="color:#31a879;">
                            ● Still learning &amp; improving
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CERITA -->
        <div class="col-lg-7 mb-4">
            <div class="section-card mb-4">
                <h5>
                    <span class="mini-icon">
                        <i class="fas fa-mug-hot"></i>
                    </span>
                    A Little About Me
                </h5>

                <p>
                    Aku adalah siswa RPL yang lagi belajar
                    mengenal dunia coding dan pengembangan website.
                    Kadang ada error yang bikin pusing, kadang
                    ada hal baru yang bikin penasaran, tapi justru
                    dari situ aku belajar buat lebih teliti dan
                    nggak gampang nyerah. 😆
                </p>
            </div>

            <div class="section-card">
                <h5>
                    <span class="mini-icon">
                        <i class="fas fa-code"></i>
                    </span>
                    My Project
                </h5>

                <p>
                    Project UKK-ku adalah
                    <strong>Sistem Pelanggaran Siswa</strong>.
                    Website ini dibuat untuk membantu pengelolaan
                    data sekolah dan pencatatan pelanggaran siswa
                    supaya lebih terstruktur dan mudah dikelola.
                </p>

                <div class="mt-3">
                    <span class="skill-tag">HTML</span>
                    <span class="skill-tag">CSS</span>
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">Bootstrap</span>
                </div>
            </div>
        </div>

        <!-- MY GOALS -->
        <div class="col-md-6 mb-4">
            <div class="section-card">
                <h5>
                    <span class="mini-icon">
                        <i class="fas fa-bullseye"></i>
                    </span>
                    My Goals
                </h5>

                <p>
                    Aku pengen terus upgrade skill, menambah
                    pengalaman, dan bisa bikin karya yang bukan
                    cuma keren dilihat, tapi juga bermanfaat.
                    Nggak harus langsung jago, yang penting
                    berani mulai dan mau terus belajar. 🌱
                </p>
            </div>
        </div>

        <!-- MOTTO -->
        <div class="col-md-6 mb-4">
            <div class="quote-box">
                <div class="mb-3" style="font-size:30px;color:#a9b5ff;">
                    <i class="fas fa-quote-left"></i>
                </div>

                <h5 class="font-weight-bold">
                    Little Steps, Big Dreams ✨
                </h5>

                <p class="mb-0">
                    "Nggak harus sempurna buat memulai.
                    Mulai aja dulu, belajar dari prosesnya,
                    dan biarkan diri kita berkembang sedikit
                    demi sedikit."
                </p>
            </div>
        </div>

    </div>

    <div class="footer">
        Made with <span style="color:#e8799a;">♥</span>
        and a little bit of code.
        <br>
        Sistem Pelanggaran Siswa &copy; <?= date('Y') ?>
    </div>

</main>
</div>
</div>
</body>
</html>
