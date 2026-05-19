console.log("✅ [super/booth.js] loaded");

/*
========================================
GET CSRF TOKEN
========================================
*/
function getCsrfToken() {

    return document
        .querySelector(
            'meta[name="csrf-token"]'
        )
        ?.getAttribute("content");

}

/*
========================================
OPEN FORM
========================================
*/
window.openBoothForm = function(
    action,
    id = '',
    nama = '',
    kategori = '',
    lantai = ''
) {

    const modal =
        document.getElementById("boothModal");

    modal.style.display =
        "flex";

    document.getElementById(
        "boothFormTitle"
    ).innerHTML =
        action === "add"
            ? "<i class='fas fa-store'></i> Tambah Booth"
            : "<i class='fas fa-edit'></i> Edit Booth";

    document.getElementById("id_booth").value =
        id;

    document.getElementById("nama_booth").value =
        nama;

    document.getElementById("kategori").value =
        kategori || '';

    document.getElementById("lantai").value =
        lantai || '';

    document.getElementById("boothForm")
        .onsubmit = (e) => {

            e.preventDefault();

            submitBoothForm(action);

        };

};

/*
========================================
CLOSE FORM
========================================
*/
window.closeBoothForm = function() {

    document.getElementById(
        "boothModal"
    ).style.display = "none";

};

/*
========================================
SUBMIT BOOTH
========================================
*/
async function submitBoothForm(action) {

    const form =
        document.getElementById("boothForm");

    const formData =
        new FormData(form);

    formData.append(
        "action",
        action
    );

    try {

        const response =
            await fetch(
                "/admin/super/booth/action",
                {
                    method: "POST",

                    headers: {
                        "X-CSRF-TOKEN":
                            getCsrfToken()
                    },

                    body: formData
                }
            );

        const data =
            await response.json();

        if (!data.success) {

            showToast(
                data.message ||
                "Gagal menyimpan booth",
                "error"
            );

            return;

        }

        showToast(
            data.message ||
            "Booth berhasil disimpan",
            "success"
        );

        closeBoothForm();

        refreshBoothTableAndCount();

    } catch (err) {

        console.error(err);

        showToast(
            "❌ Gagal memproses data booth",
            "error"
        );

    }

}

/*
========================================
DELETE BOOTH
========================================
*/
window.deleteBooth = function(id) {

    showConfirm(
        "Yakin ingin menghapus booth ini?",
        async (confirmed) => {

            if (!confirmed) return;

            try {

                const response =
                    await fetch(
                        "/admin/super/booth/action",
                        {
                            method: "POST",

                            headers: {
                                "Content-Type":
                                    "application/json",

                                "X-CSRF-TOKEN":
                                    getCsrfToken()
                            },

                            body: JSON.stringify({
                                action: "delete",
                                id: id
                            })
                        }
                    );

                const data =
                    await response.json();

                if (!data.success) {

                    showToast(
                        data.message ||
                        "Gagal hapus booth",
                        "error"
                    );

                    return;

                }

                showToast(
                    data.message ||
                    "Booth berhasil dihapus",
                    "success"
                );

                refreshBoothTableAndCount();

            } catch (err) {

                console.error(err);

                showToast(
                    "❌ Network error",
                    "error"
                );

            }

        }
    );

};

/*
========================================
REFRESH TABLE
========================================
*/
async function refreshBoothTableAndCount() {

    const container =
        document.getElementById(
            "data-section"
        );

    container.innerHTML = `
        <div class="loading">
            ⏳ Memuat data booth...
        </div>
    `;

    try {

        const response =
            await fetch(
                "/admin/super/content?type=booth",
                {
                    method: "POST",

                    headers: {
                        "X-CSRF-TOKEN":
                            getCsrfToken()
                    }
                }
            );

        const html =
            await response.text();

        container.innerHTML =
            html;

        const totalBooth =
            container.querySelectorAll(
                "tbody tr"
            ).length;

        const cardText =
            document.querySelector(
                ".card:nth-child(2) p"
            );

        if (cardText) {

            cardText.textContent =
                `${totalBooth} Aktif`;

        }

    } catch (err) {

        console.error(err);

        showToast(
            "❌ Gagal memuat data booth",
            "error"
        );

    }

}