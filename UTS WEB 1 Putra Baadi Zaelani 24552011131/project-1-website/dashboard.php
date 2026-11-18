<?php
require_once "config/database.php";
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];
$isAdmin = (($_SESSION["role"] ?? "") === "admin");
$pesanSukses = "";

// Proses aksi admin (ubah status atau hapus) atau warga (hapus laporan miliknya yang masih Menunggu)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aksi = $_POST["aksi"] ?? "";
    $laporanId = (int)($_POST["laporan_id"] ?? 0);

    if ($isAdmin && $aksi === "ubah_status" && $laporanId > 0) {
        $statusBaru = $_POST["status"] ?? "";
        if (in_array($statusBaru, ["Menunggu", "Diproses", "Selesai"], true)) {
            $upd = $conn->prepare("UPDATE laporan SET status = ? WHERE id = ?");
            $upd->bind_param("si", $statusBaru, $laporanId);
            $upd->execute();
            $upd->close();
            $pesanSukses = "Status laporan #LPR-00{$laporanId} berhasil diperbarui menjadi {$statusBaru}.";
        }
    } elseif ($aksi === "hapus" && $laporanId > 0) {
        if ($isAdmin) {
            $del = $conn->prepare("DELETE FROM laporan WHERE id = ?");
            $del->bind_param("i", $laporanId);
            $del->execute();
            $del->close();
            $pesanSukses = "Laporan #LPR-00{$laporanId} berhasil dihapus.";
        } else {
            $del = $conn->prepare("DELETE FROM laporan WHERE id = ? AND user_id = ? AND status = 'Menunggu'");
            $del->bind_param("ii", $laporanId, $userId);
            $del->execute();
            if ($del->affected_rows > 0) {
                $pesanSukses = "Laporan berhasil dibatalkan.";
            }
            $del->close();
        }
    }
}

if ($isAdmin) {
    $result = $conn->query("SELECT laporan.*, users.nama FROM laporan JOIN users ON laporan.user_id = users.id ORDER BY laporan.created_at DESC");
} else {
    $stmt = $conn->prepare("SELECT laporan.*, users.nama FROM laporan JOIN users ON laporan.user_id = users.id WHERE laporan.user_id = ? ORDER BY laporan.created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
}

$pageTitle = ($isAdmin ? "Panel Ketua RW 01" : "Dashboard Warga") . " - LaporWarga";
include "includes/header.php";
?>

<section class="container-main">
    <div class="section-header">
        <div>
            <span class="section-kicker"><?= $isAdmin ? "Administrasi Ketua RW 01" : "Panel Warga RT " . htmlspecialchars($_SESSION["rt"] ?? "01") ?></span>
            <h1 class="section-title">Halo, <?= htmlspecialchars($_SESSION["nama"]) ?></h1>
            <p class="section-subtitle">
                <?= $isAdmin
                    ? "Kelola seluruh pengaduan warga dari RT 01, RT 02, dan RT 03 serta perbarui status tindak lanjutnya."
                    : "Pantau status tindak lanjut seluruh pengaduan yang pernah Anda kirimkan." ?>
            </p>
        </div>
        <a href="pengaduan.php" class="btn-nav-primary">+ Buat Pengaduan Baru</a>
    </div>

    <?php if (isset($_GET["laporan"]) && $_GET["laporan"] === "berhasil"): ?>
        <div class="alert-success">Pengaduan baru berhasil dikirim dan masuk ke sistem RW 01!</div>
    <?php endif; ?>

    <?php if ($pesanSukses): ?>
        <div class="alert-success"><?= htmlspecialchars($pesanSukses) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:14px; margin-bottom:24px;">
            <h2 style="font-size:1.25rem; font-weight:800;">
                <?= $isAdmin ? "Daftar Seluruh Pengaduan Masuk (RW 01)" : "Riwayat Pengaduan Saya" ?>
            </h2>
            <input type="text" id="pencarian" placeholder="Cari judul, RT, atau kategori..." class="form-control" style="max-width:320px;">
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <div style="display:flex; flex-direction:column; gap:16px;">
                <?php while ($laporan = $result->fetch_assoc()):
                    $statusClass = "status-menunggu";
                    if ($laporan["status"] === "Diproses") $statusClass = "status-diproses";
                    if ($laporan["status"] === "Selesai") $statusClass = "status-selesai";
                ?>
                    <div data-laporan-card style="border:1px solid #e2e8f0; border-radius:14px; padding:20px 22px; display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:16px; background:#f8fafc;">
                        <div style="flex:1; min-width:250px;">
                            <div style="display:flex; flex-wrap:wrap; align-items:center; gap:10px; margin-bottom:6px;">
                                <span class="badge-status <?= $statusClass ?>" style="position:static;"><?= htmlspecialchars($laporan["status"]) ?></span>
                                <span style="font-size:0.82rem; font-weight:700; color:#059669;">
                                    #LPR-00<?= (int)$laporan["id"] ?> • RT <?= htmlspecialchars($laporan["rt"]) ?> • <?= htmlspecialchars($laporan["kategori"]) ?>
                                </span>
                            </div>
                            <h3 style="font-size:1.12rem; font-weight:800;"><?= htmlspecialchars($laporan["judul"]) ?></h3>
                            <p style="font-size:0.84rem; color:#64748b; margin-top:4px;">
                                Pelapor: <strong><?= htmlspecialchars($laporan["nama"]) ?></strong> • <?= htmlspecialchars($laporan["created_at"]) ?>
                            </p>
                        </div>

                        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:10px;">
                            <?php if ($isAdmin): ?>
                                <form method="POST" style="display:flex; flex-direction:row; align-items:center; gap:8px; margin:0;">
                                    <input type="hidden" name="aksi" value="ubah_status">
                                    <input type="hidden" name="laporan_id" value="<?= (int)$laporan["id"] ?>">
                                    <select name="status" class="form-control" style="padding:7px 10px; font-size:0.82rem; width:auto;">
                                        <option value="Menunggu" <?= $laporan["status"] === "Menunggu" ? "selected" : "" ?>>Menunggu</option>
                                        <option value="Diproses" <?= $laporan["status"] === "Diproses" ? "selected" : "" ?>>Diproses</option>
                                        <option value="Selesai" <?= $laporan["status"] === "Selesai" ? "selected" : "" ?>>Selesai</option>
                                    </select>
                                    <button type="submit" class="btn-sm-action btn-sm-blue">Simpan</button>
                                </form>
                            <?php endif; ?>

                            <a href="detail.php?id=<?= (int)$laporan["id"] ?>" class="btn-nav-primary" style="padding:8px 14px; font-size:0.84rem;">Detail</a>

                            <?php if ($isAdmin || $laporan["status"] === "Menunggu"): ?>
                                <form method="POST" onsubmit="return confirm('Hapus pengaduan ini?')" style="margin:0;">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="laporan_id" value="<?= (int)$laporan["id"] ?>">
                                    <button type="submit" class="btn-sm-action btn-sm-red">Hapus</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="color:#64748b; text-align:center; padding:32px 0;">Belum ada data pengaduan.</p>
        <?php endif; ?>
    </div>
</section>

<?php
if (isset($stmt)) $stmt->close();
include "includes/footer.php";
?>
