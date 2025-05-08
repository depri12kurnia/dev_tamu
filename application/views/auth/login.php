<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In | Login</title>
  <link rel="shortcut icon" href="<?php echo base_url(); ?>public/settings/icon/icon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow" style="width: 100%; max-width: 400px;">

      <!-- Header -->
      <div class="card-header text-white" style="background-color: #17a2b8;">
        <h5 class="mb-0">Sign In Here</h5>
      </div>

      <!-- Body -->
      <div class="card-body">
        <?php if (validation_errors()) : ?>
          <div class="alert alert-danger">
            <?php echo validation_errors(); ?>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('message')) : ?>
          <div class="alert alert-danger">
            <?php echo $this->session->flashdata('message'); ?>
          </div>
        <?php endif; ?>
        <?php echo form_open("auth/login"); ?>
        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label fw-bold">Email :</label>
          <div class="input-group">
            <input type="email" class="form-control" placeholder="Email" name="identity" autofocus required>
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
          </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label fw-bold">Password :</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
            <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
              <i id="toggleIcon" class="bi bi-eye-slash"></i>
            </span>
          </div>
        </div>

        <!-- Remember Me & Forgot -->
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" name="remember" id="remember" value="1">
          <label class="form-check-label" for="remember">Remember Me</label>
        </div>
        <div class="mb-3">
          <a href="<?php echo site_url('auth/forgot_password'); ?>" class="text-primary">I forgot my password</a>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn text-white" style="background-color: #17a2b8;">Sign in</button>
          <button type="button" class="btn btn-outline-secondary">Cancel</button>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const password = document.getElementById("password");
      const icon = document.getElementById("toggleIcon");
      if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
      } else {
        password.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>