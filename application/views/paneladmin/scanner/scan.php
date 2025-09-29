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
                    <div class="card-body">
                        <div style="width:100%" id="reader"></div>
                        <p id="result" style="font-weight:bold;margin-top:10px;"></p>

                        <!-- Control buttons -->
                        <div class="mt-3">
                            <button type="button" class="btn btn-primary" onclick="startScanner()">
                                <i class="fas fa-play"></i> Start Scanner
                            </button>
                            <button type="button" class="btn btn-warning" onclick="stopScanner()">
                                <i class="fas fa-stop"></i> Stop Scanner
                            </button>
                            <button type="button" class="btn btn-info" onclick="tryAlternativeCamera()">
                                <i class="fas fa-camera"></i> Try Alternative
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="checkCameraInfo()">
                                <i class="fas fa-info"></i> Check Camera Info
                            </button>
                        </div>

                        <!-- Manual QR input fallback -->
                        <div class="mt-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="manual-qr" placeholder="Masukkan kode QR secara manual...">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="button" onclick="processManualQR()">
                                        <i class="fas fa-check"></i> Process
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <audio id="sound-success" src="<?= base_url('public/sound/success.mp3') ?>" preload="auto"></audio>
    <audio id="sound-error" src="<?= base_url('public/sound/failed.mp3') ?>" preload="auto"></audio>
</section>

<!-- QRcode -->
<script src="https://unpkg.com/html5-qrcode"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<input type="hidden" id="csrf_token_name" value="<?= $csrf_token_name ?>">
<input type="hidden" id="csrf_hash" value="<?= $csrf_hash ?>">

<script>
    let html5QrCode;

    function getCsrfToken() {
        return document.getElementById("csrf_hash").value;
    }

    // Function to check camera support
    function checkCameraSupport() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            return false;
        }
        return true;
    }

    // Function to get available cameras
    async function getCameras() {
        try {
            const devices = await Html5Qrcode.getCameras();
            return devices;
        } catch (err) {
            console.error("Error getting cameras:", err);
            return [];
        }
    }

    async function startScanner() {
        // Check browser support first
        if (!checkCameraSupport()) {
            document.getElementById("result").innerText = "Browser tidak mendukung akses kamera. Gunakan browser yang lebih baru.";
            return;
        }

        try {
            // Get available cameras
            const cameras = await getCameras();

            if (cameras.length === 0) {
                document.getElementById("result").innerText = "Tidak ada kamera yang ditemukan.";
                return;
            }

            console.log("Available cameras:", cameras);

            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0
            };

            // Try different camera constraints
            let cameraConstraints;

            // First try with back camera (environment)
            if (cameras.length > 1) {
                // Use the last camera (usually back camera)
                cameraConstraints = cameras[cameras.length - 1].id;
            } else {
                // Use first available camera
                cameraConstraints = cameras[0].id;
            }

            // Alternative: try with facingMode constraint
            // cameraConstraints = { facingMode: "environment" };

            await html5QrCode.start(
                cameraConstraints,
                config,
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

                            // ✅ Play success or error sound
                            if (data.status === 'success') {
                                document.getElementById("sound-success").play();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            } else {
                                document.getElementById("sound-error").play();

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            }

                            // 🔄 Restart scan setelah 2 detik
                            setTimeout(() => {
                                startScanner();
                            }, 3000);
                        });
                },
                function onScanError(errorMessage) {
                    // silent error handling
                }
            );

            document.getElementById("result").innerText = "Scanner aktif. Arahkan kamera ke QR code.";

        } catch (err) {
            console.error("Error starting scanner:", err);

            // Try fallback method
            if (err.name === 'NotAllowedError') {
                document.getElementById("result").innerText = "Akses kamera ditolak. Mohon izinkan akses kamera pada browser.";
            } else if (err.name === 'NotFoundError') {
                document.getElementById("result").innerText = "Kamera tidak ditemukan.";
            } else if (err.name === 'NotSupportedError') {
                document.getElementById("result").innerText = "Browser tidak mendukung akses kamera. Gunakan HTTPS atau browser yang lebih baru.";
            } else {
                document.getElementById("result").innerText = "Gagal akses kamera: " + err.message;
            }

            // Try alternative camera setup
            setTimeout(() => {
                tryAlternativeCamera();
            }, 2000);
        }
    }

    // Alternative camera setup
    async function tryAlternativeCamera() {
        try {
            const config = {
                fps: 5,
                qrbox: 200,
                aspectRatio: 1.0
            };

            // Try with basic facingMode constraint
            await html5QrCode.start({
                    facingMode: "environment"
                },
                config,
                function onScanSuccess(qrCodeMessage) {
                    html5QrCode.stop();
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
                            document.getElementById("csrf_hash").value = data.csrf_token;

                            if (data.status === 'success') {
                                document.getElementById("sound-success").play();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            } else {
                                document.getElementById("sound-error").play();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                            }

                            setTimeout(() => {
                                startScanner();
                            }, 3000);
                        });
                },
                function onScanError(errorMessage) {
                    // silent
                }
            );

            document.getElementById("result").innerText = "Scanner aktif (mode alternatif).";

        } catch (err) {
            console.error("Alternative camera also failed:", err);
            document.getElementById("result").innerText = "Kamera tidak dapat diakses. Pastikan:\n1. Browser mendukung kamera\n2. Akses kamera diizinkan\n3. Menggunakan HTTPS\n4. Kamera tidak digunakan aplikasi lain";
        }
    }

    // Stop scanner function
    function stopScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                document.getElementById("result").innerText = "Scanner dihentikan.";
            }).catch(err => {
                console.error("Error stopping scanner:", err);
            });
        }
    }

    // Check camera info function
    async function checkCameraInfo() {
        try {
            const cameras = await Html5Qrcode.getCameras();
            let info = `Ditemukan ${cameras.length} kamera:\n`;
            cameras.forEach((camera, index) => {
                info += `${index + 1}. ${camera.label || 'Camera ' + (index + 1)} (ID: ${camera.id})\n`;
            });

            // Check browser support
            const hasGetUserMedia = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
            info += `\nBrowser support getUserMedia: ${hasGetUserMedia}`;
            info += `\nUser Agent: ${navigator.userAgent}`;
            info += `\nProtocol: ${window.location.protocol}`;

            alert(info);
            document.getElementById("result").innerText = info;
        } catch (err) {
            const errorInfo = `Error getting camera info: ${err.message}`;
            alert(errorInfo);
            document.getElementById("result").innerText = errorInfo;
        }
    }

    // Process manual QR function
    function processManualQR() {
        const qrCode = document.getElementById("manual-qr").value.trim();
        if (!qrCode) {
            alert("Masukkan kode QR terlebih dahulu!");
            return;
        }

        document.getElementById("result").innerText = "Memvalidasi kode manual...";

        const formData = new URLSearchParams();
        formData.append('qr_code', qrCode);
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
                document.getElementById("csrf_hash").value = data.csrf_token;

                if (data.status === 'success') {
                    document.getElementById("sound-success").play();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 2500,
                        showConfirmButton: false
                    });
                } else {
                    document.getElementById("sound-error").play();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        timer: 2500,
                        showConfirmButton: false
                    });
                }

                // Clear manual input
                document.getElementById("manual-qr").value = "";
            })
            .catch(err => {
                document.getElementById("result").innerText = "Error: " + err.message;
            });
    }

    window.onload = function() {
        html5QrCode = new Html5Qrcode("reader");

        // Show initial info
        document.getElementById("result").innerText = "Memuat scanner...";

        // Auto start scanner
        setTimeout(() => {
            startScanner();
        }, 1000);
    };
</script>