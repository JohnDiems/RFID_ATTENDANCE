<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>ADMIN REGISTRATION FORM</title> 
    
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
      .row {
        align-content: center;
        justify-content: center;
        display: flex;
        height: 90vh;
        width: 100%;
      }
      .tittle {
        color: #2ECC71;
        font-size: 30px;
        font-weight: 600;
        letter-spacing: 3px;
      }
      .EntrixSystem {
        font-size: 70px;
        font-weight: 900;
        letter-spacing: 3px;
        color: #337AB7;
      }
      .Department{
        color: #337AB7;
        font-size: 30px;
        font-weight: 400;
        letter-spacing: 3px;
        margin-bottom: 20px;
      }

      .typejs {
        color: red;
        font-size: 30px;
        font-weight: 700;
        letter-spacing: 3px
      }
    </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <h2 class="text-center EntrixSystem">WELCOME TO ENTRIX SYSTEM</h2>
      <h4 class="text-center Department">Assumption Junior High School Department</h4>
          <!------------------------ERROR ALERT------------------------------>
          @if($errors->has('username'))
              <div class="alert alert-danger text-center" id="elem-error">
                  {{ $errors->first('username') }}
              </div>
          @endif

           <!------------------------ERROR ALERT------------------------------>
          @if($errors->has('password'))
              <div class="alert alert-danger text-center" id="elem-error">
                  {{ $errors->first('password') }}
              </div>
          @endif

           <!------------------------SUCCESS ALERT------------------------------>
          @if(session('success'))
              <div class="alert alert-success text-center">
                  {{ session('success') }}
              </div>
          @endif
           <!------------------------CONTENT ALERT------------------------------>
        <div class="col-sm-12 col-mb-6 col-lg-5">
          <div class="card  text-dark" >
              <div class="card-body login label-p">
                 <h2 class="tittle text-center">ADMIN REGISTRATION</h2><hr>
                  <form action="{{route ('AdminRegistrationRequest') }}" method="post">
                      @csrf

                    <div class="form-group label-p mt-3">
                      <label for="name">Name</label>
                      <div class="input-group">
                          <div class="input-group-prepend">
                              <span class="input-group-text mt-1"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                      </div>
                    </div>
                    
                    <div class="form-group label-p mt-3">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text mt-1"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        </div>
                    </div>
                    
                    <div class="form-group label-p mt-3">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text mt-1"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    
                    <div class="form-group label-p mt-3">
                        <label for="role">Role:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text mt-1"><i class="fas fa-user"></i></span>
                            </div>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="forn-group mt-4">
                      <button type="submit" name="submit" id="submit" class="btn btn-primary w-100" class="form-control" name="submit">REGISTER</button>
                    </div>
                  </form>
              </div>
          </div>
          <h5 class="link text-center" style="width: 100%; margin-top:40px "><span class="typejs"></span></h5>
      </div>
    </div>
  </div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>

<script>
  var typed = new Typed('.typejs', {
    strings: ["DO NOT SHARE THIS LINK!", "SECURE THIS LINK!", "BE RESPONSIBLE!"],
    typeSpeed: 150,
    BackSpeed: 150,
    loop: true
  });

  const displayError = document.querySelector('#elem-error');
  const ElemError = () => {
    displayError.style.display = 'none';
  }

  setTimeout(ElemError, 3000);

  


</script>

  
</body>
</html>
