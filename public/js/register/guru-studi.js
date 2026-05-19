document.addEventListener("DOMContentLoaded", function(){

const jenjang = document.getElementById("prodi_sekarang");
const lanjutWrapper = document.getElementById("lanjut_studi_wrapper");
const lanjutSelect = document.getElementById("jenjang_studi");

const prodiWrapper = document.getElementById("prodi_tujuan_wrapper");
const prodiSelect = document.getElementById("prodi_tujuan");

if(!jenjang) return;

/* ================= JENJANG ================= */

jenjang.addEventListener("change", function(){

    if(this.value){

        lanjutWrapper.style.display = "block";

        // AUTO RELOAD PRODI
        if(lanjutSelect.value === "Ya"){
            loadProdi();
        }

    }else{

        lanjutWrapper.style.display = "none";
        prodiWrapper.style.display = "none";

    }

});

/* ================= MINAT LANJUT ================= */

lanjutSelect.addEventListener("change", function(){

    if(this.value === "Ya"){

        prodiWrapper.style.display = "block";
        loadProdi();

    }else{

        prodiWrapper.style.display = "none";

    }

});

/* ================= LOAD PRODI ================= */

function loadProdi(){

    const jenjangValue = jenjang.value;

    // 🔥 RESET OPTION DULU
    prodiSelect.innerHTML = '<option value="">Memuat Program Studi...</option>';

    fetch('/api/prodi?jenjang=' + encodeURIComponent(jenjangValue))
    .then(res => res.json())
    .then(data => {

        prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';

        data.forEach(prodi => {

            let option = document.createElement("option");

            option.value = prodi;
            option.textContent = prodi;

            prodiSelect.appendChild(option);

        });

    });

}

});