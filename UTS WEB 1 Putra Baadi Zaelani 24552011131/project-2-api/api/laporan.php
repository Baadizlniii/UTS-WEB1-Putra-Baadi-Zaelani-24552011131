<?php
require_once "../config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function kirimJson($statusCode, $payload) {
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    // READ: Mengambil data laporan
    case "GET":

        if (isset($_GET["id"])) {
            $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);

            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "ID tidak valid"
                ]);
                exit;
            }

            $stmt = $conn->prepare("SELECT * FROM laporan WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $data = $stmt->get_result()->fetch_assoc();

            if ($data) {
                echo json_encode([
                    "success" => true,
                    "data" => $data
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Laporan tidak ditemukan"
                ]);
            }

            $stmt->close();
        } else {
            $result = $conn->query(
                "SELECT * FROM laporan ORDER BY created_at DESC"
            );

            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode([
                "success" => true,
                "data" => $data
            ]);
        }

        break;

    // CREATE: Menambahkan laporan
    case "POST":

        $input = json_decode(file_get_contents("php://input"), true);

        if (!is_array($input)) {
            kirimJson(400, [
                "success" => false,
                "message" => "Format JSON tidak valid"
            ]);
        }

        if (!isset($_SESSION["user_id"])) {
            kirimJson(401, [
                "success" => false,
                "message" => "Silakan login terlebih dahulu"
            ]);
        }

        // ID pengguna diambil dari session, bukan dari input request,
        // agar pengguna tidak dapat mengirim laporan atas nama akun lain.
        $userId = (int) $_SESSION["user_id"];
        $judul = trim($input["judul"] ?? "");
        $kategori = trim($input["kategori"] ?? "");
        $rt = trim($input["rt"] ?? "");
        $deskripsi = trim($input["deskripsi"] ?? "");

        $kategoriValid = ["Infrastruktur", "Kebersihan", "Keamanan", "Sosial", "Lainnya"];
        $rtValid = ["01", "02", "03"];

        if (
            $judul === "" ||
            $deskripsi === "" ||
            !in_array($kategori, $kategoriValid, true) ||
            !in_array($rt, $rtValid, true)
        ) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Data laporan tidak lengkap atau tidak valid"
            ]);
            break;
        }

        $cekUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $cekUser->bind_param("i", $userId);
        $cekUser->execute();
        $userAda = $cekUser->get_result()->num_rows > 0;
        $cekUser->close();

        if (!$userAda) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Pengguna tidak ditemukan"
            ]);
            break;
        }

        $stmt = $conn->prepare(
            "INSERT INTO laporan (user_id, judul, kategori, rt, deskripsi)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "issss",
            $userId,
            $judul,
            $kategori,
            $rt,
            $deskripsi
        );

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "Laporan berhasil ditambahkan",
                "id" => $stmt->insert_id
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Gagal menambahkan laporan"
            ]);
        }

        $stmt->close();
        break;

    // UPDATE: Mengubah status laporan
    case "PUT":

        if (($_SESSION["role"] ?? "") !== "admin") {
            kirimJson(isset($_SESSION["user_id"]) ? 403 : 401, [
                "success" => false,
                "message" => "Hanya admin yang dapat mengubah status laporan"
            ]);
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!is_array($input)) {
            kirimJson(400, [
                "success" => false,
                "message" => "Format JSON tidak valid"
            ]);
        }

        $id = (int)($input["id"] ?? 0);
        $status = $input["status"] ?? "";

        $statusValid = ["Menunggu", "Diproses", "Selesai"];

        if (!$id || !in_array($status, $statusValid, true)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID atau status tidak valid"
            ]);
            break;
        }

        $stmt = $conn->prepare(
            "UPDATE laporan SET status = ? WHERE id = ?"
        );

        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "success" => true,
                "message" => "Status laporan berhasil diperbarui"
            ]);
        } else {
            $cek = $conn->prepare("SELECT id FROM laporan WHERE id = ?");
            $cek->bind_param("i", $id);
            $cek->execute();
            $ada = $cek->get_result()->num_rows > 0;
            $cek->close();

            if ($ada) {
                echo json_encode([
                    "success" => true,
                    "message" => "Status laporan tidak berubah"
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    "success" => false,
                    "message" => "Laporan tidak ditemukan"
                ]);
            }
        }

        $stmt->close();
        break;

    // DELETE: Menghapus laporan
    case "DELETE":

        if (($_SESSION["role"] ?? "") !== "admin") {
            kirimJson(isset($_SESSION["user_id"]) ? 403 : 401, [
                "success" => false,
                "message" => "Hanya admin yang dapat menghapus laporan"
            ]);
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!is_array($input)) {
            kirimJson(400, [
                "success" => false,
                "message" => "Format JSON tidak valid"
            ]);
        }

        $id = (int)($input["id"] ?? 0);

        if (!$id) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "ID laporan wajib diisi"
            ]);
            break;
        }

        $stmt = $conn->prepare("DELETE FROM laporan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "success" => true,
                "message" => "Laporan berhasil dihapus"
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Laporan tidak ditemukan"
            ]);
        }

        $stmt->close();
        break;

    default:

        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Method tidak diizinkan"
        ]);
}
?>