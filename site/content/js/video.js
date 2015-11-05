window.addEventListener("load",function() {
    
    var vid = document.getElementById("mainVideo");
    vid.currentTime = 0;
    vid.play();
    
    vid.addEventListener("ended", function () {
        vid.currentTime = 0;
        vid.play();
    });
});