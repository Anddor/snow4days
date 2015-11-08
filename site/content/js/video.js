window.addEventListener("load", function () {
    
    var myVideo = document.getElementById('mainVideo'),
        isChrome = window.chrome,
        vendorName = window.navigator.vendor;
    myVideo.play();
    
    myVideo.addEventListener("ended", function () {
        /*
            currentTime fungerer ikke skikkelig i chrome (fuck chrome).
            Må derfor finne ut om brukeren bruker chrome og i det tilfelle bruke load()-funksjonen
            bruker ikke load()-funksjonen på de andre fordi det fører til at bakgrunnen blir hvit i en kort stund. (siden videoen lastes inn på nytt, isteden for å bare startes på nytt)
        */
        if (isChrome !== null && isChrome !== undefined && vendorName === "Google Inc.") {
            console.log("bruker chrome")
            myVideo.load();
        } else {
            console.log("bruker ikke chrome")
            myVideo.currentTime = 0;
        }
        myVideo.play();
    });
});

