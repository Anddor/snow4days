window.addEventListener("load", function () {
    "use strict";
    if (window.innerWidth < 1300) {
        document.getElementById("front").removeChild(document.getElementById("mainVideo"));
    }
});