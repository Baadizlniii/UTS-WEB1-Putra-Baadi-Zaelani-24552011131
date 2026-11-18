<?php
require_once "config/database.php";
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $rt = $_POST["rt"] ?? "01";
    $password = $_POST["password"] ?? "";
    $konfirmasi = $_POST["konfirmasi_password"] ?? "";

    if ($nama === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Nama dan alamat email harus diisi dengan benar.";
    } elseif (!in_array($rt, ["01", "02", "03"], true)) {
        $error = "Pilih wilayah RT yang valid.";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak sama.";
    } else {
        $cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $sudahAda = $cek->get_result()->num_rows > 0;
        $cek->close();

        if ($sudahAda) {
            $error = "Email sudah terdaftar.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, rt) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $email, $hash, $rt);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: login.php?daftar=berhasil");
                exit;
            } else {
                $error = "Registrasi gagal. Silakan coba lagi.";
            }
            $stmt->close();
        }
    }
}

$pageTitle = "Daftar Akun Warga - LaporWarga";
include "includes/header.php";
?>

<div class="auth-wrapper">
    <div class="form-card">
        <span class="section-kicker">Registrasi Warga RW 01</span>
        <h1 style="font-size:1.75rem; font-weight:800; margin-bottom:6px;">Buat Akun Baru</h1>
        <p style="color:#64748b; font-size:0.92rem; margin-bottom:24px;">Khusus warga domisili RT 01, RT 02, dan RT 03 Cibaduyut Wetan</p>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form id="formRegister" method="POST">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" required value="<?= htmlspecialchars($_POST["nama"] ?? "") ?>" placeholder="Masukkan nama lengkap" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" placeholder="nama@email.com" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Wilayah RT (RW 01 Cibaduyut Wetan)</label>
                <select name="rt" required class="form-control">
                    <option value="01" <?= (($_POST["rt"] ?? "") === "01") ? "selected" : "" ?>>RT 01</option>
                    <option value="02" <?= (($_POST["rt"] ?? "") === "02") ? "selected" : "" ?>>RT 02</option>
                    <option value="03" <?= (($_POST["rt"] ?? "") === "03") ? "selected" : "" ?>>RT 03</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Password (Minimal 8 Karakter)</label>
                <input type="password" name="password" id="password" minlength="8" required placeholder="••••••••" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" required placeholder="Ulangi password" class="form-control">
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>
        </form>

        <p style="text-align:center; margin-top:20px; font-size:0.88rem; color:#64748b;">
            Sudah punya akun? <a href="login.php" style="color:#059669; font-weight:700;">Masuk di sini</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
