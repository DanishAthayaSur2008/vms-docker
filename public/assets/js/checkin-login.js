/*
==========================================
Sengked.com
Visitor Login (2 langkah) - Javascript

Langkah 1 : Nomor Telepon + ID Visitor (5 digit)
Langkah 2 : Tujuan Kunjungan, Lantai, Nomor Rak, Selfie

Catatan integrasi backend:
- Verifikasi langkah 1 memanggil URL pada atribut
  data-lookup-url (route checkin.lookup) dengan query
  ?no_hp=...&visitor_id=...
- Respons yang dipahami frontend:
    { success: true,  data: { nama, perusahaan, foto } }
    { success: false, reason: "phone_not_found" }
    { success: false, reason: "visitor_id_not_found" }
    { success: false, reason: "mismatch" }
  (tanpa "reason" dianggap nomor telepon tidak ditemukan)
- Pencocokan Nomor Telepon <-> ID Visitor HARUS dilakukan
  di backend. Frontend hanya memvalidasi format (5 digit angka).
==========================================
*/

const loginForm = document.getElementById("loginForm");

const loginStep1 = document.getElementById("loginStep1");
const loginStep2 = document.getElementById("loginStep2");
const stepIndicator1 = document.getElementById("loginStepIndicator1");
const stepIndicator2 = document.getElementById("loginStepIndicator2");
const stepLine = document.getElementById("loginStepLine");

const loginPhone = document.getElementById("loginPhone");
const loginVisitorId = document.getElementById("loginVisitorId");
const loginVisitorIdCount = document.getElementById("loginVisitorIdCount");
const loginNextButton = document.getElementById("loginNextButton");
const loginBackButton = document.getElementById("loginBackButton");

const loginVerifyError = document.getElementById("loginVerifyError");
const loginVerifyErrorText = document.getElementById("loginVerifyErrorText");
const loginFormError = document.getElementById("loginFormError");
const loginFormErrorText = document.getElementById("loginFormErrorText");

const loginPurpose = document.getElementById("loginPurpose");
const loginLantai = document.getElementById("loginLantai");
const loginRak = document.getElementById("loginRak");

const foundName = document.getElementById("foundName");
const foundCompany = document.getElementById("foundCompany");

const loginPreviousPhotoHint = document.getElementById("loginPreviousPhotoHint");
const loginPreviousPhotoImg = document.getElementById("loginPreviousPhotoImg");

const loginCameraButton = document.getElementById("loginCameraButton");
const loginCaptureButton = document.getElementById("loginCaptureButton");
const loginRetakeButton = document.getElementById("loginRetakeButton");
const loginSelfieInput = document.getElementById("loginSelfieInput");
const loginCameraStream = document.getElementById("loginCameraStream");
const loginCameraCanvas = document.getElementById("loginCameraCanvas");
const loginCameraError = document.getElementById("loginCameraError");

const loginPreviewImage = document.getElementById("loginPreviewImage");
const loginPhotoPlaceholder = document.getElementById("loginPhotoPlaceholder");

const loginSubmitButton = document.getElementById("loginSubmitButton");
const loginResetButton = document.getElementById("loginResetButton");

const loginConfirmSubmit = document.getElementById("loginConfirmSubmit");

const loginConfirmModal = new bootstrap.Modal(
    document.getElementById("loginConfirmModal")
);

const lookupUrl = loginForm.dataset.lookupUrl;

const PHONE_PATTERN = /^[0-9]{10,15}$/;
const VISITOR_ID_PATTERN = /^[0-9]{5}$/;

const MESSAGES = {
    phoneInvalid: "Nomor telepon tidak valid. Masukkan 10 sampai 15 digit angka.",
    idInvalid: "ID Visitor harus terdiri dari tepat 5 digit angka.",
    idNotFound: "ID Visitor tidak ditemukan. Harap periksa kembali ID Visitor Anda.",
    phoneNotFound: "Nomor telepon tidak ditemukan. Harap periksa kembali nomor telepon Anda.",
    mismatch: "Nomor telepon dan ID Visitor tidak cocok. Harap periksa kembali data Anda.",
    network: "Tidak dapat terhubung ke server. Periksa koneksi Anda lalu coba lagi.",
    needVerify: "Silakan verifikasi Nomor Telepon dan ID Visitor terlebih dahulu."
};

let currentStep = 1;
let identityVerified = false;
let verifiedKey = "";
let loginPhotoUploaded = false;
let loginMediaStream = null;

const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
).matches;

// ==============================
// HELPER UI (alert & status field)
// ==============================

function restartAnimation(element) {

    element.style.animation = "none";

    // paksa reflow supaya animasi masuk diputar ulang
    void element.offsetWidth;

    element.style.animation = "";

}

