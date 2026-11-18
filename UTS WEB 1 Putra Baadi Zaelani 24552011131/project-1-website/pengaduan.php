<?php
require_once "config/database.php";
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$error = "";
$defaultRt = $_SESSION["rt"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = trim($_POST["judul"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $rt = trim($_POST["rt"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $userId = $_SESSION["user_id"];

    $kategoriValid = ["Infrastruktur", "Kebersihan", "Keamanan", "Sosial", "Lainnya"];
    $rtValid = ["01", "02", "03"];

    if ($judul === "" || $deskripsi === "") {
        $error = "Judul dan deskripsi wajib diisi.";
    } elseif (!in_array($kategori, $kategoriValid, true)) {
        $error = "Kategori tidak valid.";
    } elseif (!in_array($rt, $rtValid, true)) {
        $error = "RT tidak valid.";
    } else {
        $stmt = $conn->prepare("INSERT INTO laporan (user_id, judul, kategori, rt, deskripsi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $userId, $judul, $kategori, $rt, $deskripsi);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: dashboard.php?laporan=berhasil");
            exit;
        } else {
            $error = "Pengaduan gagal dikirim.";
        }
        $stmt->close();
    }
}

$pageTitle = "Buat Pengaduan - LaporWarga";
include "includes/header.php";
?>

<div class="page-wrapper-md">
    <div class="form-card">
        <span class="section-kicker">Formulir Laporan RW 01</span>
        <h1 style="font-size:1.85rem; font-weight:800; margin-bottom:6px;">Sampaikan Pengaduan Anda</h1>
        <p style="color:#64748b; font-size:0.95rem; margin-bottom:26px;">Laporan akan langsung tercatat dan diteruskan ke pengurus RT/RW 01 Cibaduyut Wetan.</p>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Judul Pengaduan</label>
                <input type="text" name="judul" maxlength="150" required value="<?= htmlspecialchars($_POST["judul"] ?? "") ?>" placeholder="Contoh: Saluran air tersumbat di depan Gang H. Kurdi" class="form-control">
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div class="form-group">
                    <label class="form-label">Kategori Laporan</label>
                    <select name="kategori" required class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Infrastruktur">Infrastruktur & Jalan</option>
                        <option value="Kebersihan">Kebersihan & Drainase</option>
                        <option value="Keamanan">Keamanan & Siskamling</option>
                        <option value="Sosial">Sosial & Kesehatan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Wilayah RT</label>
                    <select name="rt" required class="form-control">
                        <option value="">-- Pilih RT --</option>
                        <option value="01" <?= ($defaultRt === "01") ? "selected" : "" ?>>RT 01</option>
                        <option value="02" <?= ($defaultRt === "02") ? "selected" : "" ?>>RT 02</option>
                        <option value="03" <?= ($defaultRt === "03") ? "selected" : "" ?>>RT 03</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Lengkap Kejadian & Lokasi</label>
                <textarea name="deskripsi" rows="5" required placeholder="Jelaskan kronologi dan patokan lokasi secara jelas..." class="form-control"><?= htmlspecialchars($_POST["deskripsi"] ?? "") ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Kirim Laporan Pengaduan</button>
        </form>
    </div>
</div>

<?php include "includes/footer.php"; ?>
