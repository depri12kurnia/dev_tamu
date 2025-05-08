<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Activity Users Logs</h3>
            </div>
            <div class="col-6">
                <p class="btn btn-group">
                    <button class="btn btn-danger btn-sm" onclick="delete_activity()"><i class="fa fa-trash"></i> Delete All</a></button>
                </p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="data_activity" class="table table-bordered table-hover small">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>user</th>
                            <th>action</th>
                            <th>timestamp</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>user</th>
                            <th>action</th>
                            <th>timestamp</th>
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
        table = $('#data_activity').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('admin/activity/get_data') ?>",
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

    function delete_activity() {
        if (confirm('Are you sure you want to delete this data?')) {
            $.ajax({
                url: "<?php echo site_url('admin/activity/delete_all_activity') ?>",
                type: "POST",
                data: {
                    csrf_token_jkt3: getCsrfToken() // Kirim CSRF token
                },
                dataType: "JSON",
                cache: false, // Hindari cache request
                success: function(data) {
                    if (data.status === "success") {
                        $('#modal_form').modal('hide');
                        reload_table();

                        // Debugging: Tampilkan token CSRF baru di console
                        console.log("Token CSRF baru:", data.csrf_token);

                        // Update CSRF token di cookie untuk request selanjutnya
                        document.cookie = "csrf_cookie_jkt3=" + data.csrf_token + "; path=/";
                    } else {
                        alert("Failed to delete activity: " + data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert("Error deleting data. Please try again.");
                }
            });
        }
    }
</script>