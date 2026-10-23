/*
==========================================
DETAIL VISITOR
==========================================
*/

const backButton = document.querySelector(".btn-secondary");
/*
==========================================
BACK BUTTON
==========================================
*/

if(backButton){

    backButton.addEventListener("click",function(){

        console.log(

            "Kembali ke halaman Data Visitor"

        );

    });

}
/*
==========================================
PAGE LOADED
==========================================
*/

window.addEventListener("load",function(){

    console.log(

        "Detail Visitor Loaded"

    );

});
/*
==========================================
SIMULASI DATA
==========================================
*/

const visitorStatus = document.querySelector(".badge");

if(visitorStatus){

    console.log(

        "Status Visitor :",

        visitorStatus.textContent

    );

}
