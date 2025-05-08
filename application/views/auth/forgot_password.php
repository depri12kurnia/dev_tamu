<!-- <h1><?php echo lang('forgot_password_heading'); ?></h1>
<p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>

<div id="infoMessage"><?php echo $message; ?></div>

<?php echo form_open("auth/forgot_password"); ?>

<p>
      <label for="identity"><?php echo (($type == 'email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label)); ?></label> <br />
      <?php echo form_input($identity); ?>
</p>

<p><?php echo form_submit('submit', lang('forgot_password_submit_btn')); ?></p>

<?php echo form_close(); ?> -->


<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Forgot Password</title>
      <link rel="shortcut icon" href="<?php echo base_url(); ?>public/settings/icon/icon.png">

      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
      <!-- icheck bootstrap -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
      <div class="login-box">
            <div class="card card-outline card-primary">
                  <div class="card-header text-center">
                        <img src="<?php echo base_url(); ?>public/settings/logo/logo.png" alt="Logo" class="brand-image" style="opacity: .8">
                  </div>
                  <div class="card-body">
                        <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                        <div id="infoMessage" class="login-box-msg"><?php echo $message; ?></div>
                        <?php echo form_open("auth/forgot_password_process"); ?>
                        <div class="input-group mb-3">
                              <input type="email" class="form-control" placeholder="Email" name="email">
                              <div class="input-group-append">
                                    <div class="input-group-text">
                                          <span class="fas fa-envelope"></span>
                                    </div>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                              </div>
                              <!-- /.col -->
                        </div>
                        <?php echo form_close(); ?>
                        <p class="mt-3 mb-1">
                              <a href="<?php echo site_url('auth/login'); ?>">Login</a>
                        </p>
                  </div>
                  <!-- /.login-card-body -->
            </div>
      </div>
      <!-- /.login-box -->

      <!-- jQuery -->
      <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap 4 -->
      <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
</body>

</html>