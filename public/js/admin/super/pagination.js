console.log("🔢 pagination.js loaded");

/*
========================================
EVENT DELEGATION - PAGE BUTTON CLICK
========================================
Dipasang ke document (bukan ke #data-section) karena
#data-section di-replace innerHTML-nya tiap ganti halaman,
jadi listener yang nempel langsung di situ akan ikut hilang.

Guard window.__paginationDelegated dipakai karena script ini
ikut di re-run ulang tiap kali showData() me-render ulang konten
(lewat executeInjectedScripts di content-loader.js). Tanpa guard,
listener akan numpuk dan satu klik bisa memanggil showData()
berkali-kali.
========================================
*/

if (!window.__paginationDelegated) {

    window.__paginationDelegated = true;

    document.addEventListener("click", function (e) {

        const btn = e.target.closest(".page-btn");

        // klik bukan di tombol halaman (misal kena .page-dots "..") -> abaikan
        if (!btn) {
            return;
        }

        e.preventDefault();

        const page = parseInt(btn.dataset.page, 10);
        const type = btn.dataset.type;

        if (!page || !type) {
            console.warn("Tombol pagination tidak punya data-page/data-type yang valid.", btn);
            return;
        }

        if (typeof window.showData !== "function") {
            console.error("showData() tidak ditemukan. Pastikan content-loader.js sudah dimuat.");
            return;
        }

        window.showData(type, null, page);

    });

}