(function (window, document, undefined) {
    "use strict";
    function init() {
        function restart() {
            var vid = document.getElementById("mainVideo");
            vid.currentTime = 0;
            vid.play();
        }


        var vid = document.getElementById("mainVideo");
        vid.addEventListener('ended', restart, false);


    }
    window.onload = init;

 
})(window, document, undefined);