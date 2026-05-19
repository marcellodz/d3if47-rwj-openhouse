console.log("📊 Laravel staff dashboard.js loaded");

/*
=========================
ELEMENT
=========================
*/

const pilihSesi =
    document.getElementById("pilihSesi");

const pilihKegiatan =
    document.getElementById("pilihKegiatan");

const label1 =
    document.getElementById("label1");

const label2 =
    document.getElementById("label2");

const label3 =
    document.getElementById("label3");

const val1 =
    document.getElementById("val1");

const val2 =
    document.getElementById("val2");

const val3 =
    document.getElementById("val3");

/*
=========================
HELPER
=========================
*/

function setLoading() {

    val1.innerText = "...";

    val2.innerText = "...";

    val3.innerText = "...";

}

function setSummary(l1, v1, l2, v2, l3, v3) {

    label1.innerText = l1;

    val1.innerText = v1 ?? 0;

    label2.innerText = l2;

    val2.innerText = v2 ?? 0;

    label3.innerText = l3;

    val3.innerText = v3 ?? 0;

}

function resetKegiatanDropdown() {

    pilihKegiatan.innerHTML = `
        <option value="Semua">
            Semua Kegiatan
        </option>
    `;

    pilihKegiatan.disabled = true;

}

async function fetchJson(url) {

    const res =
        await fetch(url, {
            headers: {
                "Accept": "application/json"
            }
        });

    if (!res.ok) {

        throw new Error(`HTTP ${res.status} - ${url}`);

    }

    return await res.json();

}

/*
=========================
DEFAULT SUMMARY
Registrasi Awal
=========================
*/

async function loadDefaultSummary() {

    setLoading();

    resetKegiatanDropdown();

    try {

        const data =
            await fetchJson("/admin/staff/api/summary");

        setSummary(
            "Total Pendaftar",
            data.totalPendaftar,
            "Hadir Registrasi Awal",
            data.hadirRegistrasi,
            "Hadir Kegiatan",
            data.hadirKegiatan
        );

    } catch (err) {

        console.error("loadDefaultSummary error:", err);

        setSummary(
            "Total Pendaftar",
            0,
            "Hadir Registrasi Awal",
            0,
            "Hadir Kegiatan",
            0
        );

    }

}

/*
=========================
LOAD KEGIATAN BY SESI
=========================
*/

async function loadKegiatanBySesi(sesi) {

    resetKegiatanDropdown();

    try {

        const list =
            await fetchJson(
                `/admin/staff/api/kegiatan?sesi=${encodeURIComponent(sesi)}`
            );

        pilihKegiatan.innerHTML = `
            <option value="Semua">
                Semua Kegiatan
            </option>
        `;

        list.forEach(kegiatan => {

            const option =
                document.createElement("option");

            option.value =
                kegiatan;

            option.textContent =
                kegiatan;

            pilihKegiatan.appendChild(option);

        });

        pilihKegiatan.disabled = false;

    } catch (err) {

        console.error("loadKegiatanBySesi error:", err);

        resetKegiatanDropdown();

    }

}

/*
=========================
SUMMARY SESI
=========================
*/

async function loadSummarySesi(sesi) {

    setLoading();

    try {

        const summary =
            await fetchJson(
                `/admin/staff/api/summary-sesi?sesi=${encodeURIComponent(sesi)}`
            );

        const oc =
            await fetchJson(
                `/admin/staff/api/hadir-oc-sesi?sesi=${encodeURIComponent(sesi)}`
            );

        setSummary(
            "Pendaftar Sesi",
            summary.totalPeserta,
            "Hadir Registrasi Awal",
            oc.hadirOC,
            "Hadir Kegiatan",
            summary.hadir
        );

    } catch (err) {

        console.error("loadSummarySesi error:", err);

        setSummary(
            "Pendaftar Sesi",
            0,
            "Hadir Registrasi Awal",
            0,
            "Hadir Kegiatan",
            0
        );

    }

}

/*
=========================
SUMMARY KEGIATAN
=========================
*/

async function loadSummaryKegiatan(nama, sesi) {

    setLoading();

    try {

        const summary =
            await fetchJson(
                `/admin/staff/api/summary-kegiatan?nama=${encodeURIComponent(nama)}&sesi=${encodeURIComponent(sesi)}`
            );

        const oc =
            await fetchJson(
                `/admin/staff/api/hadir-oc-kegiatan?nama=${encodeURIComponent(nama)}&sesi=${encodeURIComponent(sesi)}`
            );

        setSummary(
            "Pendaftar Kegiatan",
            summary.totalPeserta,
            "Hadir Registrasi Awal",
            oc.hadirOC,
            "Hadir Kegiatan",
            summary.hadir
        );

    } catch (err) {

        console.error("loadSummaryKegiatan error:", err);

        setSummary(
            "Pendaftar Kegiatan",
            0,
            "Hadir Registrasi Awal",
            0,
            "Hadir Kegiatan",
            0
        );

    }

}

/*
=========================
EVENT SESI
=========================
*/

pilihSesi.addEventListener("change", async () => {

    const sesi =
        pilihSesi.value;

    if (sesi === "Registrasi Awal") {

        await loadDefaultSummary();

        return;

    }

    await loadKegiatanBySesi(sesi);

    await loadSummarySesi(sesi);

});

/*
=========================
EVENT KEGIATAN
=========================
*/

pilihKegiatan.addEventListener("change", async () => {

    const sesi =
        pilihSesi.value;

    const nama =
        pilihKegiatan.value.trim();

    if (nama === "Semua") {

        await loadSummarySesi(sesi);

        return;

    }

    await loadSummaryKegiatan(nama, sesi);

});

/*
=========================
INIT
=========================
*/

document.addEventListener("DOMContentLoaded", () => {

    loadDefaultSummary();

});