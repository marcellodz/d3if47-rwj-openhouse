console.log("✅ [staff.js] loaded");

window.toggleHadir = async function(
    idKegiatan,
    idUser,
    btn
) {

    const isActive =
        btn.classList.contains("active");

    const action =
        isActive
            ? "batalkan"
            : "hadir";

    try {

        const response =
            await fetch(
                "/admin/staff/presensi/update",
                {
                    method: "POST",

                    headers: {
                        "Content-Type": "application/json",

                        "X-CSRF-TOKEN":
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body: JSON.stringify({
                        id_kegiatan: idKegiatan,
                        iduser: idUser,
                        action: action
                    })
                }
            );

        const resp =
            await response.json();

        if (!resp.success) {

            showToast(
                resp.message ||
                "Gagal update presensi",
                "error"
            );

            return;

        }

        const row =
            btn.closest("tr");

        const statusEl =
            row.querySelector(".status");

        if (action === "hadir") {

            btn.classList.add("active");

            btn.innerHTML = `
                <i class='fas fa-undo'></i>
                Batalkan
            `;

            statusEl.textContent =
                "Hadir";

            statusEl.className =
                "status hadir";

        } else {

            btn.classList.remove("active");

            btn.innerHTML = `
                <i class='fas fa-check'></i>
                Hadir
            `;

            statusEl.textContent =
                "Belum Hadir";

            statusEl.className =
                "status belum-hadir";

        }

        showToast(
            "✅ Presensi berhasil diperbarui",
            "success"
        );

    } catch (err) {

        console.error(err);

        showToast(
            "❌ Gagal koneksi server",
            "error"
        );

    }

};