document.addEventListener("DOMContentLoaded", () => {
    const btn = document.querySelector("[data-mobile-toggle]");
    const menu = document.querySelector("[data-mobile-menu]");
    if (btn && menu) {
        btn.addEventListener("click", () => {
            const isOpen = menu.getAttribute("data-open") === "1";
            menu.setAttribute("data-open", isOpen ? "0" : "1");
        });
    }
});
