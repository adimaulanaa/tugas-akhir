<?php if ($faktur): ?>
  <div><strong>No Faktur : </strong><?php echo $faktur->no_faktur_penjualan ?><span class="pull-right"><strong>Tanggal Transaksi : </strong><?php echo date_indo($faktur->tgl_penjualan) ?> <?php echo $faktur->waktu ?></span></div>

<?php endif?>
<table class="table table-condensed table-bordered" id="tbFaktur">
    <tr>
      <th>Total Penjualan</th>
      <th>Diskon</th>
      <th>Total Diskon</th>
      <th>Ket Diskon</th>
      <th>Cash</th>
      <th>Debet</th>
      <th>Bank</th>
      <!-- <th>Keterangan</th> -->
      <th>User</th>
      <!-- <th style="text-align: right">Harga</th>
      <th style="text-align: right">Subtotal</th> -->
      <th style="text-align: center">Aksi</th>
    </tr>
    <?php if ($faktur): ?>
      <?php foreach ($list as $l): ?>
        <tr>
          <td style="text-align: right"><?php echo number_format($l->total_penjualan, 0, ',', '.') ?></td>
          <td style="text-align: right"><?php echo number_format($l->diskon, 0, ',', '.') ?></td>
          <td style="text-align: right"><?php echo number_format($l->total_penjualan_sdiskon, 0, ',', '.') ?></td>
          <td><?php echo $l->ket_diskon ?></td>
          <td style="text-align: right"><?php echo number_format($l->cash, 0, ',', '.') ?></td>
          <td style="text-align: right"><?php echo number_format($l->debet, 0, ',', '.') ?></td>
          <td><?php echo $l->ket ?></td>
          <!-- <td><?php echo $l->ket ?></td> -->
          <td><?php echo $l->id_user ?></td>
          <td style="text-align: center"><a href="javascript:void(0);" class="update_item" data-nofak='<?php echo $l->no_faktur_penjualan ?>' >Edit</a></td>
        </tr>
    <?php endforeach;?>
    <?php else: ?>
      <?php echo "<tr><td colspan='5'>Data tidak ditemukan</td></tr>"; ?>
    <?php endif?>
</table>
<script>
   $(document).ready(function() {
        $('#tbFaktur').on('click','.update_item',function(){
            var nofak=$(this).data('nofak');
            var kode=$(this).data('kode');
            var kode_enk = btoa(kode);
            window.location.href = "<?php echo base_url('kasir/update_faktur_item/') ?>"+nofak+"/";
        });
    });
</script>