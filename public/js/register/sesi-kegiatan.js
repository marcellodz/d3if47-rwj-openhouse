const sesiCards = document.querySelectorAll(".sesi-card");

const kegiatanContainer = document.getElementById("kegiatan_container");

const ikutTourSelect = document.getElementById("ikut_tour");
const campusTourWrapper = document.getElementById("campus_tour_wrapper");
const campusTourSelect = document.getElementById("campus_tour_sesi");

const teluExploreSelect = document.getElementById("telu_explore");
const teluExploreInfo = document.getElementById("telu_explore_info");

let kegiatanData = {};
let sesiDipilih = {};
let pilihanUser = {};


// ===============================
// DATA RESMI EVENT (LOKASI + WAKTU)
// ===============================

const kegiatanMap = {
    "Seminar Fakultas Informatika": "Gedung Telkom University Landmark Tower Lantai 2 - Pukul 09.15 - 10.45 WIB",
    "Seminar Fakultas Teknik Elektro": "Gedung Telkom University Landmark Tower Lantai 16 - Pukul 09.15 - 10.45 WIB",
    "Trial Class 1: Future Preneur - Siap Jadi Pebisnis di Era AI": "Gedung Telkom University Landmark Tower Lantai 16 - Pukul 09.15 - 10.45 WIB",
    "Trial Class 2: Decision Making Under Pressure - Jadi Manajer Sehari!": "Gedung Telkom University LT 16 - Pukul 09.15 - 10.45 WIB",
    "Trial Class 3: The Power of Empathy - Seni Memahami Perasaan Orang Lain": "Gedung Telkom University LT 18 - Pukul 09.15 - 10.45 WIB",
    "Seminar Parent": "Gedung Telkom University LT 1 - Pukul 10.00 - 13.00 WIB",

    "Seminar Fakultas Rekayasa Industri": "Gedung Telkom University LT 2 - Pukul 10.35 - 12.10 WIB",
    "Seminar Fakultas Ilmu Terapan": "Gedung Telkom University LT 16 - Pukul 10.35 - 12.10 WIB",
    "Trial Class 1: Media, Mitos, dan Manipulasi - Siapa yang Mengendalikan Narasi?": "Gedung Telkom University LT 16 - Pukul 10.35 - 12.10 WIB",
    "Trial Class 2: Smart Health Revolution - Ketika Teknologi Bertemu Tubuh Manusia": "Gedung Telkom University LT 16 - Pukul 10.35 - 12.10 WIB",
    "Trial Class 3: Data Sains": "Gedung Telkom University LT 16 - Pukul 10.35 - 12.10 WIB",
    "Seminar Double Degree Program": "Gedung Telkom University LT 18 - Pukul 10.35 - 12.10 WIB",

    "Seminar Fakultas Ekonomi Bisnis": "Gedung Telkom University LT 2 - Pukul 12.00 - 13.35 WIB",
    "Seminar Fakultas Industri Kreatif": "Gedung Telkom University LT 16 - Pukul 12.00 - 13.35 WIB",
    "Trial Class 1: AI dan Revolusi Sinema - Ketika Mesin Ikut Berkarya": "Gedung Telkom University LT 16 - Pukul 12.00 - 13.35 WIB",
    "Trial Class 2: Robot Mini Challenge - Kendalikan Dunia dengan Kode!": "Gedung Telkom University LT 16 - Pukul 12.00 - 13.35 WIB",
    "Trial Class 3: Tech Meets Business - Membangun Startup Digital dari Nol": "Gedung Telkom University LT 16 - Pukul 12.00 - 13.35 WIB",

    "Seminar Fakultas Komunikasi dan Ilmu Sosial": "Gedung Telkom University LT 16 - Pukul 13.25 - 15.00 WIB",
    "Trial Class 1: From Human to Machine - Membangun AI yang Bisa Berpikir": "Gedung Telkom University LT 16 - Pukul 13.25 - 15.00 WIB",
    "Trial Class 2: Leisure Leadership - Managing People, Places, and Emotions": "Gedung Telkom University LT 16 - Pukul 13.25 - 15.00 WIB",
    "Trial Class 3: Build Your Own Logistics Startup - Inovasi di Dunia Pengiriman": "Gedung Telkom University LT 18 - Pukul 13.25 - 15.00 WIB",
    "Seminar Minat Bakat": "Gedung Telkom University LT 16 - Pukul 13.25 - 15.00 WIB"
};


// ===============================
// GROUP KEGIATAN PER SESI
// ===============================

