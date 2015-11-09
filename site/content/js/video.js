window.addEventListener("load", function () {
    "use strict";
    if (screen.innerWidth < 1300) {
        document.getElementById("mainVideo").removeAttribute("autoplay");
    }
});