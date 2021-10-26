<div class="content-wrapper">
  <div class="container">
    <div class="row pad-botm">
      <div class="col-md-12">
        <!-- <h4 class="header-line">Member</h4> -->
        <div class="col-md-7">
        <div class="alert alert-warning print-error-msg" role="alert" style="display:none"></div>
        <form class="form-inline" id="formRetur" action="" method="post">
          <div class="form-group">
            <select name="kd_member" id="kd_member" class="form-control">
              <option value="">Pilih Member</option>
              <?php foreach ($member->result() as $key) : ?>
                <option value="<?php echo $key->kd_member ?>"><?php echo $key->nm_member ?></option>
              <?php endforeach ?>
            </select>
            <button type="submit" id="btnLihatMember" class="btn btn-default">LIHAT</button>
          </div>
      </div>
      </div>
    </div>
    <div class="row">
      <!-- <div class="col-md-12">
        <div class="alert alert-warning print-error-msg" role="alert" style="display:none"></div>
        <form class="form-inline" id="formRetur" action="" method="post">
          <div class="form-group">
            <select name="kd_member" id="kd_member" class="form-control">
              <option value="">Pilih Member</option>
              <?php foreach ($member->result() as $key) : ?>
                <option value="<?php echo $key->kd_member ?>"><?php echo $key->nm_member ?></option>
              <?php endforeach ?>
            </select>
            <button type="submit" id="btnLihatMember" class="btn btn-default">LIHAT</button>
          </div>
      </div> -->

      </form>
      <hr>
      <div id="view" class="span8">

      </div>
    </div>

  </div>
</div>

<style>
  .border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
  }

  .card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
  }

  .pb-2,
  .py-2 {
    padding-bottom: 0.5rem !important;
  }

  .pt-2,
  .py-2 {
    padding-top: 0.5rem !important;
  }

  .h-100 {
    height: 100% !important;
  }

  .shadow {
    box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15) !important;
  }

  .card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1.25rem;
  }

  .align-items-center {
    align-items: center !important;
  }

  .no-gutters {
    margin-right: 0;
    margin-left: 0;
  }

  .no-gutters>.col,
  .no-gutters>[class*=col-] {
    padding-right: 0;
    padding-left: 0;
  }

  .mr-2,
  .mx-2 {
    margin-right: 0.5rem !important;
  }

  .col {
    flex-basis: 0;
    flex-grow: 1;
    max-width: 100%;
  }

  .text-xs {
    font-size: 10px;
  }

  .text-primary {
    color: #4e73df !important;
  }

  .font-weight-bold {
    font-weight: 700 !important;
  }

  .dropdown .dropdown-menu .dropdown-header,
  .sidebar .sidebar-heading,
  .text-uppercase {
    text-transform: uppercase !important;
  }

  .mb-1,
  .my-1 {
    margin-bottom: 0.25rem !important;
  }

  .text-gray-800 {
    color: #5a5c69 !important;
  }

  .font-weight-bold {
    font-weight: 700 !important;
  }

  .no-gutters>[class*=col-] {
    padding-right: 0;
    padding-left: 0;
  }

  .col-auto {
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }

  .text-gray-300 {
    color: #dddfeb !important;
  }

  .fa,
  .fas {
    font-weight: 900;
  }
</style>

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
<script src="<?php echo base_url() ?>/assets/js/jquery.price_format.min.js"></script>
<script>
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


  $(document).ready(function() {
    $("#btnLihatMember").click(function(e) {
      e.preventDefault();
      var kd_member = $('#kd_member').val();
      console.log(kd_member);
      $(this).html("SEARCHING...").attr("disabled", "disabled");
      $.ajax({
        url: '<?php echo base_url('crm/get_data_member') ?>',
        type: 'POST',
        data: {
          kd_member: kd_member
        },
        dataType: "json",
        beforeSend: function(e) {
          if (e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response) {
          $("#btnLihatMember").html("LIHAT").removeAttr("disabled");
          $("#view").html(response.hasil);
        },
        error: function(xhr, ajaxOptions, thrownError) {
          $("#btnLihatMember").html("LIHAT").removeAttr("disabled");
          $(".print-error-msg").css('display', 'block');
          $(".print-error-msg").html('Nomor faktur tidak ditemukan');
        }
      });
    });
  });
</script>

</body>

</html>