const kegiatanBySesi = {
    1: [
        "Seminar Fakultas Informatika",
        "Seminar Fakultas Teknik Elektro",
        "Trial Class 1: Future Preneur - Siap Jadi Pebisnis di Era AI",
        "Trial Class 2: Decision Making Under Pressure - Jadi Manajer Sehari!",
        "Trial Class 3: The Power of Empathy - Seni Memahami Perasaan Orang Lain",
        "Seminar Parent"
    ],
    2: [
        "Seminar Fakultas Rekayasa Industri",
        "Seminar Fakultas Ilmu Terapan",
        "Trial Class 1: Media, Mitos, dan Manipulasi - Siapa yang Mengendalikan Narasi?",
        "Trial Class 2: Smart Health Revolution - Ketika Teknologi Bertemu Tubuh Manusia",
        "Trial Class 3: Data Sains",
        "Seminar Double Degree Program"
    ],
    3: [
        "Seminar Fakultas Ekonomi Bisnis",
        "Seminar Fakultas Industri Kreatif",
        "Trial Class 1: AI dan Revolusi Sinema - Ketika Mesin Ikut Berkarya",
        "Trial Class 2: Robot Mini Challenge - Kendalikan Dunia dengan Kode!",
        "Trial Class 3: Tech Meets Business - Membangun Startup Digital dari Nol"
    ],
    4: [
        "Seminar Fakultas Komunikasi dan Ilmu Sosial",
        "Trial Class 1: From Human to Machine - Membangun AI yang Bisa Berpikir",
        "Trial Class 2: Leisure Leadership - Managing People, Places, and Emotions",
        "Trial Class 3: Build Your Own Logistics Startup - Inovasi di Dunia Pengiriman",
        "Seminar Minat Bakat"
    ]
};


// ===============================
// API LIMIT
// ===============================

fetch("/api/kegiatan")
.then(res => res.json())
.then(data => {
    kegiatanData = data;
})
.catch(err => {
    console.error("API kegiatan error:", err);
});


// ===============================
// CLICK SESI
// ===============================

sesiCards.forEach(card => {
    card.addEventListener("click", function () {
        let sesi = this.dataset.sesi;

        if (sesi === "none") {
            sesiDipilih = {};
            pilihanUser = {};

            sesiCards.forEach(c => c.classList.remove("active"));
            this.classList.add("active");

            kegiatanContainer.innerHTML = "";
            kegiatanContainer.style.display = "none";
            return;
        }

        let noneCard = document.querySelector('.sesi-card[data-sesi="none"]');
        if (noneCard) noneCard.classList.remove("active");

        if (sesiDipilih[sesi]) {
            delete sesiDipilih[sesi];
            delete pilihanUser[sesi];
            this.classList.remove("active");
        } else {
            sesiDipilih[sesi] = true;
            this.classList.add("active");
        }

        renderKegiatan(sesi);
    });
});



// ===============================
// RENDER KEGIATAN
// ===============================

function renderKegiatan(sesiBaru = null) {
    kegiatanContainer.innerHTML = "";

    let sesiUrut = Object.keys(sesiDipilih).sort((a, b) => a - b);

    if (sesiUrut.length === 0) {
        kegiatanContainer.style.display = "none";
        return;
    }

    kegiatanContainer.style.display = "block";

    sesiUrut.forEach(sesi => {
        let wrapper = document.createElement("div");
        wrapper.className = "form-group";
        wrapper.id = "sesi_dropdown_" + sesi;

        let label = document.createElement("label");
        label.innerText = "SESI " + sesi;

        let select = document.createElement("select");
        select.name = "kegiatan[]";
        select.className = "form-control";

        select.innerHTML = `<option value="">Pilih kegiatan...</option>`;

        let list = kegiatanBySesi[sesi] || [];

        list.forEach(nama => {
            let data = kegiatanData[nama];
            let text = nama;
            let disabled = "";

            if (data) {
                text = `${nama} (${data.total_pendaftar}/${data.limit_total})`;

                if (data.status === "penuh") {
                    text = `${nama} (PENUH)`;
                    disabled = "disabled";
                }
            }

            let infoLengkap = kegiatanMap[nama] || "-";
            let valueOption = `${infoLengkap}|${nama}`;
            let selected = pilihanUser[sesi] === valueOption ? "selected" : "";

            select.innerHTML += `
<option value="${valueOption}" ${selected} ${disabled}>
${text}
</option>
`;
        });

        select.addEventListener("change", function () {
            pilihanUser[sesi] = this.value;
        });

        wrapper.appendChild(label);
        wrapper.appendChild(select);
        kegiatanContainer.appendChild(wrapper);
    });

    if (sesiBaru) {
        let el = document.getElementById("sesi_dropdown_" + sesiBaru);
        if (el) {
            setTimeout(() => {
                el.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
            }, 200);
        }
    }
}


// ===============================
// CAMPUS TOUR
// ===============================

if (ikutTourSelect) {
    ikutTourSelect.addEventListener("change", function () {

        if (this.value === "Ya") {

            campusTourWrapper.style.display = "block";
            campusTourSelect.innerHTML = `<option value="">Pilih sesi campus tour</option>`;

            const campusTourMap = {
                "Sesi 1": "Gedung Telkom University LT 1 - Pukul 10.45 - 11.15 WIB",
                "Sesi 2": "Gedung Telkom University LT 1 - Pukul 11.15 - 11.45 WIB",
                "Sesi OTS": ""
            };

            Object.keys(campusTourMap).forEach(nama => {
                let infoLengkap = campusTourMap[nama];

                campusTourSelect.innerHTML += `
<option value="${infoLengkap}|${nama}">
${nama}
</option>
`;
            });

        } else {
            campusTourWrapper.style.display = "none";
            campusTourSelect.innerHTML = `<option value="">Pilih sesi campus tour</option>`;
        }

    });
}


// ===============================
// TEL-U EXPLORE
// ===============================

if (teluExploreSelect) {
    teluExploreSelect.addEventListener("change", function () {
        if (this.value === "Ya") {
            teluExploreInfo.style.display = "flex";
        } else {
            teluExploreInfo.style.display = "none";
        }
    });
}