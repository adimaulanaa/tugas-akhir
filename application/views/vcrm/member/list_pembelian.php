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
                            Total Qty Pembelian</div>
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
    <!-- <div class="col-xl-3 col-md-2 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Qty Pembelian</div>
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
    </div> -->
    <div class="col-xl-3 col-md-2 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Sales Order</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            <?php if ($member) : ?>
                                <?php
                                echo $count;
                                ?>
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
                            <?php if ($member) : ?>
                                <?php echo $data->jum_point ?>
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
        <strong>Kode : </strong><?php echo $member->kd_member ?>
        <br><strong>Nama : </strong><?php echo $data->nm_member ?>
        <br><strong>Tlpn : </strong><?php echo $data->telp ?>
        <br><strong>Alamat : </strong><?php echo $data->almt_member ?>


        <br><br>
        <span class="pull-right"><strong>Detail Transaksi Sampai : </strong><?php echo date_indo($member->tgl_penjualan) ?> <?php echo $member->waktu ?></span>

        <br>

    <?php endif ?>
    </div>
    <table class="table table-condensed table-bordered" id="tbRetur">
        <thead>
            <tr>
                <th>No Faktur</th>
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
                        <td><?php echo $l->no_faktur_penjualan ?></td>
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