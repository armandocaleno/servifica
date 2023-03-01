function listree() {
    let e = document.getElementsByClassName("listree-submenu-heading");
    Array.from(e).forEach((function (e) {
        if (e.nextElementSibling !== null) {
            e.classList.add("collapsed"), e.nextElementSibling.style.display = "none", e.addEventListener(
                "click", (function (t) {
                    // t.preventDefault();
                    const n = t.target.nextElementSibling;
                    if (n !== null) {


                        "none" == n.style.display ? (e.classList.remove("collapsed"), e.classList.add(
                            "expanded"), n.style.display = "block") : (e.classList.remove("expanded"), e
                                .classList.add("collapsed"), n.style.display = "none"), t.stopPropagation()
                    }
                }))
        }
    }))
}