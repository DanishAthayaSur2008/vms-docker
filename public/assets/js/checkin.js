/*
==========================================
DC Visitor
Check In Javascript (Laravel)
==========================================
*/

const form = document.getElementById("checkinForm");

const visitorName = document.getElementById("visitorName");
const visitorPhone = document.getElementById("visitorPhone");
const visitorEmail = document.getElementById("visitorEmail");
const visitorCompany = document.getElementById("visitorCompany");
const visitorPurpose = document.getElementById("visitorPurpose");
const visitorLantai = document.getElementById("visitorLantai");
const visitorRak = document.getElementById("visitorRak");

const previousDataAlert = document.getElementById("previousDataAlert");
const previousPhotoHint = document.getElementById("previousPhotoHint");
const previousPhotoImg = document.getElementById("previousPhotoImg");

const cameraButton = document.getElementById("cameraButton");
const captureButton = document.getElementById("captureButton");
const retakeButton = document.getElementById("retakeButton");
const selfieInput = document.getElementById("selfieInput");
const cameraStream = document.getElementById("cameraStream");
const cameraCanvas = document.getElementById("cameraCanvas");
const cameraError = document.getElementById("cameraError");

const previewImage = document.getElementById("previewImage");
const photoPlaceholder = document.getElementById("photoPlaceholder");

const submitButton = document.getElementById("submitButton");
const resetButton = document.getElementById("resetButton");

const confirmSubmit = document.getElementById("confirmSubmit");

const confirmModal = new bootstrap.Modal(
    document.getElementById("confirmModal")
);

let photoUploaded = false;
let mediaStream = null;

// ==============================
// FOTO SELFIE (Kamera Live)
// ==============================

function showError(message) {

    cameraError.textContent = message;
    cameraError.classList.remove("d-none");

}

function stopCamera() {

    if (mediaStream) {

        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;

    }

    cameraStream.classList.add("d-none");

}

async function startCamera() {

    cameraError.classList.add("d-none");

    try {

        mediaStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "user" },
            audio: false
        });

        cameraStream.srcObject = mediaStream;

        cameraStream.classList.remove("d-none");
        previewImage.classList.add("d-none");
        photoPlaceholder.classList.add("d-none");

        cameraButton.classList.add("d-none");
        captureButton.classList.remove("d-none");
        retakeButton.classList.add("d-none");

    } catch (error) {
 
        console.error(error);

        showError(
            "Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan di browser."
        );

    }

}

cameraButton.addEventListener("click", startCamera);

captureButton.addEventListener("click", function () {

    const videoWidth = cameraStream.videoWidth;
    const videoHeight = cameraStream.videoHeight;

    if (!videoWidth || !videoHeight) {
        showError("Kamera belum siap. Tunggu sebentar lalu coba lagi.");
        return;
    }

    cameraCanvas.width = videoWidth;
    cameraCanvas.height = videoHeight;

    const ctx = cameraCanvas.getContext("2d");

    // Mirror seperti kamera selfie HP
    ctx.save();

    ctx.translate(videoWidth, 0);
    ctx.scale(-1, 1);

    ctx.drawImage(
        cameraStream,
        0,
        0,
        videoWidth,
        videoHeight
    );

    ctx.restore();

    cameraCanvas.toBlob(function (blob) {

        if (!blob) {
            showError("Gagal mengambil foto.");
            return;
        }

        const file = new File(
            [blob],
            "selfie-" + Date.now() + ".jpg",
            {
                type: "image/jpeg"
            }
        );

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        selfieInput.files = dataTransfer.files;

        const imageUrl = URL.createObjectURL(blob);

        previewImage.src = imageUrl;
        previewImage.classList.remove("d-none");
        photoPlaceholder.classList.add("d-none");

        stopCamera();

        captureButton.classList.add("d-none");
        retakeButton.classList.remove("d-none");

        photoUploaded = true;

    }, "image/jpeg", 0.9);

});

retakeButton.addEventListener("click", function () {

    previewImage.classList.add("d-none");
    photoUploaded = false;
    selfieInput.value = "";

    startCamera();

});
selfieInput.addEventListener("change", function () {

    const file = this.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (e) {

        previewImage.src = e.target.result;

        previewImage.classList.remove("d-none");
        photoPlaceholder.classList.add("d-none");
        cameraStream.classList.add("d-none");

        cameraButton.classList.add("d-none");
        captureButton.classList.add("d-none");
        retakeButton.classList.remove("d-none");

        photoUploaded = true;

    };

    reader.readAsDataURL(file);

});

