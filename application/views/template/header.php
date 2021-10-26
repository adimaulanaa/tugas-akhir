<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Aplikasi Gudang KC" />

    <link rel="icon" type="image/png" href="<?php echo base_url('assets/') ?>/img/logo.png" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <?php
    $query = $this->db->get('tabel_toko', 1, 0);
    $toko = $query->row();
    $namatoko = $toko->nm_toko;
    ?>
    <title><?php echo $namatoko ?></title>
    <link href="<?php echo base_url() ?>/assets/css/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/style.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/toastr.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/jquery-ui.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>/assets/css/bootstrap-select.min.css" rel="stylesheet">

</head>

<body>
    <?php
    $query = $this->db->get('tabel_toko', 1, 0);
    $toko = $query->row();
    $namatoko = $toko->nm_toko;
    ?>
    
    <?php
    $hak = $this->session->userdata('akses');
    ?>
    <!-- LOGO HEADER END-->
 
    <section class="menu-section no-print">
        <div class="container">
            <div class="row ">
                
                <div class="col-md-12"> 
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-left">
                            <li><a href="<?php echo base_url('dashboard/') ?>"><img src="<?php echo base_url('assets/') ?>/img/logo.png" alt="image/png" width="25px" height="25px"></a></li>
                            <!-- <li><a href="<?php echo base_url('dashboard/') ?>">DASHBOARD</a></li> -->
                            <?php if ($hak != 'kasir') : ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">MASTER</a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_master/kategori/') ?>">KATEGORI</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_master/satuan/') ?>">SATUAN</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_master/supplier/') ?>">SUPPLIER</a></li>
                                        <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_master/supplier/') ?>">DISKON</a></li> -->
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_master/member/') ?>">MEMBER</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_master/barang/') ?>">BARANG</a>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/stok/') ?>">PRICE BARANG</a></li>
                                </li>
                        </ul>
                        </li>
                    <?php endif ?>
                    <?php if ($hak != 'kasir') : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">STOCK</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_stok/stok/') ?>">STOK</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_stok/edit_stok') ?>">STOCKOPNAME</a></li>                                
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('manajer/kartu-stok/') ?>">MUTASI STOK BARANG</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_stok/ubah_harga/') ?>">UBAH HARGA</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/stok-min/') ?>">STOK HABIS</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_stok/perubahan_harga/') ?>">UPDATE HARGA</a></li>
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_stok/repacking/') ?>">REPACKING</a> -->
                        </li>
                        </ul>
                        </li>
                    <?php endif ?>
                    <?php if ($hak != 'kasir') : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">PEMBELIAN</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_pembelian/nomor-faktur/') ?>">PEMBELIAN BARANG</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('kasir/barang-masuk/') ?>">BARANG MASUK</a></li>
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('internaldelevery/internal-start/') ?>">INTERNAL DELEVERY</a></li> -->
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_pembelian/returbeli/') ?>">RETURN Pembelian</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('m_pembelian/carifaktur/') ?>">CEK FAKTUR Pembelian</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('pemakaiantoko/pakai-barang-start/') ?>">PEMAKAIAN TOKO</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('mutasi/mutasi-masuk-start/') ?>">MUTASI MASUK</a></li>

                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/bahan-rusak/') ?>">BAHAN RUSAK / BUSUK</a></li> -->
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/stok-min/') ?>">BAHAN MAU HABIS</a></li> -->
                            </ul>
                        </li>
                    <?php endif ?>
                    <!-- <?php if ($hak != 'kasir') : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">MENU BARANG<i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/supplier/') ?>">SUPPLIER</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/satuan/') ?>">SATUAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/barang/') ?>">ENTRY BAHAN BAKU</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/menu/') ?>">DAFTAR MENU</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/pembelian-start/') ?>">PEMBELIAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/stok/') ?>">STOK BAHAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/bahan-rusak/') ?>">BAHAN RUSAK / BUSUK</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/stok-min/') ?>">BAHAN MAU HABIS</a></li>
                            </ul>
                        </li>
                    <?php endif ?> -->
                    <?php if ($hak != 'gudang') : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">PENJUALAN</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('kasir/nomor-faktur/') ?>">PENJUALAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('kasir/retur/') ?>">RETURN PENJUALAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('mutasi/mutasi-keluar-start/') ?>">MUTASI KELUAR</a></li>
                                <?php if ($hak == 'manager') : ?>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('kasir/update-faktur/') ?>">UPDATE FAKTUR</a></li>
                                    <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('mutasi/update-mutasi-masuk/') ?>">UPDATE MASUK</a></li> -->
                                <?php endif ?>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('kasir/input-biaya/') ?>">INPUT BIAYA OPERASIONAL</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('kasir/rekap/') ?>">REKAP HARI INI</a></li>
                            </ul>
                        </li>
                    <?php endif ?>
                    <?php if ($hak == 'manager') : ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">SETTING TOKO</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('manajer/toko/') ?>">TOKO</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('manajer/user/') ?>">USER</a></li>
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/stok/') ?>">STOK BAHAN</a></li> -->
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('manajer/kartu-stok/') ?>">KARTU STOK BARANG</a></li> -->
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/nilai-persediaan/') ?>">ASSETS BARANG TOKO</a></li>
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('gudang/stok-min/') ?>">BAHAN MAU HABIS</a></li> -->
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">CRM</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('crm/profile-member/') ?>">List Member</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">GRAFIK</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <!-- <li role="presentation"><a target="_blank" role="menuitem" tabindex="-1" href="<?php echo base_url('grafik/stok-barang/') ?>">GRAFIK Stok Barang</a></li> -->
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('grafik/profit-bulanan/') ?>">GRAFIK PROFIT BULANAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('grafik/penjualan-bulanan/') ?>">GRAFIK PENJUALAN BULANAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('grafik/penjualan-tahun/') ?>">GRAFIK PENJUALAN TAHUNAN</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">LAPORAN</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/penjualan-transaksi/') ?>">PENJUALAN PER TRANSAKSI</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/penjualan-barang/') ?>">PENJUALAN PER BARANG</a></li>
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/stockopname/') ?>">Lap Stockopname</a></li> -->
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/profit/') ?>">PROFIT PENJUALAN</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('laporan/rekap/') ?>">REKAPITULASI PENJUALAN</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">LAPORAN LAIN</a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('laporan/biaya/') ?>">BIAYA-BIAYA</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/pembelian/') ?>">PEMBELIAN</a></li>
                                <!-- <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/internalkc/') ?>">INTERNAL</a></li> -->
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/stockopname/') ?>">LAP STOCKOPNAME</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/lap-mutasi-masuk/') ?>">LAP MASUK</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/lap-mutasi-keluar/') ?>">LAP KELUAR</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/lap-pemakaian-toko/') ?>">LAP PEMAKAIAN TOKO</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/retur/') ?>">RETUR PENJUALAN BARANG</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="<?php echo base_url('laporan/update-faktur/') ?>">UPDATE FAKTUR</a></li>
                            </ul>
                        </li>
                    <?php endif ?>
                    <li><a href="<?php echo base_url('login/logout/') ?>"><strong style="color: red">KELUAR</strong></a></li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>