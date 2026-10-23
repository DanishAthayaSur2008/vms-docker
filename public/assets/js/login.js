/*
==========================================
Admin Login
==========================================
*/

const loginForm = document.getElementById("loginForm");

const username = document.getElementById("username");

const password = document.getElementById("password");

const loginButton = document.getElementById("loginButton");

const loginAlert = document.getElementById("loginAlert");

const togglePassword = document.getElementById("togglePassword");
/*
==========================================
Show / Hide Password
==========================================
*/

togglePassword.addEventListener("click", function () {

    if (password.type === "password") {

        password.type = "text";

        this.innerHTML = `

            <i class="bi bi-eye-slash"></i>

        `;

    } else {

        password.type = "password";

        this.innerHTML = `

            <i class="bi bi-eye"></i>

        `;

    }

});
/*
==========================================
Login Validation
==========================================
*/

loginForm.addEventListener("submit", function (e) {

    e.preventDefault();

    loginAlert.classList.add("d-none");

    if (username.value.trim() === "") {

        username.focus();

        return;

    }

    if (password.value.trim() === "") {

        password.focus();

        return;

    }

    loginButton.disabled = true;

    loginButton.innerHTML = `

        <span class="spinner-border spinner-border-sm"></span>

        Memproses...

    `;

    setTimeout(() => {

        if (

            username.value === "admin"

            &&

            password.value === "admin123"

        ) {

            window.location.href = "dashboard";

        }

        else {

            loginAlert.classList.remove("d-none");

        }

        loginButton.disabled = false;

        loginButton.innerHTML = `

            <i class="bi bi-box-arrow-in-right"></i>

            <span>

                Login

            </span>

        `;

    },1500);

});