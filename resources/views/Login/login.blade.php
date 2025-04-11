<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/RFIDLOGO.png">
    <title>LOGIN</title>

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">

    <style>
        body {
            background-image: url('images/backgound.png');
            background-size: cover;
            min-height: 100vh;
            width: 100%;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .container {
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .bg-image {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
    </style>
</head>



<body>
    <section class="vh-100  bg-image">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card mb-5">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block text-center"
                                style="margin-top: 50px; left:8px;">
                                <!----------------------------ASSUMPTION IMAGE LOGIN FORM---------------------------------->
                                <img style="width: 80%;" src="{{ asset('images/ASSUMPTION.png') }}" alt="login form" />
                                <p style="margin-top: 20px; font-size: 25px; font-weight:750; color:#858796;">JUNIOR
                                    HIGH SCHOOL <br>DEPARTMENT</p>
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center"
                                style="background-color: #24A9E1; border: 2px solid #fff; border-bottom-right-radius: 20px; border-top-right-radius: 20px;">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <!----------------------------LOGIN REQUEST---------------------------------->
                                    <form action="{{ route('login') }}" method="post">
                                        <!----------------------------CSRF SECURITY---------------------------------->
                                        @csrf
                                        <div class="d-flex align-items-center mb-3 pb-4">
                                            <i class="fas fa-sign-in-alt text-dark" style="font-size: 40px;"> </i> <span
                                                class="h1 fw-bold mb-0 ml-2 text-white"> ENTRIX</span>
                                        </div>


                                        <h5 class="fw-normal mb-3 pb-3 text-white">Please enter your Username and
                                            password!</h5>
                                        <!-------------------------DISPLAY ERROR------------------------------------->
                                        @if (session('error'))
                                            <div id="error-message" class="alert alert-danger mt-2">
                                                {{ session('error') }}</div>
                                        @endif


                                        <div class="form-group mb-4">
                                            <!-------------------------USERNAME INPUT------------------------------------->
                                            <input type="text" id="username" name="username" placeholder="Username"
                                                class="form-control form-control-lg" required />
                                        </div>

                                        <!-------------------------PASSWORD INPUT------------------------------------->
                                        <div class="form-group mb-4">
                                            <input type="password" id="password" name="password" placeholder="Password"
                                                class="form-control form-control-lg" required />
                                        </div>

                                        <!-------------------------BTN LOGIN------------------------------------->
                                        <div class="pt-1 mb-3">
                                            <button type="submit" class="btn bn632-hover bn22 btn-block"><span
                                                    class="h3">Log In</span></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        const validate = document.querySelector('#error-message');

        function validateError() {
            validate.style.display = "none";
        }

        setTimeout(validateError, 5000);
    </script>
</body>

</html>
