<?php
require_once "config/database.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit("ID laporan tidak valid.");
}

$stmt = $conn->prepare("SELECT laporan.*, users.nama FROM laporan JOIN users ON laporan.user_id = users.id WHERE laporan.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$laporan = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$laporan) {
    http_response_code(404);
    exit("Laporan tidak ditemukan.");
}

$status = $laporan["status"];
$statusClass = "status-menunggu";
if ($status === "Diproses") $statusClass = "status-diproses";
if ($status === "Selesai") $statusClass = "status-selesai";

$pageTitle = "Detail Pengaduan - " . $laporan["judul"];
include "includes/header.php";
?>

<div class="page-wrapper-md">
    <a href="index.php" style="display:inline-block; margin-bottom:18px; color:#059669; font-weight:700; font-size:0.9rem;">&larr; Kembali ke Beranda</a>

    <div class="form-card">
        <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; margin-bottom:16px;">
            <span style="font-size:0.85rem; font-weight:800; color:#059669; text-transform:uppercase;">
                #LPR-00<?= (int)$laporan["id"] ?> • <?= htmlspecialchars($laporan["kategori"]) ?>
            </span>
            <span class="badge-status <?= $statusClass ?>" style="position:static; font-size:0.82rem; padding:6px 14px;">
                Status: <?= htmlspecialchars($status) ?>
            </span>
        </div>

        <h1 style="font-size:1.9rem; font-weight:800; line-height:1.25; margin-bottom:14px;">
            <?= htmlspecialchars($laporan["judul"]) ?>
        </h1>

        <div style="display:flex; flex-wrap:wrap; gap:18px; font-size:0.88rem; color:#64748b; padding-bottom:20px; border-bottom:1px solid #e2e8f0;">
            <span>Pelapor: <strong style="color:#0f172a;"><?= htmlspecialchars($laporan["nama"]) ?></strong></span>
            <span>Wilayah: <strong style="color:#0f172a;">RT <?= htmlspecialchars($laporan["rt"]) ?> / RW 01</strong></span>
            <span>Tanggal: <strong style="color:#0f172a;"><?= htmlspecialchars($laporan["created_at"]) ?></strong></span>
        </div>

        <div class="progress-steps">
            <div class="step-box done">
                <strong style="font-size:0.85rem; display:block;">1. Laporan Diterima</strong>
                <span style="font-size:0.76rem;">Tercatat di sistem RW 01</span>
            </div>
            <div class="step-box <?= ($status === 'Diproses' || $status === 'Selesai') ? 'done' : '' ?>">
                <strong style="font-size:0.85rem; display:block;">2. Sedang Ditindaklanjuti</strong>
                <span style="font-size:0.76rem;">Peninjauan pengurus RT/RW</span>
            </div>
            <div class="step-box <?= ($status === 'Selesai') ? 'done' : '' ?>">
                <strong style="font-size:0.85rem; display:block;">3. Selesai Ditangani</strong>
                <span style="font-size:0.76rem;">Permasalahan tuntas</span>
            </div>
        </div>

        <div style="margin-top:20px;">
            <h2 style="font-size:1.1rem; font-weight:800; margin-bottom:10px;">Uraian Pengaduan</h2>
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:18px; color:#334155; white-space:pre-line;">
                <?= htmlspecialchars($laporan["deskripsi"]) ?>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
