<!DOCTYPE html>
<html>
<head>
  <?php include'com/head.php'; ?>
  <script type="text/javascript">
  function validate(){
    if(Number(document.getElementById('first').value)+Number(document.getElementById('second').value)==Number(document.getElementById('sum').value)){
          return true;
    }else{
          document.getElementById('error').innerHTML="*** Invalid Captcha ***";
          return false;
    }
  }
  </script>
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
      body {
      background-image: url("image/Auction_3.JPG");
      background-color: #cccccc;
      height: 100vh; /* Use viewport height for full page height */
      width: 100%;
      background-position: center; /* Center the image */
      background-repeat: no-repeat; /* Do not repeat the image */
      background-size: cover;
      -webkit-transition: none;
      -o-transition: none;
      transition: none;
    }

  #logo {
    width: 150px;
    height: 150px;
  }
</style>


</head>

<body class="hold-transition">
<div class="login-box">
  <div class="login-logo">
  <img src="com/wcl_logo.png" class="img-circle" alt="User Image" id="logo">
<br><b style="background-color: #cccccc;">WCL<br/>
ROAD SALE MONITORING PORTAL
</b></br>  </div>
  <div class="login-box-body">
    <p class="login-box-msg"><b>Login</b></p>
    <form onsubmit="return validate(this);" action="query/validatelogin.php" method="post" enctype='multipart/form-data' >
      <div class="form-group has-feedback">
        <input type="text" name='attuser' class="form-control" placeholder="Username" required />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name='attpwd'  class="form-control" placeholder="Password" required />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <?php
				$first=rand(1,99);
				$second=rand(1,9);
				?>
         <div class="form-group col-sm-3">
							<b>Captcha</b>
					</div>
					<div class="form-group col-sm-1">
							<b><?php echo $first; ?></b><input type="hidden" name="first" id="first" value='<?php echo $first; ?>' class="form-control" />
					</div>
					<div class="form-group col-sm-1">
							<b>+</b>
					</div>
					<div class="form-group col-sm-1">
							<b><?php echo $second; ?></b><input type="hidden" name="second" id="second" value='<?php echo $second; ?>' class="form-control" />
					</div>
					<div class="form-group col-sm-1">
							<b>=</b>
					</div>
					<div class="form-group col-sm-3">
							  <input type="text" name='sum' id='sum' class="form-control" />
					</div>
          <div class="row col-xs-12 text-red" id='error'></div>
          <div class="row">
        <div class="col-xs-8"><a href="#"></a>
        </div>
        <div class="col-xs-12 text-center">
           <button type="submit" class="btn btn-primary btn-block btn-flat" style="background-color: #ff0000;">Login</button>
      </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>