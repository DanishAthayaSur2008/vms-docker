/*
==========================================
DC Visitor
Device Preview Frame (Shared)
Dipakai di semua halaman (Home, Check In,
Check Out) supaya mode tampilan yang dipilih
(HP/Tablet/Desktop) tetap konsisten pas
pindah halaman.
==========================================
*/

document.addEventListener("DOMContentLoaded", () => {

    const STORAGE_KEY = "vms_device_mode";
    const deviceButtons = document.querySelectorAll(".device-btn");

    function applyDeviceMode(mode) {

        document.documentElement.classList.remove(
            "device-mobile",
            "device-tablet",
            "device-desktop"
        );

        document.body.classList.remove(
            "device-mobile",
            "device-tablet",
            "device-desktop"
        );

        document.documentElement.classList.add("device-" + mode);
        document.body.classList.add("device-" + mode);

        deviceButtons.forEach(function (btn) {
            btn.classList.toggle("active", btn.dataset.device === mode);
        });

    }

    // Ambil mode yang tersimpan dari halaman sebelumnya,
    // default ke 'desktop' kalau belum pernah dipilih.
    const savedMode = localStorage.getItem(STORAGE_KEY) || "desktop";
    applyDeviceMode(savedMode);

    deviceButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            const mode = this.dataset.device;
            localStorage.setItem(STORAGE_KEY, mode);
            applyDeviceMode(mode);
        });
    });

});