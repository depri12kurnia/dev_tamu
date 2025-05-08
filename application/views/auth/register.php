<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-8">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Register a new participants</h3>
                        </div>
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
                            <?php echo form_open("auth/register_process"); ?>
                            <input type="hidden" name="csrf_token_jkt3" value="<?= $this->security->get_csrf_hash(); ?>">

                            <div class="form-group">
                                <label>Email :</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Password :</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Password" name="password" required id="password">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-eye-slash" id="eye"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Retype Password :</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Retype password" name="password_confirm" required id="password_confirm">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-eye-slash" id="eye2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>First Name :</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Last Name :</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <a href="<?php echo base_url('auth/login'); ?>" class="text-center">I already have a account</a>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" class="btn btn-info btn-block">Register</button>
                                </div>
                                <!-- /.col -->
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {

        $('#eye').click(function() {

            if ($(this).hasClass('fa-eye-slash')) {

                $(this).removeClass('fa-eye-slash');

                $(this).addClass('fa-eye');

                $('#password').attr('type', 'text');

            } else {

                $(this).removeClass('fa-eye');

                $(this).addClass('fa-eye-slash');

                $('#password').attr('type', 'password');
            }
        });
        $('#eye2').click(function() {

            if ($(this).hasClass('fa-eye-slash')) {

                $(this).removeClass('fa-eye-slash');

                $(this).addClass('fa-eye');

                $('#password_confirm').attr('type', 'text');

            } else {

                $(this).removeClass('fa-eye');

                $(this).addClass('fa-eye-slash');

                $('#password_confirm').attr('type', 'password');
            }
        });
    });
</script>