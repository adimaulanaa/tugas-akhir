<div class="row">
    <div class="col-xl-3 col-md-2 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pembelian</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?php if ($member) : ?>
                                <?php foreach ($list as $l) : ?>
                                    <?php
                                    $grandtotal += $l->sub_total_jual;
                                    ?>
                                <?php endforeach; ?>
                                <?php echo number_format($grandtotal, 0, ',', '.') ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-2 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Totol Qty</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?php if ($member) : ?>
                                <?php foreach ($list as $l) : ?>
                                    <?php
                                    $qty += $l->jumlah;
                                    ?>
                                <?php endforeach; ?>
                                <?php echo number_format($qty, 0, ',', '.') ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-2 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Point</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                        
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-2 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Hutang</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?php if ($member) : ?>
                                <?php foreach ($list as $l) : ?>
                                    <?php
                                    $grandtotal += $l->sub_total_jual;
                                    ?>
                                <?php endforeach; ?>
                                <?php echo number_format($grandtotal, 0, ',', '.') ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</div>
<br>
<?php if ($member) : ?>
    <div>
        <strong>Kode : </strong><?php echo $member->kd_member ?><span class="pull-right"><strong> Transaksi Sampai : </strong><?php echo date_indo($member->tgl_penjualan) ?> <?php echo $member->waktu ?></span>
        <strong>Nama : </strong><?php echo $member->nm_member ?>  
        <strong>Tlpn : </strong><?php echo $member->telp ?>

<?php endif ?>
<?php foreach ($data as $d) : ?>
        <br> 
     
    <!-- <strong>Tlpn : </strong><?php echo $d->telp ?> -->
    <!-- <strong>Alamat : </strong><?php echo $d->almt_member ?> -->
    <?php endforeach; ?>
    </div>
<table class="table table-condensed table-bordered" id="tbRetur">
    <thead>
        <tr>
            <!-- <th>No Faktur</th> -->
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <!-- <th style="text-align: right">Harga</th> -->
            <th style="text-align: right">Total</th>
            <!-- <th style="text-align: center">Aksi</th> -->
        </tr>
    </thead>
    <tbody>
        <?php if ($member) : ?>
            <?php foreach ($list as $l) : ?>
                <tr>


                    <td><?php echo $l->kd_barang ?></td>
                    <td><?php echo $l->nm_barang ?></td>
                    <td style="text-align: center"><?php echo $l->jumlah ?></td>
                    <td style="text-align: right"><?php echo number_format($l->sub_total_jual, 0, ',', '.') ?></td>
                </tr>
                <?php
                // $qty += $l->jumlah;
                $grandtotal += $l->sub_total_jual;
                ?>

            <?php endforeach; ?>
        <?php else : ?>
            <?php echo "<tr><td colspan='5'>Data tidak ditemukan</td></tr>"; ?>
        <?php endif ?>
    </tbody>
    <tr>
        <!-- <td><?php echo $l->kd_barang ?></td>
        <td><?php echo $l->nm_barang ?></td>
        <td style="text-align: center"><?php echo $l->jumlah ?></td> -->
        <!-- <td style="text-align: right"><?php echo number_format($l->harga, 0, ',', '.') ?></td> -->
        <!-- <td style="text-align: right"><?php echo number_format($l->sub_total_jual, 0, ',', '.') ?></td> -->
    </tr>

</table>
<script>
    $(document).ready(function() {
        $('#tbRetur').on('click', '.retur_item', function() {
            var nofak = $(this).data('nofak');
            var kode = $(this).data('kode');
            var kode_enk = btoa(kode);
            window.location.href = "<?php echo base_url('kasir/retur-item/') ?>" + nofak + "/" + kode_enk;
        });
    });
</script>