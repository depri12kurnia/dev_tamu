<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Sendmail Guest</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table" class="table table-bordered table-hover small">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>name</th>
                            <th>email</th>
                            <th>nim</th>
                            <th>prodi</th>
                            <th>qr_code</th>
                            <th>is_sendmail_time</th>
                            <th>is_sendmail_in</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datatables -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>name</th>
                            <th>email</th>
                            <th>nim</th>
                            <th>prodi</th>
                            <th>qr_code</th>
                            <th>is_sendmail_time</th>
                            <th>is_sendmail_in</th>
                            <th>#</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<input type="hidden" id="csrf_token" name="csrf_token_jkt3" value="<?= $this->security->get_csrf_hash(); ?>">

<script type="text/javascript">
    var save_method;
    var table;

    function getCsrfToken() {
        let token = document.cookie.split('; ')
            .find(row => row.startsWith('csrf_cookie_jkt3='))
            ?.split('=')[1] || '';

        // console.log("CSRF Token dari Cookie:", token); // Debug
        return token;
    }

    function getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    $(document).ajaxSend(function(e, xhr, options) {
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-Token', csrfToken);
        }
    });

    $(document).ready(function() {
        table = $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "lengthChange": true,
            "ajax": {
                "url": "<?php echo site_url('admin/sendmail/ajax_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.csrf_token_jkt3 = getCsrfToken(); // Kirim CSRF token sebagai data POST
                },
                "error": function(xhr) {
                    console.log("Error:", xhr.responseText);
                }
            }

        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function sendMail(email) {
        if (confirm('Kirim undangan ke ' + email + '?')) {
            $.ajax({
                url: "<?php echo site_url('admin/sendmail/send_email') ?>",
                type: "POST",
                data: {
                    email: email,
                    csrf_token_jkt3: getCsrfToken()
                },
                dataType: "json",
                success: function(data) {
                    alert(data.message);
                    reload_table();
                },
                error: function(jqXHR) {
                    alert('Gagal mengirim email');
                }
            });
        }
    }
</script>