/*
    FILE NAME: video.js
    WRITTEN BY:  Marius Aaesnes
    WHEN:  October 2015
    PURPOSE:  If the user is using a screen that is smaller than 1300 (this is just a value i chose, and can be changed) the video is removed, so that it doesn't play on phones, and the background picture is showing instead (it is behind the video)
*/
window.addEventListener("load", function () {     "use strict";     if (window.innerWidth < 1300) {         document.getElementById("front").removeChild(document.getElementById("mainVideo"));     } });