// ==============================
// NOMOR HP
// ==============================

const lookupUrl = form.dataset.lookupUrl;

visitorPhone.addEventListener("input", function () {

    this.value = this.value.replace(/[^0-9]/g, "");

    this.classList.toggle("is-valid", this.value.length >= 10);

});

visitorPhone.addEventListener("blur", async function () {

    if (!lookupUrl || this.value.length < 10) {
        return;
    }

    try {

        const response = await fetch(
            lookupUrl + "?no_hp=" + encodeURIComponent(this.value)
        );

        const result = await response.json();

        if (!result.success) {
            return;
        }

        const data = result.data;

        if (data.nama) {
            visitorName.value = data.nama;
            visitorName.classList.toggle("is-valid", true);
        }

        if (data.email) {
            visitorEmail.value = data.email;
            visitorEmail.classList.toggle("is-valid", true);
        }

        if (data.perusahaan) {
            visitorCompany.value = data.perusahaan;
            visitorCompany.classList.toggle("is-valid", true);
        }

        if (data.lantai) {
            visitorLantai.value = data.lantai;
        }

        if (data.nomor_rak) {
            visitorRak.value = data.nomor_rak;
            visitorRak.classList.toggle("is-valid", true);
        }

        previousDataAlert.classList.remove("d-none");

        if (data.foto) {
            previousPhotoImg.src = data.foto;
            previousPhotoHint.classList.remove("d-none");
        } else {
            previousPhotoHint.classList.add("d-none");
        }

    } catch (error) {

        console.error(error);

    }

});

// ==============================
// NOMOR RAK (angka saja)
// ==============================

visitorRak.addEventListener("input", function () {

    this.value = this.value.replace(/[^0-9]/g, "");

    this.classList.toggle("is-valid", this.value.trim() !== "");

});

// ==============================
// VALIDASI
// ==============================

function validateForm() {

    if (visitorName.value.trim().length < 3) {

        alert("Nama minimal 3 karakter.");
        visitorName.focus();
        return false;

    }

    if (visitorPhone.value.length < 10) {

        alert("Nomor HP tidak valid.");
        visitorPhone.focus();
        return false;

    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(visitorEmail.value)) {

        alert("Email tidak valid.");
        visitorEmail.focus();
        return false;

    }

    if (visitorCompany.value.trim() === "") {

        alert("Perusahaan wajib diisi.");
        visitorCompany.focus();
        return false;

    }

    if (visitorPurpose.value === "") {

        alert("Pilih tujuan kunjungan.");
        visitorPurpose.focus();
        return false;

    }

    if (visitorLantai.value === "") {

        alert("Pilih lantai terlebih dahulu.");
        visitorLantai.focus();
        return false;

    }

    if (visitorRak.value.trim().length < 1) {

        alert("Nomor rak wajib diisi (angka).");
        visitorRak.focus();
        return false;

    }

    if (!photoUploaded) {

        alert("Silakan ambil foto selfie.");
        return false;

    }

    return true;

}

// ==============================
// SUBMIT
// ==============================

form.addEventListener("submit", function (e) {

    e.preventDefault();

    if (!validateForm()) {

        return;

    }

    confirmModal.show();

});

// ==============================
// KIRIM KE LARAVEL
// ==============================

confirmSubmit.addEventListener("click", function () {

    confirmModal.hide();

    submitButton.disabled = true;

    submitButton.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Mengirim Data...
    `;

    form.submit();

});

// ==============================
// RESET
// ==============================

resetButton.addEventListener("click", function () {

    stopCamera();

    previewImage.src = "";

    previewImage.classList.add("d-none");

    photoPlaceholder.classList.remove("d-none");

    selfieInput.value = "";

    photoUploaded = false;

    cameraButton.classList.remove("d-none");
    captureButton.classList.add("d-none");
    retakeButton.classList.add("d-none");
    cameraError.classList.add("d-none");

    previousDataAlert.classList.add("d-none");
    previousPhotoHint.classList.add("d-none");

});

// ==============================
// LIVE VALIDATION
// ==============================

visitorName.addEventListener("input", function () {

    this.classList.toggle("is-valid", this.value.length >= 3);

});

visitorEmail.addEventListener("input", function () {

    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    this.classList.toggle("is-valid", regex.test(this.value));

});

visitorCompany.addEventListener("input", function () {

    this.classList.toggle("is-valid", this.value.trim() !== "");

});