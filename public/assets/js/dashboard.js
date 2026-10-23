/*
==========================================
Dashboard Javascript
==========================================
*/

const dashboardContent = document.getElementById("dashboardContent");

const pollUrl = dashboardContent.dataset.pollUrl;

const totalVisitorEl = document.getElementById("totalVisitor");

const activeVisitorEl = document.getElementById("activeVisitor");

const todayCheckinEl = document.getElementById("todayCheckin");

const todayCheckoutEl = document.getElementById("todayCheckout");

const visitorTerbaruTable = document.getElementById("visitorTerbaruTable");

/*
==========================================
Render Visitor Terbaru
==========================================
*/

function statusBadge(status) {

    if (status === "Masuk") {
        return '<span class="badge bg-success">Check In</span>';
    }

    return '<span class="badge bg-secondary">Sudah Check Out</span>';

}

function renderVisitorTerbaru(visitors) {

    if (visitors.length === 0) {

        visitorTerbaruTable.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Belum ada data visitor.
                </td>
            </tr>
        `;

        return;

    }

    visitorTerbaruTable.innerHTML = visitors.map(function (visitor) {

        return `
            <tr>
                <td>${visitor.nama}</td>
                <td>${visitor.perusahaan}</td>
                <td>${visitor.tujuan}</td>
                <td>${visitor.check_in}</td>
                <td>${statusBadge(visitor.status)}</td>
            </tr>
        `;

    }).join("");

}

/*
==========================================
Fetch Dashboard Data (Polling)
==========================================
*/

async function fetchDashboardData() {

    try {

        const response = await fetch(pollUrl, {
            headers: { "Accept": "application/json" },
        });

        if (!response.ok) return;

        const result = await response.json();

        totalVisitorEl.textContent = result.total_visitor;

        activeVisitorEl.textContent = result.visitor_aktif;

        todayCheckinEl.textContent = result.visitor_hari_ini;

        todayCheckoutEl.textContent = result.visitor_keluar_hari_ini;

        renderVisitorTerbaru(result.visitor_terbaru);

    } catch (error) {

        console.error("Gagal memuat data dashboard:", error);

    }

}

/*
==========================================
Page Ready
==========================================
*/

window.addEventListener("load", function () {

    fetchDashboardData();

    setInterval(fetchDashboardData, 5000);

});