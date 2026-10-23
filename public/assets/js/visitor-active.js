/*
==========================================
Visitor Active Javascript
==========================================
*/

/*
==========================================
Refresh Time
==========================================
*/visitor-active

function updateCurrentTime(){

    const now = new Date();

    console.log(

        "Update :",

        now.toLocaleTimeString("id-ID")

    );

}

setInterval(

    updateCurrentTime,

    60000

);
/*
==========================================
Page Loaded
==========================================
*/

window.addEventListener("load",function(){

    console.log(

        "Visitor Active Loaded"

    );

});