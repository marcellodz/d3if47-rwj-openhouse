console.log("✅ [ui.js] loaded");

/*
========================================
TOAST CONTAINER
========================================
*/

if (!document.querySelector(".toast-container")) {

    const div =
        document.createElement("div");

    div.className =
        "toast-container";

    document.body.appendChild(div);

}

/*
========================================
TOAST
========================================
*/

window.showToast = function(
    message,
    type = "success"
) {

    const container =
        document.querySelector(".toast-container");

    const toast =
        document.createElement("div");

    toast.className =
        `toast ${type}`;

    let icon =
        "fa-check-circle";

    if (type === "error") {
        icon = "fa-times-circle";
    }

    if (type === "warning") {
        icon = "fa-exclamation-triangle";
    }

    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        ${message}
    `;

    container.appendChild(toast);

    setTimeout(() => {

        toast.classList.add("show");

    }, 50);

    setTimeout(() => {

        toast.classList.remove("show");

        setTimeout(() => {

            toast.remove();

        }, 300);

    }, 4000);

};

/*
========================================
CONFIRM POPUP
========================================
*/

window.showConfirm = function(
    message,
    callback
) {

    const overlay =
        document.createElement("div");

    overlay.className =
        "popup-overlay active";

    overlay.innerHTML = `
        <div class="popup">
            <h3>
                <i class="fas fa-exclamation-triangle"></i>
                Konfirmasi
            </h3>

            <p>${message}</p>

            <div class="popup-buttons">

                <button class="confirm">
                    Ya
                </button>

                <button class="cancel">
                    Batal
                </button>

            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    overlay.querySelector(".confirm")
        .onclick = () => {

            callback(true);

            overlay.remove();

        };

    overlay.querySelector(".cancel")
        .onclick = () => {

            callback(false);

            overlay.remove();

        };

};

/*
========================================
TOPBAR SCROLL EFFECT
========================================
*/

document.addEventListener("scroll", () => {

    const topbar =
        document.getElementById("topbar");

    if (!topbar) return;

    if (window.scrollY > 30) {

        topbar.classList.add("scrolled");

    } else {

        topbar.classList.remove("scrolled");

    }

});