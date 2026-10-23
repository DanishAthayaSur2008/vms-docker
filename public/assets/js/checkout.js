/*
==========================================
DC Visitor
Checkout Javascript
==========================================
*/

const searchForm = document.getElementById("searchForm");

const searchPhone = document.getElementById("searchPhone");

const searchButton = document.getElementById("searchButton");

const visitorCard = document.getElementById("visitorCard");

const notFoundAlert = document.getElementById("notFoundAlert");

const visitorId = document.getElementById("visitorId");

const visitorPhoto = document.getElementById("visitorPhoto");

const visitorName = document.getElementById("visitorName");

const visitorPhone = document.getElementById("visitorPhone");

const visitorEmail = document.getElementById("visitorEmail");

const visitorCompany = document.getElementById("visitorCompany");

const visitorPurpose = document.getElementById("visitorPurpose");

const visitorLantai = document.getElementById("visitorLantai");

const visitorRak = document.getElementById("visitorRak");

const visitorCheckIn = document.getElementById("visitorCheckIn");

const confirmCheckoutButton = document.getElementById("confirmCheckoutButton");

const backHomeButton = document.getElementById("backHomeButton");

/*
==========================================
Config (URL & CSRF Token dari Blade)
==========================================
*/

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const searchUrl = searchForm.dataset.searchUrl;

const checkoutUrl = visitorCard.dataset.checkoutUrl;

const homeUrl = backHomeButton.dataset.homeUrl;

/*
==========================================
Search Visitor
==========================================
*/

searchForm.addEventListener("submit", async function (e) {

    e.preventDefault();

    visitorCard.classList.add("d-none");

    notFoundAlert.classList.add("d-none");

    const phone = searchPhone.value.trim();

    if (phone === "") {

        alert("Masukkan Nomor HP.");

        return;

    }

    searchButton.disabled = true;

    searchButton.innerHTML = `

        <span class="spinner-border spinner-border-sm"></span>

        Mencari...

    `;

    try {

        const response = await fetch(searchUrl, {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },

            body: JSON.stringify({
                no_hp: phone
            })

        });

        const result = await response.json();

        if (response.ok && result.success) {

            const visitor = result.data;

            visitorId.value = visitor.id;

            visitorPhoto.src = visitor.foto;

            visitorName.textContent = visitor.nama;

            visitorPhone.textContent = visitor.no_hp;

            visitorEmail.textContent = visitor.email || "-";

            visitorCompany.textContent = visitor.perusahaan;

            visitorPurpose.textContent = visitor.tujuan;

            visitorLantai.textContent = visitor.lantai || "-";

            visitorRak.textContent = visitor.nomor_rak || "-";

            visitorCheckIn.textContent = visitor.waktu_masuk;

            visitorCard.classList.remove("d-none");

        } else {

            notFoundAlert.classList.remove("d-none");

        }

    } catch (error) {

        console.error(error);

        notFoundAlert.classList.remove("d-none");

    }

    searchButton.disabled = false;

    searchButton.innerHTML = `

        <i class="bi bi-search"></i>

        <span>

            Cari Visitor

        </span>

    `;

});
/*
==========================================
Confirm Checkout
==========================================
*/

confirmCheckoutButton.addEventListener("click", async function () {

    if (!visitorId.value) {

        return;

    }

    confirmCheckoutButton.disabled = true;

    confirmCheckoutButton.innerHTML = `

        <span class="spinner-border spinner-border-sm"></span>

        Memproses...

    `;

    try {

        const response = await fetch(checkoutUrl, {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },

            body: JSON.stringify({
                id: visitorId.value
            })

        });

        const result = await response.json();

        const confirmModal = bootstrap.Modal.getInstance(

            document.getElementById("confirmCheckoutModal")

        );

        confirmModal.hide();

        if (response.ok && result.success) {

            const successModal = new bootstrap.Modal(

                document.getElementById("successModal")

            );

            successModal.show();

        } else {

            alert(result.message || "Checkout gagal, silakan coba lagi.");

        }

    } catch (error) {

        console.error(error);

        alert("Terjadi kesalahan, silakan coba lagi.");

    }

    confirmCheckoutButton.disabled = false;

    confirmCheckoutButton.innerHTML = `

        Ya, Check Out

    `;

});

/*
==========================================
Back Home
==========================================
*/

backHomeButton.addEventListener("click", function () {

    window.location.href = homeUrl;

});

/*
==========================================
Only Number Input
==========================================
*/

searchPhone.addEventListener("input", function () {

    this.value = this.value.replace(/[^0-9]/g, "");

});

/*
==========================================
Enter Key Support
==========================================
*/

searchPhone.addEventListener("keypress", function (e) {

    if (e.key === "Enter") {

        searchForm.requestSubmit();

    }

});