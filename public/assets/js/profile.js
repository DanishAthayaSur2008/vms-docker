/*
==========================================
PROFILE PAGE
==========================================
*/

window.addEventListener("load", function () {

    console.log("Profile Page Loaded");

    /*
    ==========================================
    Auto-open modal again if server-side
    validation failed on submit
    ==========================================
    */

    if (window.profilePageErrors) {

        if (window.profilePageErrors.profile) {

            const editModal = new bootstrap.Modal(
                document.getElementById("editProfileModal")
            );

            editModal.show();

        }

        if (window.profilePageErrors.password) {

            const passwordModal = new bootstrap.Modal(
                document.getElementById("changePasswordModal")
            );

            passwordModal.show();

        }

    }

});
