console.log("✅ [super/main.js] loaded");

/*
========================================
GET CSRF TOKEN
========================================
*/
function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
}

/*
========================================
SHOW DATA CONTENT
========================================
*/
window.showData = async function(type, event = null) {

    const container =
        document.getElementById("data-section");

    container.innerHTML = `
        <div class="loading">
            ⏳ Memuat data ${type}...
        </div>
    `;

    document.querySelectorAll(".card")
        .forEach(card => {
            card.classList.remove("active");
        });

    if (event?.currentTarget) {
        event.currentTarget.classList.add("active");
    }

    try {

        const response = await fetch(
            `/admin/super/content?type=${type}`,
            {
                method: "POST",

                headers: {
                    "X-CSRF-TOKEN": getCsrfToken(),
                    "X-Requested-With": "XMLHttpRequest"
                }
            }
        );

        const html =
            await response.text();

        container.innerHTML = html;

        /*
        ========================================
        RE-RUN SCRIPTS
        ========================================
        */

        const scripts =
            container.querySelectorAll("script");

        scripts.forEach(oldScript => {

            const newScript =
                document.createElement("script");

            if (oldScript.src) {

                newScript.src =
                    oldScript.src;

            } else {

                newScript.textContent =
                    oldScript.textContent;

            }

            document.body.appendChild(newScript);

        });

    } catch (err) {

        console.error(err);

        showToast(
            `❌ Gagal memuat data ${type}`,
            "error"
        );

    }

};