/*
==========================================
SETTING PAGE
(Validasi & penyimpanan sudah ditangani
Laravel di backend)
==========================================
*/

const maintenanceMode = document.getElementById("maintenanceMode");

/*
==========================================
MAINTENANCE MODE (indikator visual saja)
==========================================
*/

if (maintenanceMode) {

    maintenanceMode.addEventListener("change", function () {

        console.log(
            this.checked ? "Maintenance Mode : ON" : "Maintenance Mode : OFF"
        );

    });

}

/*
==========================================
PAGE READY
==========================================
*/

window.addEventListener("load", function () {

    console.log("Setting Page Loaded");

});
