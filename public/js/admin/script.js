// Efek fade di header saat scroll
document.addEventListener("scroll", () => {

    const header =
        document.getElementById("topbar");

    if (!header) return;

    if (window.scrollY > 30) {

        header.classList.add("scrolled");

    } else {

        header.classList.remove("scrolled");

    }

});

// Dropdown profile
const btn =
    document.getElementById("profileBtn");

const dropdown =
    document.getElementById("profileDropdown");

if (btn && dropdown) {

    btn.addEventListener("click", () => {

        dropdown.style.display =
            dropdown.style.display === "block"
                ? "none"
                : "block";

    });

    window.addEventListener("click", (e) => {

        if (
            !btn.contains(e.target)
            &&
            !dropdown.contains(e.target)
        ) {

            dropdown.style.display =
                "none";

        }

    });

}