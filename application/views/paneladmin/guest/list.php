<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Master Data Guest</h3>
                <a href="<?php echo base_url('admin/guest/export_excel') ?>" target="_blank" class="card-title btn btn-warning btn-sm float-right"><i class="fa fa-download"></i> Report</a>
                <!-- <a href="<?php echo base_url('admin/guest/print_labels') ?>" target="_blank" class="card-title btn btn-primary btn-sm float-right"><i class="fa fa-print"></i> Print</a> -->
                <a href="<?php echo base_url('admin/guest/print_label_pdf') ?>" target="_blank" class="card-title btn btn-danger btn-sm float-right"><i class="fa fa-file-pdf"></i> Pdf</a>
                <button class="card-title btn btn-default btn-sm float-right" onclick="import_guest()"><i class="fa fa-file-excel"></i> Import</a></button>
                <button class="card-title btn btn-success btn-sm float-right" onclick="add_guest()"><i class="fa fa-plus"></i> Add</a></button>
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
                            <th>is_checked_time</th>
                            <th>is_checked_in</th>
                            <th>Action</th>
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
                            <th>is_checked_time</th>
                            <th>is_checked_in</th>
                            <th>Action</th>
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

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Guest Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">name</label>
                            <div class="col-md-12">
                                <input name="name" placeholder="name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">email</label>
                            <div class="col-md-12">
                                <input name="email" placeholder="Email" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">nim</label>
                            <div class="col-md-12">
                                <input name="nim" placeholder="nim" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">prodi</label>
                            <div class="col-md-12">
                                <select name="prodi" class="form-control">
                                    <option value="">- Pilih Prodi -</option>
                                    <option value="D-III Keperawatan">D-III Keperawatan</option>
                                    <option value="D-III Kebidanan">D-III Kebidanan</option>
                                    <option value="D-III Teknologi Laboratorium Medis">D-III Teknologi Laboratorium Medis</option>
                                    <option value="STr Keperawatan">STr Keperawatan</option>
                                    <option value="STr Kebidanan">STr Kebidanan</option>
                                    <option value="STr Fisioterapi">STr Fisioterapi</option>
                                    <option value="STr Teknologi Laboratorium Medis">STr Teknologi Laboratorium Medis</option>
                                    <option value="STr Promosi Kesehatan">STr Promosi Kesehatan</option>
                                    <option value="Profesi Ners">Profesi Ners</option>
                                    <option value="Profesi Bidan">Profesi Bidan</option>
                                    <option value="Profesi Fisioterapis">Profesi Fisioterapis</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnSave">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Import -->
<div class="modal fade" id="modal_import" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Guest Import</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_import" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="file_excel" class="form-control" type="file" accept=".xlsx" required>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Import</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                "url": "<?php echo site_url('admin/guest/ajax_list') ?>",
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

    function add_guest() {
        save_method = 'add';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('.modal-title').text('Add Guest');
    }

    function edit_guest(id) {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $.ajax({
            url: "<?php echo site_url('admin/guest/ajax_edit/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="name"]').val(data.name);
                $('[name="email"]').val(data.email);
                $('[name="nim"]').val(data.nim);
                $('[name="prodi"]').val(data.prodi);
                $('#modal_form').modal('show');
                $('.modal-title').text('Edit Guest');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error getting data from ajax');
            }
        });
    }

    function delete_guest(id) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                url: "<?php echo site_url('admin/guest/ajax_delete/') ?>" + id,
                type: "POST",
                data: {
                    csrf_token_jkt3: getCsrfToken()
                }, // Kirim CSRF token

                dataType: "JSON",
                success: function(data) {
                    $('#modal_form').modal('hide');
                    reload_table();

                    console.log("Token CSRF baru:", data.csrf_token); // Debug
                    // Perbarui CSRF token setelah request berhasil
                    document.cookie = "csrf_cookie_jkt3=" + data.csrf_token + "; path=/";
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }

    $('#btnSave').click(function() {
        var url;
        if (save_method == 'add') {
            url = "<?php echo site_url('admin/guest/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('admin/guest/ajax_update') ?>";
        }

        var formData = $('#form').serialize();
        formData += '&csrf_token_jkt3=' + getCsrfToken(); // Tambahkan CSRF token ke form data

        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize() + '&csrf_token_jkt3=' + getCsrfToken(),
            dataType: "JSON",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("X-CSRF-Token", getCsrfToken());
            },
            success: function(data) {
                if (data.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                }
                console.log("Token CSRF baru:", data.csrf_token); // Debug
                // Perbarui CSRF token setelah request berhasil
                document.cookie = "csrf_cookie_jkt3=" + data.csrf_token + "; path=/";
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });

    // Import Guest

    function import_guest() {
        $('#form_import')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_import').modal('show');
        $('.modal-title').text('Import Guest');
    }

    $('#form_import').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        // ✅ Tambahkan CSRF token dengan .append()
        formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', getCsrfToken());

        $.ajax({
            url: '<?php echo site_url('admin/guest/import_excel') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#form_import button[type=submit]').prop('disabled', true).text('Mengimpor...');
            },
            success: function(res) {
                alert(res.message);
                $('#modal_import').modal('hide');
                $('#form_import')[0].reset();
                table.ajax.reload();

                // ✅ Update CSRF token baru
                $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val(res.csrf_token);
            },
            error: function() {
                alert('Gagal mengimpor file.');
            },
            complete: function() {
                $('#form_import button[type=submit]').prop('disabled', false).text('Import');
            }
        });
    });

    function manualCheckin(id) {
        if (!confirm("Check-in tamu ini secara manual?")) return;

        $.ajax({
            url: "<?= site_url('admin/guest/ajax_manual_checkin') ?>",
            type: "POST",
            data: {
                id: id,
                csrf_token_jkt3: getCsrfToken()
            },
            dataType: "JSON",
            success: function(res) {
                alert(res.message);
                // update token
                $('input[name="csrf_token_jkt3"]').val(res.csrf_token);
                table.ajax.reload(null, false);
            },
            error: function() {
                alert("Gagal melakukan check-in.");
            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }
</script>