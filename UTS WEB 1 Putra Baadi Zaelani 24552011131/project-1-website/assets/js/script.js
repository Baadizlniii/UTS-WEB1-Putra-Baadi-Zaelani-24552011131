document.addEventListener("DOMContentLoaded", function () {
    // Validasi Login dengan Struktur Percabangan IF (Ketentuan Project 1 No. 3)
    const formLogin = document.getElementById("formLogin");

    if (formLogin) {
        formLogin.addEventListener("submit", function (event) {
            const email = document.getElementById("loginEmail").value.trim();
            const password = document.getElementById("loginPassword").value;

            if (email === "" || password === "") {
                event.preventDefault();
                alert("Email dan password wajib diisi!");
                return;
            }

            if (!email.includes("@")) {
                event.preventDefault();
                alert("Format email tidak valid!");
                return;
            }

            if (password.length < 6) {
                event.preventDefault();
                alert("Password minimal 6 karakter!");
                return;
            }
        });
    }

    // Validasi Registrasi dengan Struktur Percabangan IF
    const formRegister = document.getElementById("formRegister");

    if (formRegister) {
        formRegister.addEventListener("submit", function (event) {
            const password = document.getElementById("password").value;
            const konfirmasi = document.getElementById("konfirmasi_password").value;

            if (password.length < 8) {
                event.preventDefault();
                alert("Password minimal 8 karakter.");
                return;
            }

            if (password !== konfirmasi) {
                event.preventDefault();
                alert("Konfirmasi password tidak sama.");
            }
        });
    }

    // Pencarian Langsung Kartu Laporan
    const pencarian = document.getElementById("pencarian");

    if (pencarian) {
        pencarian.addEventListener("input", function () {
            const kataKunci = pencarian.value.toLowerCase();
            const kartu = document.querySelectorAll("[data-laporan-card]");

            kartu.forEach(function (item) {
                const isi = item.textContent.toLowerCase();
                item.style.display = isi.includes(kataKunci) ? "" : "none";
            });
        });
    }
});
