<table width="250" border="0" align="center">
    <tr>
        <td><?php echo $faktur->no_faktur_penjualan; ?></td>
        <td colspan="3" align="right"><?php echo date('d-m-Y', strtotime($faktur->tgl_penjualan)) . " " . $faktur->waktu ?></td>

    </tr>
    <?php foreach ($produk->result() as $key) : ?>
        <tr>
            <td>[<?php echo $key->kd_barang ?>]
                <?php if ($key->diskonpersen > 0) : ?>
                    <i> ~ Disc. <?php echo $key->diskonpersen ?> %</i>
                <?php endif ?>
                <br>

                <div style="font-size: 10px;text-transform:uppercase;"><?php echo $key->nm_barang ?></div>
            </td>
            <td valign="bottom"><?php echo $key->jumlah ?></td>
            <td valign="bottom">
                <div align="right"><?php echo number_format($key->harga, 0, ',', '.') ?></div>
            </td>
            <td valign="bottom">
                <div align="right"><?php echo number_format($key->sub_total_jual, 0, ',', '.') ?></div>
            </td>
        </tr>
        <?php
        $total_item += $key->jumlah;
        $subtotal += $key->sub_total_jual;
        ?>
    <?php endforeach ?>
    <tr>
        <td colspan="4">
            <hr />
        </td>
    </tr>
    <tr>
        <td>TOTAL</td>
        <td colspan="2"><?php echo $total_item ?> ITEM</td>
        <td>
            <div align="right"><?php echo number_format($subtotal, 0, ',', '.') ?></div>
        </td>
    </tr>
    <tr>
        <td align="right">DISKON</td>
        <td></td>
        <td colspan="2">
            <div align="right"><?php echo number_format($faktur->diskon, 0, ',', '.') ?></div>
        </td>
    </tr>
    <tr>
        <td align="right">GRAND TOTAL</td>
        <td></td>
        <td colspan="2">
            <div align="right"><?php echo number_format($faktur->total_penjualan_sdiskon, 0, ',', '.') ?></div>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="center">
            <hr />
            <p>TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
        </td>
    </tr>
</table>


<p>
    <label>App Key</label>
    <input type="text" id="app-key" value="8073892276">
</p>
<p>
    <label>App Port</label>
    <input type="text" id="app-port" value="1811">
    <input type="text" name='nama'>
</p>

<p id="error"></p>

<button id="open">Open</button>
<button onclick="onClick()">Print</button>
<button onclick="onClick1()">test</button>

<p>
    ** Please, start Recta-Host before. <br>
    ** If you haven't install Recta-Host yet, goto
    <a href="https://github.com/adenvt/recta-host">here</a>
</p>

<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<!-- <script type="text/javascript">
    function onClick1 () {
        $('#nofaktur').on('input',function(){
                
            var nofaktur=$(this).val('$nofaktur');
            console.log('$nofaktur')
            $.ajax({
                type : "POST",
                url  : "<?php echo base_url('kasir/test_print/') ?>",
                dataType : "JSON",
                data : {nofaktur: nofaktur},
                cache:false,
                success: function(data){
                    $.each(data,function(nofaktur, produk, waktu, tgl){
                        $('[name="nama"]').val(data.produk);
                        $('[name="harga"]').val(data.waktu);
                        $('[name="satuan"]').val(data.tgl);
                            
                    });
                        
                }
            });
            return false;
        });
    }
</script> -->
<script>

    // var id = $('#open').val();
    // $.ajax({
    //     url: "<?php echo base_url('kasir/test_print/') ?>" + id,
    //     type: "GET",
    //     dataType: "JSON",
    //     success: function(data) {
    //     if (data[0] === undefined) return;
    //     $('#name').text(data[0].name);
    //     $('#address').text(data[0].address);
    //     $('#telp').text(data[0].telp);
    //     }
    // });

    // var printer = new Recta('8073892276', '1811')
    
    // function onClick() {
    //     printer.open().then(function() {
    //         printer.align('center')
    //             .text('faktur')
    //             .bold(true)
    //             .text('This is bold text')
    //             .bold(false)
    //             .underline(true)
    //             .text('This is underline text')
    //             .underline(false)
    //             .barcode('UPC-A', '123456789012')
    //             .cut()
    //             .print()
    //     })
    // }
</script>