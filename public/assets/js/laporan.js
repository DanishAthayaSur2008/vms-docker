/*
==========================================
REPORT PAGE
==========================================
*/

const searchInput = document.getElementById("searchReport");

const reportTable = document.getElementById("reportTable");

const exportPdf = document.getElementById("exportPdf");
/*
==========================================
SEARCH REPORT
==========================================
*/

if(searchInput){

    searchInput.addEventListener("keyup",function(){

        const keyword = this.value.toLowerCase();

        const rows = reportTable.querySelectorAll("tr");

        rows.forEach(function(row){

            const text = row.innerText.toLowerCase();

            if(text.includes(keyword)){

                row.style.display = "";

            }

            else{

                row.style.display = "none";

            }

        });

    });

}
/*
==========================================
EXPORT PDF (Print)
==========================================
*/

if(exportPdf){

    exportPdf.addEventListener("click",function(){

        window.print();

    });

}
/*
==========================================
PAGE READY
==========================================
*/

window.addEventListener("load",function(){

    console.log(

        "Report Page Loaded"

    );

});
