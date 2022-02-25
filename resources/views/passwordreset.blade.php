<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- <link rel="icon" type="image/png" sizes="16x16" href ="{{asset('images/aglogo.png')}}"> --}}


    <title>Signin</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    {{-- <link href="{{asset('css/signin.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Sacramento|Cinzel|Montserrat|Dancing Script">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  </head>

  <body class=" d-flex flex-column min-vh-100 justify-content-center align-items-center">
    <div class="card w-50 mx-auto">
      <div class="card-header">
        <h3 class="fw-bolder text-secondary">Reset your password</h3>
      </div>
      <div class="card-body">
        <form  v-show="signup_bool">
          <div class="">
            <label for="Email" class="form-label fw-bold text-secondary">Email</label>
            <input type="email" class="form-control" v-model="email" id="email"/>
          </div>
          <div class="">
              <label for="password" class="form-label fw-bold text-secondary">Password</label>
              <input type="password" class="form-control" v-model="password" id="password" />
          </div>
          <div class="">
              <label for="password_confirmation" class="form-label fw-bold text-secondary">Confirm Password</label>
              <input type="password" class="form-control" v-model="password_confirmation" id="password_confirmation" />
          </div>
        </form>
      </div>
      <div class="card-footer ">
        <span class="text-success d-none" style="height: 10px" id="messIcon">
          <i class="bi bi-chat-square-dots fs-6 me-2 d-inline"></i><p class="d-inline fs-6" id="message"></p>
        </span>
        <button class="btn float-end btn-success" onclick="passwordReset()">Reset Password</button>
      </div>
    </div>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <style>
    body{
      /*background: rgb(150, 150, 150); */
      background-image: url('{{asset('images/bgimage.png')}}');
      min-height: fit-content;
    }
    .card{
      width:40% !important;
      background-color: #ffffffdb !important;
    }
  </style>
  <script>
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const token = urlParams.get('token')
   
    var email = document.getElementById("email").value
    var password = document.getElementById("password").value
    var password_confirmation = document.getElementById("password_confirmation").value

    function passwordReset(){
      var email = document.getElementById("email").value
      var password = document.getElementById("password").value
      var password_confirmation = document.getElementById("password_confirmation").value

          axios.post('http://127.0.0.1:8000/api/reset-password',
          {   
            token, email, password, password_confirmation,
            header:{
              'Content-Type': 'application/json'
            }
          },
          ).then(response => {
              // console.log(response)
              document.getElementById("messIcon").className = " text-success d-block";
              document.getElementById("message").innerHTML = response.data.message
              setTimeout(() => {
                window.close();
              }, 2000);
             
          }).catch(error => {
              document.getElementById("messIcon").className = " text-danger d-inline";
              document.getElementById("message").innerText = error.response.data.message
          })
      // console.log([token, email, password, password_confirmation]);
    }
    
  </script>
</html>