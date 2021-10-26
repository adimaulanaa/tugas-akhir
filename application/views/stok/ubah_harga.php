<!-- MENU SECTION END-->
<div class="formSidebar w3-bar-block" style="right:0;padding:10px;" id="rightMenu">
    <button id="judul" onclick="tutupSideForm()" class="w3-bar-item">&times; Close Sidebar</button>
    <form id="formTambah" action="<?php echo base_url('m_master/simpan_barang') ?>" method="post">
        <table border="0" class="table" align="center">
            <tr>
                <td>Kode Barang</td>
                <td>:</td>
                <td><input name="kd_barang" type="text" id="kd_barang" required /></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td>:</td>
                <td><input type="text" name="nm_barang" id="nm_barang" readonly /></td>
            </tr>

            <tr>
                <td>Harga Beli</td>
                <td>:</td>
                <td><input type="text" name="hrg_beli" id="hrg_beli" required /></td>
                <td><input type="hidden" name="sebelumbeli" id="sebelumbeli" required /></td>
            </tr>
            <tr>
                <td>Harga Jual</td>
                <td>:</td>
                <td><input type="text" name="hrg_jual" id="hrg_jual" required /></td>
                <td><input type="hidden" name="sebelumjual" id="sebelumjual" required /></td>
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
        <!-- <div class="w3-container">
            <h3>DATA Barang <span class="pull-right"><a href="">Tambah Data</a></span></h3>
        </div> -->
    </div>
    <div class="w3-container" style="padding:20px;margin-bottom: 10px">
        <table id="tbBarang" class="table table-bordered table-striped table-responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th class="td-actions" align="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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

    function openRightMenu() {
        document.getElementById("main-isi").style.marginRight = "30%", document.getElementById("main-isi").style.marginRight = "30%", document.getElementById("rightMenu").style.width = "30%", document.getElementById("rightMenu").style.display = "block", document.getElementById("bukaSide").style.display = "none"
    }

    function tutupSideForm() {
        document.getElementById("rightMenu").style.display = "none", document.getElementById("main-isi").style.marginRight = "0%", document.getElementById("bukaSide").style.display = "block"
    };

    function convertToRupiah(r) {
        for (var e = "", t = r.toString().split("").reverse().join(""), n = 0; n < t.length; n++) n % 3 == 0 && (e += t.substr(n, 3) + ".");
        return e.split("", e.length - 1).reverse().join("")
    };

    $(document).ready(function() {
        document.getElementById("main-isi").style.marginRight = "30%";
        document.getElementById("rightMenu").style.width = "30%";
        document.getElementById("bukaSide").style.display = "none";
        $('#tbBarang').on('click', '.edit_record', function() {
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');
            var hrg_beli = $(this).data('beli');
            var hrg_jual = $(this).data('jual');
            // var sebeliumjual = $(this).data('sebelumjual');
            // var sebeliumbeli = $(this).data('sebelumbeli');
            $('#formTambah').attr('action', '<?php echo base_url('m_stok/ubah_harga_edit') ?>');
            $('#kd_barang').prop('readonly', true);
            document.getElementById("judul").innerHTML = "&times; Edit Data";
            // openRightMenu();
            $('[name="kd_barang"]').val(kode);
            $('[name="nm_barang"]').val(nama);
            $('[name="hrg_jual"]').val(convertToRupiah(hrg_jual));
            $('[name="hrg_beli"]').val(convertToRupiah(hrg_beli));
            $('[name="sebelumjual"]').val(convertToRupiah(hrg_jual));
            $('[name="sebelumbeli"]').val(convertToRupiah(hrg_beli));
        });


    });

    $('#tbBarang').DataTable({
        "columnDefs": [{
                "className": "dt-left",
                "targets": [1]
            },
            {
                "className": "dt-right",
                "targets": [2, 3]
            },
            {
                "className": "dt-center",
                "targets": [4]
            },
            {
                "orderable": false,
                "targets": 4
            }
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '<?php echo base_url(); ?>m_stok/json_produk',
            "type": "POST",
        },
        "columns": [{
                "data": "kd_barang"
            },
            {
                "data": "nm_barang"
            },
            {
                "data": "hrg_beli",
                render: $.fn.dataTable.render.number('.', '.', 0)
            },
            {
                "data": "hrg_jual",
                render: $.fn.dataTable.render.number('.', '.', 0)
            },
            {
                "data": "Aksi"
            },
        ],
    });

    $(function() {
        $('#hrg_beli').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });
        $('#hrg_jual').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });
        $('#sebelumjual').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });
        $('#sebelumbeli').priceFormat({
            prefix: '',
            centsLimit: 0,
            thousandsSeparator: '.'
        });
    });
</script>

</body>

</html>