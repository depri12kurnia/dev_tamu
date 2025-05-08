<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Scan QR</h3>
                    </div>
                    <div style="width:100%" id="reader"></div>
                    <p id="result" style="font-weight:bold;"></p>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<script src="https://unpkg.com/html5-qrcode"></script>

<input type="hidden" id="csrf_token_name" value="<?= $csrf_token_name ?>">
<input type="hidden" id="csrf_hash" value="<?= $csrf_hash ?>">

<script>
    let html5QrCode;

    function getCsrfToken() {
        return document.getElementById("csrf_hash").value;
    }

    function startScanner() {
        const config = {
            fps: 10,
            qrbox: 250
        };

        html5QrCode.start({
                facingMode: "environment"
            }, config,
            function onScanSuccess(qrCodeMessage) {
                html5QrCode.stop(); // stop dulu biar tidak double trigger
                document.getElementById("result").innerText = "Memvalidasi...";

                const formData = new URLSearchParams();
                formData.append('qr_code', qrCodeMessage);
                formData.append(document.getElementById("csrf_token_name").value, getCsrfToken());
                fetch("<?= site_url('admin/scan/check') ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: formData.toString()
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("result").innerText = data.message;

                        // ✅ Update token CSRF
                        document.getElementById("csrf_hash").value = data.csrf_token;

                        // 🔄 Restart scan setelah 2 detik
                        setTimeout(() => {
                            startScanner();
                        }, 2000);
                    });
            },
            function onScanError(errorMessage) {
                // silent
            }
        ).catch(err => {
            document.getElementById("result").innerText = "Gagal akses kamera: " + err;
        });
    }

    window.onload = function() {
        html5QrCode = new Html5Qrcode("reader");
        startScanner();
    };
</script>