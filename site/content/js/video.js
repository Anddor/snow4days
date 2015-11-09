window.addEventListener("load", function () {
    "use strict";
    if (window.innerWidth < 1300) {
        console.log(window.innerWidth)
        document.getElementById("videoWrapper").removeChild(document.getElementById("mainVideo"));
    }
});