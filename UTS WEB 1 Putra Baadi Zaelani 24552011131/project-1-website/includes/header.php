<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? "LaporWarga RW 01 Cibaduyut Wetan";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <!-- Framework CSS (Tailwind CSS) + Fallback CSS Lokal Mandiri -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
</head>
<body>
    <?php include __DIR__ . "/navbar.php"; ?>
    <main>
