<div class="row">
    <div class="col-lg-2 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo $total_guest ?></h3>

                <p>Total Tamu</p>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-2 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo $total_hadir ?></sup></h3>

                <p>Total Tamu Hadir</p>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-2 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo $total_tidak ?></h3>

                <p>Belum Hadir</p>
            </div>
        </div>
    </div>
    <!-- ./col -->
</div>
</br>
<p>Selamat Datang <?php echo $this->session->userdata('email'); ?></p>