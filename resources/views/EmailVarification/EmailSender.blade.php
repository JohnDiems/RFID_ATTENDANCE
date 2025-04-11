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
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
      }

      .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      h1 {
        color: #333;
      }
      p {
        color: #555;
      }
      .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #dadce0;
        font-size: 20px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 5px;
      }
    </style>
</head>
<body>

  <div class="container">
    <h1>Welcome to Our Entrix System!</h1>
    <p>Dear Admin,</p>
    <p>Thank you for joining our platform. We're excited to have you on board!</p>
    <p>To get started, click the button below:</p>
    <a href="{{route('AdminRegistration')}}" class="button">Click me!</a>
    <p>If you have any questions or need assistance, feel free to contact our support team.</p>
    <p>Best regards,<br>Entrix Team</p>
  </div>

</body>
</html>
