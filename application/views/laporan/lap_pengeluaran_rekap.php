<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Aplikasi Point of Sales" />
    <meta name="author" content="yoriadiatma" />
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/') ?>/img/logo.png"/>
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Laporan Pengeluaran Rekap</title>
    <link href="<?php echo base_url() ?>/assets/css/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/style.css" rel="stylesheet" />

</head>
<body>
<div class="container">
    <div align="right" class="no-print" id="formFilter" style="background-color: #F5F5F5;padding: 4px">
    <a href=""><button type="button" class="btn btn-success" onclick="window.print();return false;">Print</button></a>
    </div>
    <h4 align="center">LAPORAN PENGELUARAN REKAP</h4>
    <h5 align="center">TOKO : <?php echo $toko->nm_toko ?></h5>
    <h5 align="center">BULAN :  <?php echo bulan($bln) . " " . $thn; ?></h5>

<table id="tbBiaya" class="table table-bordered table-responsive">
<thead>
    <tr>
        <th style="width:50px;">No</th>
        <th>Tanggal</th>
        <th>Tenaga Kerja</th>
        <th>Investasi</th>
        <th>Operasional</th>
        <th>Lainnya</th>
        <th>Total Biaya</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($biaya->result() as $key): ?>
<?php
$query_tenaga_kerja = $this->db->query("SELECT SUM(biaya) AS tk FROM tabel_biaya WHERE tgl='$key->tgl' AND jenis='Tenaga Kerja'");
$query_investasi = $this->db->query("SELECT SUM(biaya) AS inv FROM tabel_biaya WHERE tgl='$key->tgl' AND jenis='Investasi'");
$query_operasional = $this->db->query("SELECT SUM(biaya) AS opr FROM tabel_biaya WHERE tgl='$key->tgl' AND jenis='Operasional'");
$query_lainnya = $this->db->query("SELECT SUM(biaya) AS ll FROM tabel_biaya WHERE tgl='$key->tgl' AND jenis='Lainnya'");

$a = $query_tenaga_kerja->row_array();
$b = $query_investasi->row_array();
$c = $query_operasional->row_array();
$d = $query_lainnya->row_array();
$tot = $a['tk'] + $b['inv'] + $c['opr'] + $d['ll'];
?>
    <tr>
        <td style="text-align:center;"><?php echo $no++ ?></td>
        <td><?php echo date_indo($key->tgl) ?></td>
        <td style="text-align:right;"><?php echo number_format($a['tk'], 0, ',', '.') ?></td>
        <td style="text-align:right;"><?php echo number_format($b['inv'], 0, ',', '.') ?></td>
        <td style="text-align:right;"><?php echo number_format($c['opr'], 0, ',', '.') ?></td>
        <td style="text-align:right;"><?php echo number_format($d['ll'], 0, ',', '.') ?></td>
        <td style="text-align:right;"><?php echo number_format($tot, 0, ',', '.') ?></td>
    </tr>
    <?php
$tot_a += $a['tk'];
$tot_b += $b['inv'];
$tot_c += $c['opr'];
$tot_d += $d['ll'];
$tot_tot += $tot;
?>
    <?php endforeach?>
</tbody>
<tfoot>
    <tr>
        <td colspan="2" style="text-align:center;"><b>Total</b></td>
        <td style="text-align:right;"><b>Rp <?php echo number_format($tot_a, 0, ',', '.') ?></b></td>
        <td style="text-align:right;"><b>Rp <?php echo number_format($tot_b, 0, ',', '.') ?></b></td>
        <td style="text-align:right;"><b>Rp <?php echo number_format($tot_c, 0, ',', '.') ?></b></td>
        <td style="text-align:right;"><b>Rp <?php echo number_format($tot_d, 0, ',', '.') ?></b></td>
        <td style="text-align:right;"><b>Rp <?php echo number_format($tot_tot, 0, ',', '.') ?></b></td>
</tfoot>
</table>
</div>
</body>
</html>