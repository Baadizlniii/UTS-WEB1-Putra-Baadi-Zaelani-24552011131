<?php
require_once "config/database.php";
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT id, nama, email, password, rt, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["nama"] = $user["nama"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["rt"] = $user["rt"];
        $_SESSION["role"] = $user["role"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}

$pageTitle = "Masuk - LaporWarga RW 01";
include "includes/header.php";
?>

<div class="auth-wrapper">
    <div class="form-card">
        <span class="section-kicker">Akses Warga & Pengurus</span>
        <h1 style="font-size:1.75rem; font-weight:800; margin-bottom:6px;">Masuk ke LaporWarga</h1>
        <p style="color:#64748b; font-size:0.92rem; margin-bottom:24px;">Portal Pengaduan Masyarakat RW 01 Cibaduyut Wetan</p>

        <?php if (isset($_GET["daftar"])): ?>
            <div class="alert-success">Registrasi berhasil! Silakan masuk menggunakan akun Anda.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form id="formLogin" method="POST">
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" id="loginEmail" required placeholder="nama@email.com" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <input type="password" name="password" id="loginPassword" required placeholder="••••••••" class="form-control">
            </div>

            <button type="submit" class="btn-submit">Masuk Sekarang</button>
        </form>

        <div style="margin-top:22px; padding:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:0.82rem; color:#475569; line-height:1.7;">
            <strong style="color:#0f172a; display:block; margin-bottom:4px;">Akun Uji Coba (Password: <code>password</code>)</strong>
            • Warga: <code>putra@warga.id</code><br>
            • Ketua RW (Admin): <code>admin@rw01.id</code>
        </div>

        <p style="text-align:center; margin-top:20px; font-size:0.88rem; color:#64748b;">
            Belum punya akun? <a href="register.php" style="color:#059669; font-weight:700;">Daftar Warga</a>
        </p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
