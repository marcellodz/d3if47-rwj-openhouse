// pagination_kegiatan.js
console.log("✅ [pagination_kegiatan.js] loaded");

(function () {

  function renderKegiatanPagination() {
    // pastikan ada objek pagination (data diisi dari PHP window._kegiatanPesertaData)
    const pag = window._kpPagination || { data: [], page: 1, limit: 15 };

    const tbody     = document.getElementById("kegiatan-body");
    const container = document.getElementById("kegiatan-pagination");

    if (!tbody || !container) {
      console.warn("[pagination_kegiatan] tbody/container not found");
      return;
    }

    const data  = Array.isArray(pag.data) ? pag.data : [];
    const limit = parseInt(pag.limit, 10) || 15;
    const total = data.length;

    const totalPage = Math.max(1, Math.ceil(total / limit));

    // pastikan page valid
    pag.page = Math.min(Math.max(1, parseInt(pag.page || 1, 10)), totalPage);

    // Slice data untuk page sekarang
    const start = (pag.page - 1) * limit;
    const slice = data.slice(start, start + limit);

    // Render rows
    tbody.innerHTML = slice.length
      ? slice.map(d => `
        <tr>
          <td>${d.id_kegiatan ?? "-"}</td>
          <td>${d.iduser ?? "-"}</td>
          <td>${(d["Nama Peserta"] ?? "-")}</td>
          <td>${(d["Nama Kegiatan"] ?? "-")}</td>
          <td>${(d["Waktu Kegiatan"] ?? "-")}</td>
        </tr>
      `).join("")
      : `<tr><td colspan="5" style="text-align:center;color:#888;">Tidak ada data.</td></tr>`;

    // Render pagination buttons (LOCAL, tidak mengubah URL / tidak fetch)
    if (totalPage <= 1) {
      container.innerHTML = "";
      return;
    }

    let html = "";

// First
html += `
<button class="kp-page-btn"
    data-page="1"
    ${pag.page === 1 ? "disabled" : ""}>
«
</button>`;

// Prev
html += `
<button class="kp-page-btn"
    data-page="${Math.max(1,pag.page-1)}"
    ${pag.page === 1 ? "disabled" : ""}>
‹
</button>`;

function addPage(num){
    html += `
    <button
        class="kp-page-btn ${num===pag.page?"active":""}"
        data-page="${num}">
        ${num}
    </button>`;
}

if(totalPage <= 5){

    for(let i=1;i<=totalPage;i++){
        addPage(i);
    }

}else{

    addPage(1);

    if(pag.page > 4){
        html += `<span class="kp-dots">...</span>`;
    }

    let start = Math.max(2,pag.page-1);
    let end   = Math.min(totalPage-1,pag.page+1);

    if(pag.page <=3){
        start=2;
        end=4;
    }

    if(pag.page >= totalPage-2){
        start=totalPage-3;
        end=totalPage-1;
    }

    for(let i=start;i<=end;i++){
        addPage(i);
    }

    if(pag.page < totalPage-3){
        html += `<span class="kp-dots">...</span>`;
    }

    addPage(totalPage);
}

// Next
html += `
<button class="kp-page-btn"
    data-page="${Math.min(totalPage,pag.page+1)}"
    ${pag.page===totalPage?"disabled":""}>
›
</button>`;

// Last
html += `
<button class="kp-page-btn"
    data-page="${totalPage}"
    ${pag.page===totalPage?"disabled":""}>
»
</button>`;
    container.innerHTML = html;

    // Attach local listeners (only for kp-page-btn)
    container.querySelectorAll(".kp-page-btn").forEach(btn => {
      btn.onclick = (ev) => {
        ev.stopPropagation();             // penting: jangan biarkan global handler tangkap click
        const newPage = parseInt(btn.dataset.page, 10) || 1;
        pag.page = newPage;
        window._kpPagination = pag;
        renderKegiatanPagination();

        // opsi scroll ke tabel
        const topEl = document.getElementById("kegiatan-body");
        if (topEl) topEl.scrollIntoView({ behavior: "smooth", block: "start" });
      };
    });
  }

  // expose global function supaya filter_kegiatan.js bisa panggil render ulang
  window.renderKegiatanPagination = renderKegiatanPagination;

  // init global pagination data (diisi oleh PHP di super_content.php)
  window._kpPagination = window._kpPagination || {
    data: window._kegiatanPesertaData || [],
    page: 1,
    limit: 15
  };

  // auto render on load
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", renderKegiatanPagination);
  } else {
    renderKegiatanPagination();
  }

})();
