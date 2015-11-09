window.addEventListener("load", function () {
    "use strict";
    if (window.innerWidth < 1300) {
        document.getElementById("videoWrapper").removeChild(document.getElementById("mainVideo"));
    }
});