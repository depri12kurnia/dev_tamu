<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Tamu</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/settings/icon/icon.png">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #f8f9fa;
            font-family: sans-serif;
        }

        .card-summary {
            text-align: center;
            padding: 1rem;
            border-radius: 12px;
        }

        .dashboard-header {
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .table thead {
            background-color: #f1f1f1;
        }

        .filter-container select,
        .filter-container button {
            margin: 5px;
        }

        .chart-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        canvas {
            background-color: #fff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table.dataTable thead th {
            background-color: #f1f1f1;
        }

        table.dataTable {
            width: 100% !important;
            margin-top: 20px;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url('/'); ?>"><img src="<?php echo base_url(); ?>public/settings/logo/logo.png" alt="Logo" style="height: 70px; margin-right: 30px;"></a>

            <!-- Tombol toggle untuk mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu navigasi -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"> <!-- ms-auto untuk rata kanan -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('auth/login'); ?>" style="font-size: 10;">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="d-flex align-items-center mb-3">
            <div class="dashboard-header">Dashboard Pemantauan Proses Inovasi Produk Dan Layanan</div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-2">
                <div class="card-summary bg-info text-white">Total Inovasi<br><strong><?php echo $total_inovasi ?></strong></div>
            </div>
            <div class="col-md-2">
                <div class="card-summary bg-success text-white">Internal<br><strong><?php echo $total_internal ?></strong></div>
            </div>
            <div class="col-md-2">
                <div class="card-summary bg-warning text-dark">Eksternal<br><strong><?php echo $total_eksternal ?></strong></div>
            </div>
            <div class="col-md-3">
                <div class="card-summary bg-danger text-white">Dalam Pengembangan<br><strong><?php echo $dalam_pengembangan ?></strong></div>
            </div>
            <div class="col-md-3">
                <div class="card-summary bg-primary text-white">Sudah Diimplementasikan<br><strong><?php echo $sudah_implementasi ?></strong></div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="tipeChart"></canvas>
            </div>
        </div>
        <div class="chart-container">
            <div class="col-md-12">
                <canvas id="barChart"></canvas>
            </div>
        </div>
        <br>
        <br>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select class="form-select" id="filterTipe">
                    <option value="">- Pilih Tipe -</option>
                    <option value="Produk">Produk</option>
                    <option value="Layanan">Layanan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterStatus">
                    <option value="">- Pilih Status -</option>
                    <!-- <option value="Dalam Pengembangan">Dalam Pengembangan</option>
                    <option value="Telah Diimplementasikan">Telah Diimplementasikan</option> -->
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterUnit">
                    <option value="">- Pilih Unit -</option>
                    <option value="Bag Akademik Kemahasiswaan">Bag Akademik Kemahasiswaan</option>
                    <option value="Bag Hubungan Masyarakat">Bag Hubungan Masyarakat</option>
                    <option value="Bag Kepegawaian Umum">Bag Kepegawaian Umum</option>
                    <option value="Bag Keuangan dan BMN">Bag Keuangan dan BMN</option>
                    <option value="Pusat Penelitian Dan Pengabdian Masyarakat">Pusat Penelitian Dan Pengabdian Masyarakat</option>
                    <option value="Pusat Pengembangan Pendidikan">Pusat Pengembangan Pendidikan</option>
                    <option value="Pusat Penjamin Mutu">Pusat Penjamin Mutu</option>
                    <option value="Satuan Pengawas Internal">Satuan Pengawas Internal</option>
                    <option value="Unit Arsip">Unit Arsip</option>
                    <option value="Unit Laboratorium Terpadu">Unit Laboratorium Terpadu</option>
                    <option value="Unit Pengembangan Bahasa">Unit Pengembangan Bahasa</option>
                    <option value="Unit Pengembangan Kompetensi">Unit Pengembangan Kompetensi</option>
                    <option value="Unit Perpustakaan Terpadu">Unit Perpustakaan Terpadu</option>
                    <option value="Unit Teknologi Informasi">Unit Teknologi Informasi</option>
                    <option value="Unit Usaha">Unit Usaha</option>
                    <option value="Jurusan Kebidanan">Jurusan Kebidanan</option>
                    <option value="Jurusan Keperawatan">Jurusan Keperawatan</option>
                    <option value="Jurusan Fisioterapi">Jurusan Fisioterapi</option>
                    <option value="Jurusan TLM">Jurusan TLM</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="reloadAll()">Cari</button>
            </div>
        </div>

        <table id="tabelInovasi" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Inovasi</th>
                    <th>Tipe</th>
                    <th>Unit/Divisi</th>
                    <th>Status</th>
                    <th>Tanggal Mulai</th>
                    <th>Estimasi Selesai / Implementasi</th>
                </tr>
            </thead>
        </table>

    </div>
    <footer class="bg-white text-black pt-4 pb-2 mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="text-center">
                    Copyright &copy; <?php echo date('Y') ?> <a href="https://www.poltekkesjakarta3.ac.id/">Polkesjati</a>. All rights reserved.
                </div>
            </div>
    </footer>

    <script>
        const statusData = {
            Produk: ['Dalam Pengembangan', 'Telah Diimplementasikan'],
            Layanan: ['Dalam Percobaan', 'Dalam Perbaikan', 'Telah Diimplementasikan']
        };

        const filterTipeSelect = document.getElementById('filterTipe');
        const statusSelect = document.getElementById('filterStatus');

        filterTipeSelect.addEventListener('change', function() {
            const filterTipeDipilih = this.value;

            // Kosongkan status
            statusSelect.innerHTML = '<option value="">- Pilih Status -</option>';

            if (filterTipeDipilih && statusData[filterTipeDipilih]) {
                statusData[filterTipeDipilih].forEach(function(filterStatus) {
                    const option = document.createElement('option');
                    option.value = filterStatus;
                    option.text = filterStatus;
                    statusSelect.appendChild(option);
                });
            }
        });

        const base_url = "<?= base_url() ?>";

        let pieChart, barChart;

        const table = $('#tabelInovasi').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: base_url + 'home/ajax_list',
                data: function(d) {
                    d.tipe = $('#filterTipe').val();
                    d.status = $('#filterStatus').val();
                    d.unit = $('#filterUnit').val();
                }
            }
        });

        pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ['#4caf50', '#ff9800', '#03a9f4', '#9c27b0']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });

        tipeChart = new Chart(document.getElementById('tipeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Tipe Inovasi',
                    data: [],
                    backgroundColor: '#03a9f4'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        barChart = new Chart(document.getElementById('barChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Jumlah Inovasi',
                    data: [],
                    backgroundColor: '#00B9AD'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function reloadAll() {
            table.ajax.reload();
            updateCharts();
        }

        function updateCharts() {
            $.getJSON(base_url + 'home/ajax_grafik', {
                tipe: $('#filterTipe').val(),
                status: $('#filterStatus').val(),
                unit: $('#filterUnit').val()
            }, function(res) {

                pieChart.data.labels = res.status.map(i => i.status);
                pieChart.data.datasets[0].data = res.status.map(i => i.total);
                pieChart.update();

                tipeChart.data.labels = res.tipe.map(i => i.tipe);
                tipeChart.data.datasets[0].data = res.tipe.map(i => i.total);
                tipeChart.update();

                barChart.data.labels = res.unit.map(i => i.unit);
                barChart.data.datasets[0].data = res.unit.map(i => i.total);
                barChart.update();
            });
        }

        $(document).ready(() => {
            updateCharts();
        });
    </script>
</body>

</html>