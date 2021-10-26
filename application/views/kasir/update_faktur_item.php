<div class="content-wrapper">
  <div class="container">
    <div class="row pad-botm">
        <div class="col-md-12">
            <h4 class="header-line">Konfirmasi Update Faktur</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="alert alert-warning print-error-msg" role="alert" style="display:none"></div>
          <form class="form-horizontal" action="<?php echo base_url('kasir/simpan_update_faktur') ?>" method="post">
            <div class="form-group">
              <label class="control-label col-sm-2" for="nofak">Nomor Faktur</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="nofak" name="nofak" value="<?php echo $produk->no_faktur_penjualan ?>" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="total_penjualan">Total Penjualan</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="total_penjualan" name="total_penjualan" value="<?php echo number_format($produk->total_penjualan, 0, ',', '.') ?>" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="diskon">Diskon</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="diskon" name="diskon" value="<?php echo number_format($produk->diskon, 0, ',', '.') ?>" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="total_penjualan_sdiskon">Total Diskon</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="total_penjualan_sdiskon" name="total_penjualan_sdiskon" value="<?php echo number_format($produk->total_penjualan_sdiskon, 0, ',', '.') ?>" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="ket_diskon">Keterangan Diskon</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="ket_diskon" name="ket_diskon" value="<?php echo $produk->ket_diskon ?>" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="cash">Cash</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="cash" name="cash" value="<?php echo number_format($produk->cash, 0, ',', '.') ?>" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="debet">Debet</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="debet" name="debet" value="<?php echo number_format($produk->debet, 0, ',', '.') ?>" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="id_user">User</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="id_user" name="id_user" value="<?php echo $produk->id_user ?>" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="ket">Bank</label>
              <div class="col-sm-5">
              <select name="bank" id="bank" class="form-control" >
                    <option value="">Pilih Jenis Bank</option>                   
                      <option value="BCA">BCA</option>
                      <option value="Mandiri">Mandiri</option>                    
                      <option value="BRI">BRI</option>
                      <option value="CIMB">CIMB</option>
                      <option value="BNI">BNI</option>
                      <option value="Link Aja">Link Aja</option>                    
                      <option value="QRI BCA">QRI BCA</option>
                      <option value="QRI BRI">QRI BRI</option>
                      <option value="QRI Mandiri">QRI Mandiri</option>
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="ket">Keterangan</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="ket" name="ket" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" id="btnSimpanRetur" onclick="return confirm('Apakah kamu yakin?')" class="btn btn-success">Simpan</button>
              </div>
            </div>
          </form>
        </div>
    </div>
  </div>
</div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <section class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                   &copy; Copyright <?php echo date('Y') ?>, <a href="https://alele-solutions.com" target="_blank">Alele Solutions</a>
                </div>
            </div>
        </div>
    </section>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <script src="<?php echo base_url() ?>/assets/js/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/custom.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/sweetalert.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/toastr.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/jquery.price_format.min.js"></script>
    <script>
      $('form').attr('autocomplete', 'off');
      $("ul.nav li.dropdown").hover(function(){$(this).find(".dropdown-menu").stop(!0,!0).delay(100).fadeIn(500)},function(){$(this).find(".dropdown-menu").stop(!0,!0).delay(100).fadeOut(500)});
      var pesan="<?php echo $this->session->flashdata('msg'); ?>",error="<?php echo $this->session->flashdata('error'); ?>";pesan?(toastr.options={positionClass:"toast-top-right"},toastr.success(pesan)):error&&swal(error);
      
      function convertToRupiah(r) {
        for (var e = "", t = r.toString().split("").reverse().join(""), n = 0; n < t.length; n++) n % 3 == 0 && (e += t.substr(n, 3) + ".");
        return e.split("", e.length - 1).reverse().join("")
      };
      $(function(){
        // $('[name="total_penjualan"]').val(convertToRupiah(total_penjualan));
        //   // $('#total_penjualan').priceFormat({
        //   //     prefix: '',
        //   //     centsLimit: 0,
        //   //     thousandsSeparator: '.'
        //   // });
        //   $('#total_penjualan_sdiskon').priceFormat({
        //       prefix: '',
        //       centsLimit: 0,
        //       thousandsSeparator: '.'
        //   });
        //   $('#cash').priceFormat({
        //       prefix: '',
        //       centsLimit: 0,
        //       thousandsSeparator: '.'
        //   });
        //   $('#debet').priceFormat({
        //       prefix: '',
        //       centsLimit: 0,
        //       thousandsSeparator: '.'
        //   });
          
      });
    </script>

</body>
</html>