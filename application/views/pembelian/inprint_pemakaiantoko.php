<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Reprint Faktur Pemakaian Toko</title>
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/') ?>/img/logo-alca.png" />
  <style type="text/css">
    body {
      font-family: Calibri;
      font-size: 8pt;
      color: #000;
    }
  </style>
</head>

<body>
  <table width="777" border="0" align="center">
    <tr>
      <td colspan="4" align="center">
        <font color="#000000" size="+4" style="text-transform:uppercase"><strong><?php echo $toko->nm_toko; ?></strong></font>
        <br>
        <i  color="#000000" size="+3" style="text-transform:uppercase"><strong><?php echo $toko->almt_toko ?> <?php echo $toko->kota_toko ?></strong></i>
        <br>
        <i color="#000000" size="+3" style="text-transform:uppercase"><strong><?php echo $toko->tlp_toko ?></strong></i>
      </td>
    </tr>
    <tr >
      <td><h3><?php echo $faktur->no_faktur_pemakaiantoko; ?></h3></td>
      <td colspan="3" align="right"><h3><?php echo $tgl . " " . $waktu ?></h3></td>
    </tr>
    <tr >
      <td colspan="7">
        <hr />
      </td>
    </tr>
  </table>
  <table width="777" colspan="7" border="0" align="center">
    <?php foreach ($list as $key) : ?>
      <tr>
        <td valign="bottom" style="font-size: 13px;text-transform:uppercase;"><?php echo $key->kd_barang ?></td>
        <td>

          <br>
          <div style="font-size: 13px;text-transform:uppercase;"><?php echo $key->nm_barang ?></div>
        </td>

        <td valign="bottom" style="font-size: 13px;text-transform:uppercase;"><?php echo $key->jumlah ?></td>
        <td valign="bottom" style="font-size: 13px;text-transform:uppercase;">PCS</td>
        <!-- <td valign="bottom">
          <?php if ($key->diskonpersen > 0) : ?>
            <i><?php echo $key->diskonpersen ?> %</i>
          <?php endif ?>
        </td> -->
        <td valign="bottom">
          <div align="right" style="font-size: 13px;text-transform:uppercase;"><?php echo number_format($key->harga, 0, ',', '.') ?></div>
        </td>
        <td valign="bottom">
          <div align="right" style="font-size: 13px;text-transform:uppercase;"><?php echo number_format($key->sub_total_beli, 0, ',', '.') ?></div>
        </td>
      </tr>
      <?php
      $total_item += $key->jumlah;
      $subtotal += $key->sub_total_beli;
      ?>
    <?php endforeach ?>
    <tr>
      <td colspan="7">
        <hr />
      </td>
    </tr>
    <tr>
      <td style="font-size: 17px;text-transform:uppercase;"><strong>TOTAL</strong></td>
      <td> </td>
      <td colspan="3" style="font-size: 15px;text-transform:uppercase;"><b><?php echo $total_item ?></b> </td>
      <td>
        <div align="right" style="font-size: 15px;text-transform:uppercase;"><b><?php echo number_format($subtotal, 0, ',', '.') ?></b></div>
      </td>
    </tr>
    
    <tr>
    <tr>
      <td colspan="6" align="center">
        <hr />
        <!-- <p>TERIMA KASIH ATAS KUNJUNGAN ANDA</p> -->
      </td>
    </tr>
  </table>
</body>

</html>