function setAlert(box, textElement, message) {

    if (!message) {

        box.classList.add("d-none");
        return;

    }

    textElement.textContent = message;

    box.classList.remove("d-none");

    restartAnimation(box);

}

function flagField(input) {

    input.classList.remove("is-valid");
    input.classList.add("is-invalid", "field-flag");

}

function markInvalid(input) {

    input.classList.remove("is-valid", "field-flag");
    input.classList.add("is-invalid");

}

function clearField(input) {

    input.classList.remove("is-invalid", "field-flag");

}

function scrollToTop() {

    window.scrollTo({
        top: 0,
        behavior: prefersReducedMotion ? "auto" : "smooth"
    });

}

// ==============================
// FOTO SELFIE (Kamera Live)
// ==============================

function showLoginCameraError(message) {

    loginCameraError.textContent = message;
    loginCameraError.classList.remove("d-none");

}

function stopLoginCamera() {

    if (loginMediaStream) {

        loginMediaStream.getTracks().forEach(track => track.stop());
        loginMediaStream = null;

    }

    loginCameraStream.classList.add("d-none");

}

async function startLoginCamera() {

    loginCameraError.classList.add("d-none");

    try {

        loginMediaStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "user" },
            audio: false
        });

        loginCameraStream.srcObject = loginMediaStream;

        loginCameraStream.classList.remove("d-none");
        loginPreviewImage.classList.add("d-none");
        loginPhotoPlaceholder.classList.add("d-none");

        loginCameraButton.classList.add("d-none");
        loginCaptureButton.classList.remove("d-none");
        loginRetakeButton.classList.add("d-none");

    } catch (error) {

        console.error(error);

        showLoginCameraError(
            "Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan di browser."
        );

    }

}

loginCameraButton.addEventListener("click", startLoginCamera);

loginCaptureButton.addEventListener("click", function () {

    const videoWidth = loginCameraStream.videoWidth;
    const videoHeight = loginCameraStream.videoHeight;

    if (!videoWidth || !videoHeight) {
        showLoginCameraError("Kamera belum siap. Tunggu sebentar lalu coba lagi.");
        return;
    }

    loginCameraCanvas.width = videoWidth;
    loginCameraCanvas.height = videoHeight;

    const ctx = loginCameraCanvas.getContext("2d");

    // Mirror seperti kamera selfie HP
    ctx.save();

    ctx.translate(videoWidth, 0);
    ctx.scale(-1, 1);

    ctx.drawImage(
        loginCameraStream,
        0,
        0,
        videoWidth,
        videoHeight
    );

    ctx.restore();

    loginCameraCanvas.toBlob(function (blob) {

        if (!blob) {
            showLoginCameraError("Gagal mengambil foto.");
            return;
        }

        const file = new File(
            [blob],
            "selfie-login-" + Date.now() + ".jpg",
            {
                type: "image/jpeg"
            }
        );

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        loginSelfieInput.files = dataTransfer.files;

        const imageUrl = URL.createObjectURL(blob);

        loginPreviewImage.src = imageUrl;
        loginPreviewImage.classList.remove("d-none");
        loginPhotoPlaceholder.classList.add("d-none");

        stopLoginCamera();

        loginCaptureButton.classList.add("d-none");
        loginRetakeButton.classList.remove("d-none");

        loginPhotoUploaded = true;

    }, "image/jpeg", 0.9);

});

loginRetakeButton.addEventListener("click", function () {

    loginPreviewImage.classList.add("d-none");
    loginPhotoUploaded = false;
    loginSelfieInput.value = "";

    startLoginCamera();

});

loginSelfieInput.addEventListener("change", function () {

    const file = this.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (e) {

        loginPreviewImage.src = e.target.result;

        loginPreviewImage.classList.remove("d-none");
        loginPhotoPlaceholder.classList.add("d-none");
        loginCameraStream.classList.add("d-none");

        loginCameraButton.classList.add("d-none");
        loginCaptureButton.classList.add("d-none");
        loginRetakeButton.classList.remove("d-none");

        loginPhotoUploaded = true;

    };

    reader.readAsDataURL(file);

});

// ==============================
// LANGKAH (stepper)
// ==============================

function resetCameraUI() {

    stopLoginCamera();

    loginCameraError.classList.add("d-none");

    if (!loginPhotoUploaded) {

        loginPreviewImage.classList.add("d-none");
        loginPhotoPlaceholder.classList.remove("d-none");

        loginCameraButton.classList.remove("d-none");
        loginCaptureButton.classList.add("d-none");
        loginRetakeButton.classList.add("d-none");

    }

}

