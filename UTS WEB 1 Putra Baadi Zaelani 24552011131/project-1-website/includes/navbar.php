<?php
$curr = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar-modern">
    <div class="navbar-container">
        <a href="index.php" class="brand-logo">
            <div class="brand-badge">RW1</div>
            <div>
                <span class="brand-title">LaporWarga</span>
                <span class="brand-sub">RW 01 Cibaduyut Wetan</span>
            </div>
        </a>

        <div class="nav-links">
            <a href="index.php" class="nav-item <?= $curr == 'index.php' ? 'active' : '' ?>">Beranda</a>
            <a href="pengaduan.php" class="nav-item <?= $curr == 'pengaduan.php' ? 'active' : '' ?>">Buat Aduan</a>
            <a href="program.php" class="nav-item <?= $curr == 'program.php' ? 'active' : '' ?>">Program RW</a>
            <a href="tentang.php" class="nav-item <?= $curr == 'tentang.php' ? 'active' : '' ?>">Tentang & Pengurus</a>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="dashboard.php" class="btn-nav-primary">
                    <?= ($_SESSION["role"] ?? "") === "admin" ? "Panel Ketua RW" : "Dashboard" ?>
                </a>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar dari akun?')" class="btn-nav-danger">Keluar</a>
            <?php else: ?>
                <a href="login.php" class="btn-nav-outline">Masuk</a>
                <a href="register.php" class="btn-nav-primary">Daftar Warga</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
