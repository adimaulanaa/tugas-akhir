<div class="content-wrapper" style="margin-bottom: 20px">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">DATA Member <span class="pull-right"><a href="" data-toggle="modal" data-target="#myModal">Tambah Member</a></span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="tbMember" class="table table-bordered table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>ID Member</th>
                            <th>Nama Member</th>
                            <th>Alamat </th>
                            <th>Telp / Hp</th>
                            <th>Jumlah Point</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($member->result() as $key) : ?>
                            <tr>
                                <td><?php echo $key->kd_member ?></td>
                                <td><?php echo $key->nm_member ?></td>
                                <td><?php echo $key->almt_member ?></td>
                                <td><?php echo $key->telp ?></td>
                                <td><?php echo $key->jum_point ?></td>
                                <td><?php echo $key->keterangan ?></td>
                                <td align="center"><a href="javascript:void(0);" id="tbMember" class="edit_record" data-kd="<?php echo $key->kd_member ?>" data-nama="<?php echo $key->nm_member ?>" data-alamat="<?php echo $key->almt_member ?>" data-telp="<?php echo $key->telp ?>" data-jumlah="<?php echo $key->jum_point ?>" data-keterangan="<?php echo $key->keterangan ?>">Edit</a> |
                                    <a href="javascript:void(0);" class="hapus_record" data-kd="<?php echo $key->kd_member ?>">Hapus</a></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Member</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="<?php echo base_url('m_master/simpan_member') ?>" method="post">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="kd_member">ID</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="kd_member" name="kd_member" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="nm_member">Nama</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="nm_member" name="nm_member" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="almt_member">Alamat</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="almt_member" name="almt_member" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="telp">Telp / Hp</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="telp" name="telp" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="jum_point">Jumlah Poin</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="jum_point" name="jum_point">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="keterangan">Keterangan</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" id="btnSimpan" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Member</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="<?php echo base_url('m_master/simpan_member_edit') ?>" method="post">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="kd_member_e">ID</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="kd_member_e" name="kd_member_e" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="nm_member_e">Nama</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="nm_member_e" name="nm_member_e" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="almt_member_e">Alamat</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="almt_member_e" name="almt_member_e" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="telp_e">Telp / Hp</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="telp_e" name="telp_e" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="jum_point_e">Jumlah Poin</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="jum_point_e" name="jum_point_e">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="keterangan_e">Keterangan</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="keterangan_e" name="keterangan_e">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" id="btnSimpan" class="btn btn-primary">Simpan</button>
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
</div>
<!-- FOOTER SECTION END-->
<!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
<script src="<?php echo base_url() ?>/assets/js/jquery-3.3.1.js"></script>
<script src="<?php echo base_url() ?>/assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/bootstrap.js"></script>
<script src="<?php echo base_url() ?>/assets/js/custom.js"></script>
<script src="<?php echo base_url() ?>/assets/js/sweetalert.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/toastr.min.js"></script>
<script>
    $('#tbMember').DataTable({
        "paging": false,
        "ordering": false,
    });
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
        $('#tbMember').on('click', '.edit_record', function() {
            var kd_member_e = $(this).data('kd');
            var nm_member_e = $(this).data('nama');
            var almt_member_e = $(this).data('almt_member');
            var telp_e = $(this).data('telp');
            var jum_point_e = $(this).data('jum_point');
            var keterangan_e = $(this).data('keterangan');
            $('#modalEdit').modal('show');
            $('[name="kd_member_e"]').val(kd_member_e);
            $('[name="nm_member_e"]').val(nm_member_e);
            $('[name="almt_member_e"]').val(almt_member_e);
            $('[name="telp_e"]').val(telp_e);
            $('[name="jum_point_e"]').val(jum_point_e);
            $('[name="keterangan_e"]').val(keterangan_e);
        });

        $('#tbMember').on('click', '.hapus_record', function() {
            var kd = $(this).data('kd');
            var result = confirm("Apakah yakin akan menghapus kode " + kd + " ini?");
            var akses = "<?php echo $this->session->userdata('akses') ?>";
            if (akses == 'manager') {
                if (result) {
                    window.location.href = '<?php echo base_url('m_master/hapus_member/') ?>' + kd;
                }
            }
        });
    });

    $("#kd_member").keypress(function(e) {
        if (e.which == 32) {
            return false;
        }
    });
</script>

</body>

</html>