<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    
    <title>ADMIN SECURE LINK</title> 
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Bebas+Neue&family=Poppins&family=Prompt:wght@500&family=Ubuntu:wght@300&display=swap');
      * {
        font-family: 'Poppins', sans-serif;
      }
      
      body {
        background-color: #CBE5FE;
        margin-bottom: 
      }
      .H2 {
        font-size: 40px;
        font-weight: 700;
        color: #337AB7;
      }
      .test1 {
        color: red;
        font-size: 30px;
        font-weight: 700;
      }
    </style>
</head>
<body>
  <div class="container">
    <form action="{{route('PortalCode') }}" method="POST">
      @csrf
      <div class="row justify-content-center">
        <div class="col-md-6" style="margin-top: 25%;">
           <!------------------------ERROR ALERT------------------------------>
            @if(session('error'))
              <p id="error-message" class="error text-center" style="font-size: 20px; font-weight:700; color: red; " >{{ session('error') }}</p>
            @endif

            <!------------------------SUCCESS ALERT------------------------------>
            @if(session('success'))
              <div class="alert alert-success text-center">
                  {{ session('success') }}
              </div>
            @endif
             <!------------------------CONTENT------------------------------>
            <div class="input-group" >
                <h2 class="H2 text-center" style="width: 100%; margin-bottom:40px ">WELCOME TO ENTRIX SYSTEM</h2>
                <input type="text" name="code" placeholder="ENTER THE SECURITY KEY NUMBER" class="form-control text-center" autofocus required>
                <h5 class="link text-center" style="width: 100%; margin-top:40px "><span class="test1"></span></h5>
            </div>
        </div>
      </div>
    </form>
    
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>

<script>
  var typed = new Typed('.test1', {
    strings: ["DO NOT SHARE THIS LINK!", "SECURE THIS LINK!", "BE RESPONSIBLE!"],
    typeSpeed: 150,
    BackSpeed: 150,
    loop: true

  });

  const ErrorMessage = document.querySelector('#error-message');

  function test1() {
    ErrorMessage.style.display = 'none';
  }
  setTimeout(test1, 3000);


</script>
  
</body>
</html>
