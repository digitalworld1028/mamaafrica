<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo APP_NAME; ?> | Log In</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/fontawesome-free/css/all.min.css"); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/Ionicons/css/ionicons.min.css"); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/dist/css/adminlte.css"); ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css"); ?>">

 <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/dist/css/custom.css"); ?>">
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:;"><b><?php echo APP_NAME; ?></b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
   <div class="card-body login-card-body">
    <p class="login-box-msg"><?php echo _l("Sign in to start your session"); ?></p>
    <?php
        echo _get_flash_message();
        echo form_open();
        
        echo _input_field("username",_l("User Phone"),'','text',array("placeholder"=>_l("User Phone"),"data-validation"=>"required"));
        echo _input_field("password",_l("Password"),'','password',array("placeholder"=>_l("Password"),"data-validation"=>"required"));
         echo '<div class="row"><div class="col-8">';
        echo _checkbox("remember",_l("Remember Me"));        
        echo '</div>';
        echo '<div class="col-4">';
        echo _submit_button(_l("Sign In"));
        echo '</div></div>';
        echo form_close();
    ?>

     <!--<a href="javascript:;"><?php echo _l("I forgot my password"); ?></a>-->
     </div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url("themes/backend/plugins/jquery/jquery.min.js"); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url("themes/backend/plugins/bootstrap/js/bootstrap.bundle.js"); ?>"></script>
<!-- iCheck -->
<script src="<?php echo base_url("themes/backend/dist/js/adminlte.min.js"); ?>"></script>
<script src="<?php echo base_url("themes/backend/plugins/form-validator/jquery.form-validator.min.js"); ?>"></script>
<script>
  $(function () {
    $.validate();   
  });
</script>
</body>
</html>
