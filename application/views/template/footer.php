  <!-- CONTENT-WRAPPER SECTION END-->
  <?php
    $query = $this->db->get('tabel_toko', 1, 0);
    $toko = $query->row();
    $namatoko = $toko->nm_toko;
    ?>
  <section class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                   &copy; Copyright <b><?php echo $namatoko ?></b> 2021 <!-- <?php echo date('Y') ?> -->, <a href="https://github.com/akuadimaulana" target="_blank"><b>Adi Maulana</b></a>
                </div>
            </div>
        </div>
    </section>
  <!-- FOOTER SECTION END-->