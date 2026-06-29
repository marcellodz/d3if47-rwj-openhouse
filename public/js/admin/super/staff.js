console.log("✅ [staff.js] loaded");

/*
========================================
PRESENSI PESERTA
========================================
*/

window.toggleHadir = async function (
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
                <i class="fas fa-undo"></i>
                Batalkan
            `;

            statusEl.textContent =
                "Hadir";

            statusEl.className =
                "status hadir";

        } else {

            btn.classList.remove("active");

            btn.innerHTML = `
                <i class="fas fa-check"></i>
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


/*
========================================
OPEN STAFF FORM
========================================
*/

window.openForm = function (
    action,
    id = '',
    nama = '',
    username = '',
    password = '',
    role = 'staff'
) {

    const modal =
        document.getElementById("staffModal");

    modal.style.display =
        "flex";

    document.getElementById("staffFormTitle").innerHTML =
        action === "add"
            ? "<i class='fas fa-user-plus'></i> Tambah Admin/Staff"
            : "<i class='fas fa-user-edit'></i> Edit Admin/Staff";

    document.getElementById("id_admin").value =
        id;

    document.getElementById("nama_lengkap").value =
        nama;

    document.getElementById("username").value =
        username;

    document.getElementById("password").value =
        password;

    document.getElementById("role").value =
        role;

    document.getElementById("staffForm").onsubmit =
        function (e) {

            e.preventDefault();

            submitStaffForm(action);

        };

};


/*
========================================
CLOSE STAFF FORM
========================================
*/

window.closeForm = function () {

    document.getElementById(
        "staffModal"
    ).style.display = "none";

};


/*
========================================
DELETE USER
========================================
*/

window.deleteUser = async function(id) {

    if (!confirm("Yakin ingin menghapus user ini?")) {
        return;
    }

    try {

        const response = await fetch(
            "/admin/super/staff/action",
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

                    action: "delete",

                    id_admin: id

                })

            }
        );

        const data =
            await response.json();

        if (!data.success) {

            showToast(
                data.message,
                "error"
            );

            return;

        }

        showToast(
            data.message,
            "success"
        );

        showData("staff");

    } catch (err) {

        console.error(err);

        showToast(
            "Gagal menghapus user",
            "error"
        );

    }

};


/*
========================================
SUBMIT STAFF
========================================
*/

async function submitStaffForm(action) {

    const form = document.getElementById("staffForm");

    const formData = new FormData(form);

    formData.append("action", action);

    try {

        const response = await fetch(
            "/admin/super/staff/action",
            {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            }
        );

        const data = await response.json();

        if (!data.success) {

            showToast(
                data.message || "Gagal menyimpan staff",
                "error"
            );

            return;

        }

        showToast(
            data.message,
            "success"
        );

        closeForm();

        showData("staff");

    } catch (err) {

        console.error(err);

        showToast(
            "❌ Gagal koneksi ke server",
            "error"
        );

    }

}