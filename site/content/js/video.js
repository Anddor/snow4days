window.addEventListener("load", function () {
    "use strict";
    if (window.innerWidth > 1300) {
        console.log("hei")
        document.getElementById("mainVideo").setAttribute('autoplay', '');
        document.getElementById("mainVideo").setAttribute("loop", "");
    }
});