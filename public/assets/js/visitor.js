/*
==========================================
Visitor Javascript
==========================================
*/

const searchInput = document.getElementById("searchVisitor");

const statusFilter = document.getElementById("filterStatus");

const dateFilter = document.getElementById("filterDate");

const searchButton = document.getElementById("searchButton");

const tableBody = document.getElementById("visitorTable");

const visitorCountText = document.getElementById("visitorCountText");

const pollUrl = tableBody.dataset.pollUrl;

/*
==========================================
State
==========================================
*/

let currentVisitors = [];

/*
==========================================
Render Table Rows
==========================================
*/

function statusBadge(status) {

    if (status === "Masuk") {
        return '<span class="badge bg-success">Check In</span>';
    }

    return '<span class="badge bg-secondary">Sudah Check Out</span>';

}

function renderRows(visitors) {

    if (visitors.length === 0) {

        tableBody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center text-muted py-4">
                    Belum ada data visitor.
                </td>
            </tr>
        `;

        if (visitorCountText) {
            visitorCountText.textContent = "Menampilkan 0 data visitor";
        }

        return;

    }

    tableBody.innerHTML = visitors.map(function (visitor, index) {

        const blacklistBadge = visitor.blacklisted
            ? '<span class="badge bg-danger">Blacklist</span>'
            : '';

        const blacklistButton = !visitor.blacklisted
            ? `
                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#blacklistModal"
                    data-visitor-id="${visitor.id}"
                    data-visitor-nama="${visitor.nama}">

                    <i class="bi bi-slash-circle"></i>

                </button>
            `
            : `
                <button
                    type="button"
                    class="btn btn-outline-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#unblacklistModal"
                    data-visitor-id="${visitor.id}"
                    data-visitor-nama="${visitor.nama}"
                    title="Hapus dari blacklist">

                    <i class="bi bi-unlock-fill"></i>

                </button>
            `;

        return `
            <tr>
                <td>${index + 1}</td>
                <td>${visitor.nama}</td>
                <td><span class="fw-semibold" style="font-family:ui-monospace,Menlo,Consolas,monospace;letter-spacing:.08em;">${visitor.visitor_id || '-'}</span></td>
                <td>${visitor.email}</td>
                <td>${visitor.no_hp}</td>
                <td>${visitor.perusahaan}</td>
                <td>${visitor.tujuan}</td>
                <td>${visitor.lantai}</td>
                <td>${visitor.nomor_rak}</td>
                <td>${visitor.check_in}</td>
                <td>${statusBadge(visitor.status)} ${blacklistBadge}</td>
                <td>
                    <div class="d-flex justify-content-center align-items-center flex-nowrap gap-2">
                        <a href="${visitor.detail_url}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                        ${blacklistButton}
                    </div>
                </td>
            </tr>
        `;

    }).join("");

    if (visitorCountText) {
        visitorCountText.textContent =
            `Menampilkan 1 - ${visitors.length} dari ${visitors.length} data visitor`;
    }

}

/*
==========================================
Apply Search + Status Filter
==========================================
*/

function applyFilters() {

    const keyword = searchInput.value.toLowerCase();

    const statusValue = statusFilter.value.toLowerCase();

    let filtered = currentVisitors;

    if (keyword !== "") {

        filtered = filtered.filter(function (visitor) {

            const haystack = [
                visitor.nama,
                visitor.visitor_id,
                visitor.email,
                visitor.no_hp,
                visitor.perusahaan,
                visitor.tujuan,
            ].join(" ").toLowerCase();

            return haystack.includes(keyword);

        });

    }

    if (dateFilter && dateFilter.value !== "") {

        filtered = filtered.filter(function (visitor) {

            return visitor.tanggal === dateFilter.value;

        });

    }

    if (statusValue !== "") {

        filtered = filtered.filter(function (visitor) {

            if (statusValue === "blacklist") {
                return visitor.blacklisted;
            }

            const statusLabel = visitor.status === "Masuk"
                ? "check in"
                : "sudah check out";

            return statusLabel.includes(statusValue);

        });

    }

    renderRows(filtered);

}

searchInput.addEventListener("keyup", applyFilters);

statusFilter.addEventListener("change", applyFilters);

if (dateFilter) {
    dateFilter.addEventListener("change", applyFilters);
}

if (searchButton) {
    searchButton.addEventListener("click", applyFilters);
}

searchInput.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        applyFilters();
    }
});

/*
==========================================
Fetch Visitors (Polling)
==========================================
*/

async function fetchVisitors() {

    try {

        const response = await fetch(pollUrl, {
            headers: { "Accept": "application/json" },
        });

        if (!response.ok) return;

        const result = await response.json();

        currentVisitors = result.visitors;

        applyFilters();

    } catch (error) {

        console.error("Gagal memuat data visitor:", error);

    }

}

/*
==========================================
Page Ready
==========================================
*/

window.addEventListener("load", function () {

    fetchVisitors();

    setInterval(fetchVisitors, 5000);

});