function showStep(step, options) {

    options = options || {};

    const goingBack = !!options.back;

    currentStep = step;

    const showPanel = step === 1 ? loginStep1 : loginStep2;
    const hidePanel = step === 1 ? loginStep2 : loginStep1;

    hidePanel.classList.add("d-none");

    showPanel.classList.remove("d-none");
    showPanel.classList.toggle("is-back", goingBack);

    restartAnimation(showPanel);

    stepIndicator1.classList.toggle("is-active", step === 1);
    stepIndicator1.classList.toggle("is-done", step === 2);
    stepIndicator2.classList.toggle("is-active", step === 2);

    stepLine.classList.toggle("is-complete", step === 2);

    if (step === 1) {

        stepIndicator1.setAttribute("aria-current", "step");
        stepIndicator2.removeAttribute("aria-current");

        resetCameraUI();

    } else {

        stepIndicator2.setAttribute("aria-current", "step");
        stepIndicator1.removeAttribute("aria-current");

    }

    if (!options.silent) {

        scrollToTop();

        (step === 1 ? loginPhone : loginPurpose).focus({ preventScroll: true });

    }

}

// ==============================
// INPUT : NOMOR TELEPON & ID VISITOR
// ==============================

function updateVisitorIdCount() {

    const length = loginVisitorId.value.length;

    loginVisitorIdCount.textContent = length + "/5";

    loginVisitorIdCount.classList.toggle("is-complete", length === 5);

    loginVisitorId.classList.toggle("is-valid", length === 5);

}

function invalidateVerification() {

    identityVerified = false;

    setAlert(loginVerifyError, loginVerifyErrorText, "");

}

loginPhone.addEventListener("input", function () {

    // angka saja
    this.value = this.value.replace(/[^0-9]/g, "");

    clearField(this);

    this.classList.toggle("is-valid", PHONE_PATTERN.test(this.value));

    invalidateVerification();

});

loginVisitorId.addEventListener("input", function () {

    // angka saja, maksimal 5 digit (termasuk saat paste)
    this.value = this.value.replace(/[^0-9]/g, "").slice(0, 5);

    clearField(this);

    updateVisitorIdCount();

    invalidateVerification();

});

[loginPhone, loginVisitorId].forEach(function (input) {

    input.addEventListener("keydown", function (e) {

        // Enter di langkah 1 = tombol "Lanjutkan"
        if (e.key === "Enter") {

            e.preventDefault();

            goToStepTwo();

        }

    });

});

updateVisitorIdCount();

// ==============================
// NOMOR RAK (angka saja)
// ==============================

loginRak.addEventListener("input", function () {

    this.value = this.value.replace(/[^0-9]/g, "");

    clearField(this);

    this.classList.toggle("is-valid", this.value.trim() !== "");

    setAlert(loginFormError, loginFormErrorText, "");

});

[loginPurpose, loginLantai].forEach(function (select) {

    select.addEventListener("change", function () {

        clearField(this);

        setAlert(loginFormError, loginFormErrorText, "");

    });

});

// ==============================
// LANGKAH 1 : VERIFIKASI
// ==============================

function setNextLoading(isLoading) {

    loginNextButton.disabled = isLoading;

    loginNextButton.innerHTML = isLoading
        ? `<span class="spinner-border spinner-border-sm me-2"></span>
           <span class="btn-label">Memeriksa...</span>`
        : `<span class="btn-label">Lanjutkan</span>
           <i class="bi bi-arrow-right ms-1"></i>`;

}

async function verifyVisitor(phone, visitorId) {

    const query =
        "?no_hp=" + encodeURIComponent(phone) +
        "&visitor_id=" + encodeURIComponent(visitorId);

    const response = await fetch(lookupUrl + query, {
        headers: { "Accept": "application/json" }
    });

    if (!response.ok) {

        throw new Error("HTTP " + response.status);

    }

    return response.json();

}

function interpretVerification(result) {

    if (result && result.success === true) {

        return { ok: true, data: result.data || {} };

    }

    const reason = result && result.reason;

    if (reason === "visitor_id_not_found") {

        return { ok: false, fields: [loginVisitorId], message: MESSAGES.idNotFound };

    }

    if (reason === "mismatch") {

        return { ok: false, fields: [loginPhone, loginVisitorId], message: MESSAGES.mismatch };

    }

    return { ok: false, fields: [loginPhone], message: MESSAGES.phoneNotFound };

}

