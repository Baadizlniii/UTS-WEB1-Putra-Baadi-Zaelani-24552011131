<?php
require_once "config/database.php";

$pageTitle = "Beranda - LaporWarga RW 01 Cibaduyut Wetan";
include "includes/header.php";

$totalAll = $conn->query("SELECT COUNT(*) AS c FROM laporan")->fetch_assoc()["c"] ?? 0;
$totalRt1 = $conn->query("SELECT COUNT(*) AS c FROM laporan WHERE rt='01'")->fetch_assoc()["c"] ?? 0;
$totalRt2 = $conn->query("SELECT COUNT(*) AS c FROM laporan WHERE rt='02'")->fetch_assoc()["c"] ?? 0;
$totalRt3 = $conn->query("SELECT COUNT(*) AS c FROM laporan WHERE rt='03'")->fetch_assoc()["c"] ?? 0;
$totalSelesai = $conn->query("SELECT COUNT(*) AS c FROM laporan WHERE status='Selesai'")->fetch_assoc()["c"] ?? 0;

$filterRt = $_GET["rt"] ?? "";
if (in_array($filterRt, ["01", "02", "03"], true)) {
    $stmt = $conn->prepare("SELECT laporan.*, users.nama
                            FROM laporan
                            JOIN users ON laporan.user_id = users.id
                            WHERE laporan.rt = ?
                            ORDER BY laporan.created_at DESC");
    $stmt->bind_param("s", $filterRt);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $filterRt = "";
    $query = "SELECT laporan.*, users.nama
              FROM laporan
              JOIN users ON laporan.user_id = users.id
              ORDER BY laporan.created_at DESC
              LIMIT 12";
    $result = $conn->query($query);
}
?>

<section class="hero-banner">
    <div class="hero-inner">
        <div>
            <span class="hero-pill">● PORTAL RESMI WARGA RT 01, RT 02 & RT 03</span>
            <h1 class="hero-title">
                Suarakan Aspirasi Warga <span>Cibaduyut Wetan</span> Secara Transparan
            </h1>
            <p class="hero-desc">
                Platform resmi warga RW 01 (RT 01, RT 02, dan RT 03) Kelurahan Cibaduyut Wetan, Kec. Bojongloa Kidul, Kota Bandung untuk melaporkan permasalahan infrastruktur, drainase, penerangan jalan, dan kebersihan lingkungan.
            </p>
            <div class="hero-actions">
                <a href="pengaduan.php" class="btn-hero-main">+ Buat Pengaduan Sekarang</a>
                <a href="#laporan-terbaru" class="btn-hero-sec">Pantau Laporan Warga</a>
            </div>
        </div>

        <div class="hero-illustration-card">
            <svg viewBox="0 0 420 250" style="width:100%; height:auto; display:block;" xmlns="http://www.w3.org/2000/svg">
                <rect width="420" height="250" rx="14" fill="#0f172a"/>
                <circle cx="355" cy="52" r="30" fill="#10b981" opacity="0.22"/>
                <text x="210" y="36" fill="#f8fafc" font-size="13" font-weight="800" text-anchor="middle" letter-spacing="1">WILAYAH RW 01 CIBADUYUT WETAN</text>

                <!-- RT 01 -->
                <rect x="28" y="132" width="102" height="76" rx="8" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                <polygon points="22,132 79,92 136,132" fill="#10b981"/>
                <text x="79" y="168" fill="#ffffff" font-size="14" font-weight="800" text-anchor="middle">RT 01</text>
                <text x="79" y="186" fill="#94a3b8" font-size="10" text-anchor="middle">Wilayah Utara</text>

                <!-- BALAI RW 01 / RT 02 -->
                <rect x="156" y="114" width="108" height="94" rx="8" fill="#1e293b" stroke="#10b981" stroke-width="2"/>
                <polygon points="150,114 210,70 270,114" fill="#059669"/>
                <text x="210" y="156" fill="#ffffff" font-size="13" font-weight="800" text-anchor="middle">BALAI RW 01</text>
                <text x="210" y="176" fill="#34d399" font-size="12" font-weight="700" text-anchor="middle">RT 02</text>

                <!-- RT 03 -->
                <rect x="290" y="132" width="102" height="76" rx="8" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                <polygon points="284,132 341,92 398,132" fill="#10b981"/>
                <text x="341" y="168" fill="#ffffff" font-size="14" font-weight="800" text-anchor="middle">RT 03</text>
                <text x="341" y="186" fill="#94a3b8" font-size="10" text-anchor="middle">Wilayah Selatan</text>

                <rect x="20" y="210" width="380" height="12" rx="6" fill="#334155"/>
            </svg>
        </div>
    </div>
</section>

<div class="stats-wrapper">
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total Laporan RW 01</span>
            <div class="stat-value"><?= (int)$totalAll ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label" style="color:#2563eb;">Wilayah RT 01</span>
            <div class="stat-value"><?= (int)$totalRt1 ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label" style="color:#4f46e5;">Wilayah RT 02</span>
            <div class="stat-value"><?= (int)$totalRt2 ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label" style="color:#0d9488;">Wilayah RT 03</span>
            <div class="stat-value"><?= (int)$totalRt3 ?></div>
        </div>
        <div class="stat-card highlight">
            <span class="stat-label">Laporan Tuntas</span>
            <div class="stat-value"><?= (int)$totalSelesai ?></div>
        </div>
    </div>
</div>

<section id="laporan-terbaru" class="container-main">
    <div class="section-header">
        <div>
            <span class="section-kicker">Transparansi Lingkungan</span>
            <h2 class="section-title">Pengaduan Warga Terbaru</h2>
            <p class="section-subtitle">Pantau laporan masyarakat yang masuk dari wilayah RT 01, RT 02, dan RT 03</p>
        </div>
        <a href="pengaduan.php" class="btn-nav-primary">+ Tulis Laporan Baru</a>
    </div>

    <div class="filter-toolbar">
        <div class="filter-pills">
            <a href="index.php#laporan-terbaru" class="filter-pill <?= $filterRt === '' ? 'active' : '' ?>">Semua RT</a>
            <a href="index.php?rt=01#laporan-terbaru" class="filter-pill <?= $filterRt === '01' ? 'active' : '' ?>">RT 01</a>
            <a href="index.php?rt=02#laporan-terbaru" class="filter-pill <?= $filterRt === '02' ? 'active' : '' ?>">RT 02</a>
            <a href="index.php?rt=03#laporan-terbaru" class="filter-pill <?= $filterRt === '03' ? 'active' : '' ?>">RT 03</a>
        </div>
        <input type="text" id="pencarian" placeholder="Cari judul, kategori, atau pelapor..." class="form-control" style="max-width:300px; padding:9px 14px;">
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="cards-grid">
            <?php while ($laporan = $result->fetch_assoc()):
                $statusClass = "status-menunggu";
                if ($laporan["status"] === "Diproses") $statusClass = "status-diproses";
                if ($laporan["status"] === "Selesai") $statusClass = "status-selesai";
            ?>
                <article data-laporan-card class="report-card">
                    <div class="report-banner">
                        <span class="badge-rt">RT <?= htmlspecialchars($laporan["rt"]) ?> / RW 01</span>
                        <span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($laporan["status"]) ?></span>
                        <span style="font-size:0.75rem; color:#34d399; font-weight:700; text-transform:uppercase;">
                            <?= htmlspecialchars($laporan["kategori"]) ?>
                        </span>
                        <strong style="font-size:0.95rem; margin-top:2px;">Cibaduyut Wetan</strong>
                    </div>

                    <div class="report-body">
                        <div class="report-cat">#LPR-00<?= (int)$laporan["id"] ?> • <?= htmlspecialchars($laporan["kategori"]) ?></div>
                        <h3 class="report-title"><?= htmlspecialchars($laporan["judul"]) ?></h3>
                        <p class="report-desc"><?= htmlspecialchars(substr($laporan["deskripsi"], 0, 120)) ?>...</p>

                        <div class="report-footer">
                            <span>Oleh: <strong><?= htmlspecialchars($laporan["nama"]) ?></strong></span>
                            <a href="detail.php?id=<?= (int)$laporan["id"] ?>" class="link-detail">Lihat Detail &rarr;</a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="form-card" style="text-align:center; padding:48px;">
            <h3 style="margin-bottom:8px;">Belum Ada Data Pengaduan</h3>
            <p style="color:#64748b; margin-bottom:20px;">Belum ada laporan untuk filter ini, atau database belum di-import.</p>
            <a href="pengaduan.php" class="btn-nav-primary">+ Buat Pengaduan Pertama</a>
        </div>
    <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
