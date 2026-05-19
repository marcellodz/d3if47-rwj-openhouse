document.addEventListener("DOMContentLoaded", function(){

const kelasSelect = document.getElementById("kelas_select");

const siswaFields = document.getElementById("siswa_fields");
const guruFields = document.getElementById("guru_fields");

const sekolahWrapper = document.getElementById("sekolah_wrapper");
const kampusWrapper = document.getElementById("kampus_wrapper");
const instansiWrapper = document.getElementById("instansi_wrapper");


function toggleFields(){

const val = kelasSelect.value;


/* ================= SISWA ================= */

const siswaRoles = ["10","11","12","Gap Year"];

if(siswaRoles.includes(val)){
siswaFields.style.display="block";
}else{
siswaFields.style.display="none";
}


/* ================= SEKOLAH ================= */

const sekolahRoles = ["10","11","12","Guru"];

if(sekolahRoles.includes(val)){
sekolahWrapper.style.display="block";
}else{
sekolahWrapper.style.display="none";
}


/* ================= GURU ================= */

if(val === "Guru"){
guruFields.style.display="block";
}else{
guruFields.style.display="none";
}


/* ================= MAHASISWA + FRESH GRAD ================= */

const kampusRoles = ["Mahasiswa","Fresh Graduate"];

if(kampusRoles.includes(val)){
kampusWrapper.style.display="block";
guruFields.style.display="block";
}else{
kampusWrapper.style.display="none";
}


/* ================= INSTANSI ROLES ================= */

const instansiRoles = ["Karyawan","Dosen","Entrepreneur"];

if(instansiRoles.includes(val)){
instansiWrapper.style.display="block";
guruFields.style.display="block";
}else{
instansiWrapper.style.display="none";
}

}

kelasSelect.addEventListener("change",toggleFields);

toggleFields();

});

document.addEventListener("DOMContentLoaded", function(){

const instansiInput = document.getElementById("instansi_asal");
const instansiHidden = document.getElementById("instansi_hidden");

if(!instansiInput) return;

instansiInput.addEventListener("input",function(){

instansiHidden.value = this.value;

});

});