async function goToStepTwo() {

    if (loginNextButton.disabled) {

        return;

    }

    setAlert(loginVerifyError, loginVerifyErrorText, "");

    const phone = loginPhone.value.trim();
    const visitorId = loginVisitorId.value.trim();

    // --- validasi format (frontend) ---

    let formatValid = true;

    if (!PHONE_PATTERN.test(phone)) {

        markInvalid(loginPhone);
        formatValid = false;

    }

    if (!VISITOR_ID_PATTERN.test(visitorId)) {

        markInvalid(loginVisitorId);
        formatValid = false;

    }

    if (!formatValid) {

        (loginPhone.classList.contains("is-invalid")
            ? loginPhone
            : loginVisitorId).focus();

        return;

    }

    // --- sudah pernah diverifikasi dengan data yang sama ---

    const key = phone + ":" + visitorId;

    if (identityVerified && key === verifiedKey) {

        showStep(2);

        return;

    }

    // --- verifikasi ke backend ---

    setNextLoading(true);

    try {

        const result = await verifyVisitor(phone, visitorId);

        const outcome = interpretVerification(result);

        if (!outcome.ok) {

            outcome.fields.forEach(flagField);

            setAlert(loginVerifyError, loginVerifyErrorText, outcome.message);

            outcome.fields[0].focus();

            return;

        }

        const data = outcome.data;

        foundName.textContent = data.nama || "-";
        foundCompany.textContent = data.perusahaan || "-";

        if (data.foto) {

            loginPreviousPhotoImg.src = data.foto;
            loginPreviousPhotoHint.classList.remove("d-none");

        } else {

            loginPreviousPhotoHint.classList.add("d-none");

        }

        identityVerified = true;
        verifiedKey = key;

        showStep(2);

    } catch (error) {

        console.error(error);

        setAlert(loginVerifyError, loginVerifyErrorText, MESSAGES.network);

    } finally {

        setNextLoading(false);

    }

}

loginNextButton.addEventListener("click", goToStepTwo);

loginBackButton.addEventListener("click", function () {

    showStep(1, { back: true });

});

// ==============================
// LANGKAH 2 : VALIDASI
// ==============================

function failStepTwo(message, field) {

    setAlert(loginFormError, loginFormErrorText, message);

    if (field) {

        flagField(field);

        field.focus({ preventScroll: true });

    }

    loginFormError.scrollIntoView({
        behavior: prefersReducedMotion ? "auto" : "smooth",
        block: "nearest"
    });

    return false;

}

function validateStepTwo() {

    setAlert(loginFormError, loginFormErrorText, "");

    if (!identityVerified) {

        showStep(1, { back: true });

        setAlert(loginVerifyError, loginVerifyErrorText, MESSAGES.needVerify);

        return false;

    }

    if (loginPurpose.value === "") {

        return failStepTwo("Pilih tujuan kunjungan.", loginPurpose);

    }

    if (loginLantai.value === "") {

        return failStepTwo("Pilih lantai terlebih dahulu.", loginLantai);

    }

    if (loginRak.value.trim().length < 1) {

        return failStepTwo("Nomor rak wajib diisi (angka).", loginRak);

    }

    if (!loginPhotoUploaded) {

        return failStepTwo("Silakan ambil foto selfie.", null);

    }

    return true;

}

// ==============================
// SUBMIT
// ==============================

loginForm.addEventListener("submit", function (e) {

    e.preventDefault();

    // Enter / submit saat masih di langkah 1 = lanjut ke langkah 2
    if (currentStep === 1) {

        goToStepTwo();

        return;

    }

    if (!validateStepTwo()) {

        return;

    }

    loginConfirmModal.show();

});

loginConfirmSubmit.addEventListener("click", function () {

    loginConfirmModal.hide();

    loginSubmitButton.disabled = true;

    loginSubmitButton.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Mengirim Data...
    `;

    loginForm.submit();

});

// ==============================
// RESET
// ==============================

loginForm.addEventListener("reset", function () {

    stopLoginCamera();

    loginPreviewImage.src = "";

    loginPreviewImage.classList.add("d-none");

    loginPhotoPlaceholder.classList.remove("d-none");

    loginSelfieInput.value = "";

    loginPhotoUploaded = false;

    loginCameraButton.classList.remove("d-none");
    loginCaptureButton.classList.add("d-none");
    loginRetakeButton.classList.add("d-none");
    loginCameraError.classList.add("d-none");

    loginPreviousPhotoHint.classList.add("d-none");

    identityVerified = false;
    verifiedKey = "";

    [loginPhone, loginVisitorId, loginPurpose, loginLantai, loginRak].forEach(function (field) {

        clearField(field);

        field.classList.remove("is-valid");

    });

    setAlert(loginVerifyError, loginVerifyErrorText, "");
    setAlert(loginFormError, loginFormErrorText, "");

    // nilai input baru benar-benar kosong setelah reset bawaan browser selesai
    setTimeout(updateVisitorIdCount, 0);

    showStep(1, { back: true, silent: true });

});
