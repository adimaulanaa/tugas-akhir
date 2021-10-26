<h4 align="center">LAPORAN PEMBELIAN</h4>
<h5 align="center">TOKO : <?php echo $toko->nm_toko ?></h5>
<?php if ($faktur): ?>
  <div><strong>No Faktur : </strong><?php echo $faktur->no_faktur_pembelian ?><span class="pull-right"><strong>Tanggal Transaksi : </strong><?php echo date_indo($faktur->tgl_pembelian) ?> </span></div>
  <!-- <span class="pull-right"><a data-nofak='<?php echo $faktur->no_faktur_pembelian ?>' href="<?php echo base_url('m_pembelian/cetak_data_faktur') ?>">Cetak</a></span></h4> -->

<?php endif?>
<table id="tbStok" class="table table-condensed table-bordered" id="tbRetur">
    
    <tr>
      <th>Kode Produk</th>
      <th>Nama Produk</th>
      <th>Jumlah</th>
      <th style="text-align: right">Harga</th>
      <th style="text-align: right">Subtotal</th>
      <!-- <th style="text-align: center">Aksi</th> -->
    </tr>
    <?php if ($faktur): ?>
      <?php foreach ($list as $l): ?>
        <tr>
          <td><?php echo $l->kd_barang ?></td>
          <td><?php echo $l->nm_barang ?></td>
          <td style="text-align: center"><?php echo $l->jumlah ?></td>
          <td style="text-align: right"><?php echo number_format($l->harga, 0, ',', '.') ?></td>
          <td style="text-align: right"><?php echo number_format($l->sub_total_beli, 0, ',', '.') ?></td>
          <!-- <td style="text-align: center"><a href="javascript:void(0);" class="retur_item" data-nofak='<?php echo $l->no_faktur_pembelian ?>'>Retur</a></td> -->
          <!-- <td style="text-align: center"><a data-nofak='<?php echo $l->no_faktur_pembelian ?>' href="<?php echo base_url('m_pembelian/cetak_data_faktur') ?>">Retur</a></td> -->
        </tr>
        <?php
// $tot += $l->jumlah;
$tot_harga += $l->sub_total_beli;
?>

    <?php endforeach;?>
    <tfoot>
    <tr>
        <td colspan="4" style="text-align:center;"><b>Total</b></td>
        <td style="text-align:right;"><b><?php echo number_format($tot_harga, 0, ',', '.') ?></b></td>
    </tr>
</tfoot>
    <?php else: ?>
      <?php echo "<tr><td colspan='5'>Data tidak ditemukan</td></tr>"; ?>
    <?php endif?>
</table>
<script src="<?php echo base_url() ?>/assets/js/jquery-3.3.1.js"></script>
<script src="<?php echo base_url() ?>/assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/bootstrap.js"></script>
<script src="<?php echo base_url() ?>/assets/js/custom.js"></script>
<script src="<?php echo base_url() ?>/assets/js/sweetalert.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/toastr.min.js"></script>
<script>
    $('#cetak').on('click', '.cetak_faktur', function() {
            var no_faktur_pembelian = $(this).data('no_faktur_pembelian');
            var result = confirm("Apakah yakin akan menghapus kode " + no_faktur_pembelian + " ini?");
            var akses = "<?php echo $this->session->userdata('akses') ?>";
            if (akses == 'manager') {
                if (result) {
                    window.location.href = '<?php echo base_url('m_pembelian/cetak_data_faktur/') ?>' + no_faktur_pembelian;
                }
            }
        });
    $('#tbStok').DataTable({
      "paging": false,
      "ordering": false,
      "info": false,
      "dom": 'Bfrtip',
      "buttons": [
        'copy', 'csv', 'pdf', 'print'
      ]
    });
   $(document).ready(function() {
        $('#tbRetur').on('click','.retur_item',function(){
            var nofak=$(this).data('nofak');
            // var kode=$(this).data('kode');
            // var kode_enk = btoa(kode);
            window.location.href = "<?php echo base_url('m_pembelian/cetak_data_faktur/') ?>"nofak;
        });
    });
</script>