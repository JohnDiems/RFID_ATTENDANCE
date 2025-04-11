<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="images/RFIDLOGO.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <title>Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: url('images/backgound.png');
            background-size: cover;
            min-height: 100vh;
            width: 100%;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);

        }

        section {
            position: relative;
            min-height: 100vh;
            padding: 90px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 90px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            position: relative;
            max-width: 30px;
        }

        header ul {
            position: relative;
            display: flex;
        }

        header ul li {
            list-style: none;
            display: inline-block;
        }

        header ul li a {
            position: relative;
            color: #4A4A4A;
            font-weight: 350;
            margin-left: 50px;
            text-decoration: none;
        }
        .nav-link::before {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #337AB7;
            transform: scale(0);
            transform-origin: left;
            transition: transform 0.5s;
        }

        .nav-link:hover::before {
            transform: scale(1);
        }
        .content-h1 {
            justify-content: center;
            font-size: 5rem;
            font-weight: bold;
            color: #24A9E1;
            text-transform: uppercase;
        }
        .content-p {
            font-size: 1.2rem;
            color: #4A4A4A;
        }
        button {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            background: #24A9E1;
            font-family: "Montserrat", sans-serif;
            box-shadow: 0px 6px 24px 0px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            cursor: pointer;
            border: none;
        }

        button:after {
            content: " ";
            width: 0%;
            height: 100%;
            background: #137DC7;
            position: absolute;
            transition: all 0.4s ease-in-out;
            right: 0;
        }

        button:hover::after {
            right: auto;
            left: 0;
            width: 100%;
        }

        button span {
            text-align: center;
            text-decoration: none;
            width: 100%;
            padding: 13px 25px;
            color: #fff;
            font-size: 1.125em;
            font-weight: 700;
            letter-spacing: 0.3em;
            z-index: 20;
            transition: all 0.3s ease-in-out;
        }

        button:hover span {
            color: white;
            animation: scaleUp 0.3s ease-in-out;
        }

        @keyframes scaleUp {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.95);
            }

            100% {
                transform: scale(1);
            }

        }
        .Card {
            padding: 1.5em;
            border-radius: .5em;
            box-shadow: 12px 12px 51px rgba(0, 0, 0, 0.22);
            border: 2px solid rgba(0, 0, 0, 0.368);
            background: rgb(255, 255, 255);
            text-align: center;
            transition: all 0.4s;
            justify-content: center;
            user-select: none;
            font-weight: medium;
            color: black;
            box-sizing: border-box;
            cursor: pointer;

        }

        .Card:hover {
            border: 2px solid black;
            transform: scale(1.06);
        }

        .card:active {
            transform: scale(0.50) rotateZ(1.7deg);
        }

        .Card {
            width: 100%;
            font-size: 14px;
        }
        .container h2 {
            color: #137DC7;
        }
        .li a  {
            font-size: 40px;
        }

        @media (max-width: 320px) {
            .content-h1 {
                font-size: 4rem;
                text-align: center;
            }
        }

    </style>
</head>
<body>
    <section >
        <header>
            <a><img src="{{ asset('images/RFIDLOGO.png') }}" class="logo"
                style="border-radius: 70%; width: 100%;"></a>
            <ul class="menu"data-aos="fade-down" data-aos-delay="150" data-aos-duration="150">
                <li><a href="{{ route('indexHome') }}" class="nav-link" style="font-size: 20px; color: #4A4A4A">Home</a></li>
                <li><a href="{{ route('showLoginForm') }}" class="nav-link"  style="font-size: 20px; color: #4A4A4A">Login</a></li>
            </ul>
        </header>
        <div class="content ">
            <div class="text-center ">
                <h1 class="content-h1">Entrix System</h1>
                <p class="content-p">Tags and readers are the two halves of the wireless system known as Radio Frequency Identification (RFID). The reader is an electronic gadget with one or more antennas that transmit radio waves and take in signals from RFID tags.</p>
            </div>
            <div class="d-flex justify-content-center py-5">
                <a style="text-decoration: none" href="{{ route('home') }}">
                    <button  class="text-center" >
                        <span>Make Attendance!</span>
                    </button>
                </a>
            </div>
        </div>
    </section>
</body>
</html>
