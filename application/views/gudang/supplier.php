  <!-- MENU SECTION END-->

  <div class="formSidebar w3-bar-block" style="right:0;padding:10px;" id="rightMenu">
    <button id="judul" onclick="tutupSideForm()" class="w3-bar-item">&times; Close Sidebar</button>
    <form id="formTambah" action="<?php echo base_url('gudang/simpan_supplier') ?>" method="post">
      <table border="0" class="table" align="center">
        <tr>
          <td>Kode Supplier</td>
          <td>:</td>
          <td><input name="kd_supplier" type="text" id="kd_supplier" /></td>
        </tr>
        <tr>
          <td>Nama Supplier</td>
          <td>:</td>
          <td><input type="text" name="nm_supplier" id="nm_supplier" /></td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td><input type="text" name="alamat" id="alamat" /></td>
        </tr>
        <tr>
          <td>Telp</td>
          <td>:</td>
          <td><input type="text" name="telp" id="telp" /></td>
        </tr>
        <tr>
          <td>Atas Nama</td>
          <td>:</td>
          <td><input type="text" name="an" id="an" /></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td>
            <input type="submit" class="btn" name="button_tambah" id="button_tambah" value="Simpan" />
            <input type="reset" class="btn btn-primary" name="button_reset" id="button_reset" value="Reset" />
          </td>
        </tr>
      </table>
    </form>
  </div>
  <div id="main-isi">
    <div class="w3-teal">
      <button id="bukaSide" class="w3-button w3-teal w3-right" onclick="openRightMenu()">&#9776;</button>
      <div class="w3-container">
        <h3>DATA SUPPLIER <span class="pull-right"><a href="">Tambah Data</a></span></h3>
      </div>
    </div>
    <div class="w3-container" style="padding:20px;margin-bottom: 10px">
      <table id="tbKategori" class="table table-bordered table-striped table-responsive">
        <thead>
          <tr>
            <th>Kode Supplier</th>
            <th>Nama Supplier</th>
            <th>Alamat</th>
            <th>Telp</th>
            <th>Atas Nama</th>
            <th class="td-actions" align="center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($supplier->result() as $key) : ?>
            <tr>
              <td><?php echo $key->kd_supplier ?></td>
              <td><?php echo $key->nm_supplier ?></td>
              <td><?php echo $key->almt_supplier ?></td>
              <td><?php echo $key->tlp_supplier ?></td>
              <td><?php echo $key->atas_nama ?></td>
              <td style="text-align: center;"><a href="javascript:void(0);" class="edit_record" data-kode="<?php echo $key->kd_supplier ?>" data-nama="<?php echo $key->nm_supplier ?>" data-alamat="<?php echo $key->almt_supplier ?>" data-telp="<?php echo $key->tlp_supplier ?>" data-an="<?php echo $key->atas_nama ?>">Edit</a> | <a href="javascript:void(0);" class="delete-record" data-kode_menu="<?php echo $key->kd_supplier ?>">Hapus</a></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Hapus -->
  <div class="modal fade" id="modalHapus" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Hapus Menu</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" action="<?php echo base_url('gudang/hapus_menu') ?>" method="post">
            <h4>Apakah Kamu Yakin Menghapus Data Menu Ini?</h4>
        </div>
        <input type="hidden" id="kode_menu_h" name="kode_menu_h">
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button type="submit" id="btnHapus" class="btn btn-primary">Ya</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- CONTENT-WRAPPER SECTION END-->
  <!-- <section class="footer-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          &copy; Copyright <?php echo date('Y') ?>, <a href="https://alele-solutions.com" target="_blank">Alele Solutions</a>
        </div>
      </div>
    </div>
  </section> -->
  <!-- FOOTER SECTION END-->
  <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
  <script src="<?php echo base_url() ?>/assets/js/jquery-1.10.2.js"></script>
  <script src="<?php echo base_url() ?>/assets/js/dataTables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url() ?>/assets/js/bootstrap.js"></script>
  <script src="<?php echo base_url() ?>/assets/js/custom.js"></script>
  <script src="<?php echo base_url() ?>/assets/js/sweetalert.min.js"></script>
  <script src="<?php echo base_url() ?>/assets/js/toastr.min.js"></script>
  <script>
    $('#tbKategori').DataTable({});
    $('form').attr('autocomplete', 'off');
    $("ul.nav li.dropdown").hover(function() {
      $(this).find(".dropdown-menu").stop(!0, !0).delay(100).fadeIn(500)
    }, function() {
      $(this).find(".dropdown-menu").stop(!0, !0).delay(100).fadeOut(500)
    });
    var pesan = "<?php echo $this->session->flashdata('msg'); ?>",
      error = "<?php echo $this->session->flashdata('error'); ?>";
    pesan ? (toastr.options = {
      positionClass: "toast-top-right"
    }, toastr.success(pesan)) : error && swal(error);

    function openRightMenu() {
      document.getElementById("main-isi").style.marginRight = "35%", document.getElementById("main-isi").style.marginRight = "35%", document.getElementById("rightMenu").style.width = "35%", document.getElementById("rightMenu").style.display = "block", document.getElementById("bukaSide").style.display = "none"
    }

    function tutupSideForm() {
      document.getElementById("rightMenu").style.display = "none", document.getElementById("main-isi").style.marginRight = "0%", document.getElementById("bukaSide").style.display = "block"
    }

    $(document).ready(function() {
      document.getElementById("main-isi").style.marginRight = "35%";
      document.getElementById("rightMenu").style.width = "35%";
      document.getElementById("bukaSide").style.display = "none";
      $('#tbKategori').on('click', '.edit_record', function() {
        var kode = $(this).data('kode');
        var nama = $(this).data('nama');
        var alamat = $(this).data('alamat');
        var telp = $(this).data('telp');
        var an = $(this).data('an');
        $('#formTambah').attr('action', '<?php echo base_url('gudang/simpan_supplier_edit') ?>');
        $('#kd_supplier').prop('readonly', true);
        document.getElementById("judul").innerHTML = "&times; Edit Data";
        openRightMenu();
        $('[name="kd_supplier"]').val(kode);
        $('[name="nm_supplier"]').val(nama);
        $('[name="alamat"]').val(alamat);
        $('[name="telp"]').val(telp);
        $('[name="an"]').val(an);
      });
      //GET CONFIRM DELETE
      $('.delete-record').on('click', function() {
        var kode = $(this).data('kd_supplier');
        $('#modalHapus').modal('show');
        $('[name="kd_supplier_h"]').val(kode);
      });
    });
  </script>

  </body>

  </html>