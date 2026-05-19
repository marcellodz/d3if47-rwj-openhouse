console.log("🧩 super content-loader loaded");

async function showData(type, event = null, page = 1) {

    if (event) {
        event.preventDefault();
    }

    const dataSection =
        document.getElementById("data-section");

    if (!dataSection) {
        console.error("data-section tidak ditemukan");
        return;
    }

    dataSection.innerHTML = `
        <h2 style="text-align:center;color:#ccc;">
            <i class="fas fa-spinner fa-spin"></i>
            Memuat data...
        </h2>
    `;

    try {

        const formData =
            new FormData();

        formData.append("page", page);

        const res =
            await fetch(`/admin/super/content?type=${encodeURIComponent(type)}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || ""
                },
                body: formData
            });

        const html =
            await res.text();

        dataSection.innerHTML =
            html;

        executeInjectedScripts(dataSection);

    } catch (err) {

        console.error(err);

        dataSection.innerHTML = `
            <p style="text-align:center;color:#ff6666;">
                Gagal memuat data.
            </p>
        `;
    }
}

function executeInjectedScripts(container) {

    const scripts =
        container.querySelectorAll("script");

    scripts.forEach(oldScript => {

        const newScript =
            document.createElement("script");

        if (oldScript.src) {

            newScript.src =
                oldScript.src;

            newScript.defer =
                oldScript.defer;

        } else {

            newScript.textContent =
                oldScript.textContent;

        }

        document.body.appendChild(newScript);

        oldScript.remove();

    });
}

window.showData = showData;