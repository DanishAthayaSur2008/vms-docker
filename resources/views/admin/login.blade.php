<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Sengked.com</title>


    <!-- Google Font -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">


    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">


    <!-- Global CSS -->

    <link rel="stylesheet"
          href="{{ asset('assets/css/global.css') }}">


    <!-- Login CSS -->

    <link rel="stylesheet"
          href="{{ asset('assets/css/admin/login.css') }}">


    @include('partials.head-assets')

</head>


<body>


<section class="login-page">

    <div style="position:fixed; top:20px; right:20px; z-index:1050;">
        <button class="theme-toggle" type="button" title="Ganti tema">
            <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
        </button>
    </div>

    <div class="container">


        <div class="row justify-content-center align-items-center min-vh-100">


            <div class="col-lg-5 col-md-7">


                <div class="login-card">



                    <!-- Logo -->


                    <div class="login-logo">


                        @include('partials.brand-logo')


                    </div>




                    <!-- Heading -->


                    <div class="text-center mb-4">


                        <h2>

                            Admin Login

                        </h2>


                        <p>

                            Masuk ke Dashboard
                            Sengked.com.

                        </p>


                    </div>





                    <!-- Error Message -->


                    @if(session('error'))

                    <div class="alert alert-danger">


                        <i class="bi bi-exclamation-circle-fill"></i>


                        {{ session('error') }}


                    </div>


                    @endif






                    <!-- Login Form -->


                    <form
                        action="{{ route('process') }}"
                        method="POST">
                        
                        @csrf

                        <!-- Email -->


                        <div class="mb-4">


                            <label
                                class="form-label">


                                Email


                            </label>



                            <input

                                type="email"

                                name="email"

                                class="form-control"

                                placeholder="Masukkan email"

                                required>


                        </div>







                        <!-- Password -->


                        <div class="mb-4">


                            <label
                                class="form-label">


                                Password


                            </label>




                            <div class="input-group">



                                <input

                                    type="password"

                                    name="password"

                                    id="password"

                                    class="form-control"

                                    placeholder="Masukkan password"

                                    required>




                                <button

                                    class="btn btn-outline-secondary"

                                    type="button"

                                    id="togglePassword">


                                    <i class="bi bi-eye"></i>


                                </button>



                            </div>


                        </div>







                        <!-- Remember -->


                        <div class="d-flex justify-content-between align-items-center mb-4">


                            <div class="form-check">


                                <input

                                    class="form-check-input"

                                    type="checkbox"

                                    name="remember"

                                    id="remember">



                                <label

                                    class="form-check-label"

                                    for="remember">


                                    Ingat Saya


                                </label>


                            </div>




                            <a

                                href="#"

                                class="forgot-password">


                                Lupa Password?


                            </a>



                        </div>








                        <!-- Login Button -->


                        <div class="d-grid mb-4">



                            <button

                                type="submit"

                                class="btn btn-primary btn-lg">


                                <i class="bi bi-box-arrow-in-right"></i>


                                Login



                            </button>



                        </div>





                    </form>








                    <!-- Footer -->


                    <div class="login-footer text-center">


                        <small>


                            © 2026 Sengked.com


                            <br>


                            Visitor Management System



                        </small>


                    </div>




                </div>


            </div>


        </div>


    </div>


</section>







<!-- Bootstrap JS -->


<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>





<!-- Toggle Password -->


<script>


    const togglePassword = document.getElementById('togglePassword');

    const password = document.getElementById('password');


    togglePassword.addEventListener('click', function(){


        if(password.type === "password"){


            password.type = "text";


            this.innerHTML = '<i class="bi bi-eye-slash"></i>';


        }else{


            password.type = "password";


            this.innerHTML = '<i class="bi bi-eye"></i>';


        }


    });


</script>




</body>

</html>