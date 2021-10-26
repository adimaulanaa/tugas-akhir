<?php
$this->load->library("EscPos.php");

function buatBaris1Kolom($kolom1)
  {
      // Mengatur lebar setiap kolom (dalam satuan karakter)
      $lebar_kolom_1 = 30;

      // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
      $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

      // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
      $kolom1Array = explode("\n", $kolom1);

      // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
      $jmlBarisTerbanyak = count($kolom1Array);

      // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
      $hasilBaris = array();

      // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
      for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

          // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
          $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

          // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
          $hasilBaris[] = $hasilKolom1;
      }

      // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
      return implode($hasilBaris, "\n") . "\n";
  }

	function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 8;
            $lebar_kolom_2 = 10;
            $lebar_kolom_3 = 10;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode($hasilBaris, "\n") . "\n";
        }



	function buatBaris4Kolom($kolom1, $kolom2, $kolom3, $kolom4) {
	            // Mengatur lebar setiap kolom (dalam satuan karakter)
	            $lebar_kolom_1 = 7;
	            $lebar_kolom_2 = 2;
	            $lebar_kolom_3 = 10;
	            $lebar_kolom_4 = 10;

	            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
	            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
	            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
	            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
	            $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

	            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
	            $kolom1Array = explode("\n", $kolom1);
	            $kolom2Array = explode("\n", $kolom2);
	            $kolom3Array = explode("\n", $kolom3);
	            $kolom4Array = explode("\n", $kolom4);

	            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
	            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

	            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
	            $hasilBaris = array();

	            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
	            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

	                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
	                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
	                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

	                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
	                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
	                $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

	                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
	                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
	            }

	            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
	            return implode($hasilBaris, "\n") . "\n";
	        }
					function buatBaris4Kolom1($kolom1, $kolom2, $kolom3, $kolom4) {
					            // Mengatur lebar setiap kolom (dalam satuan karakter)
											$lebar_kolom_1 = 6;
					            $lebar_kolom_2 = 6;
					            $lebar_kolom_3 = 8;
					            $lebar_kolom_4 = 8;

					            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
					            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
					            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
					            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
					            $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

					            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
					            $kolom1Array = explode("\n", $kolom1);
					            $kolom2Array = explode("\n", $kolom2);
					            $kolom3Array = explode("\n", $kolom3);
					            $kolom4Array = explode("\n", $kolom4);

					            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
					            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

					            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
					            $hasilBaris = array();

					            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
					            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

					                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
					                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
					                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

					                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
					                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
					                $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

					                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
					                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
					            }

					            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
					            return implode($hasilBaris, "\n") . "\n";
					        }

try {
		// Enter the device file for your USB printer here
	  // $connector = new Escpos\PrintConnectors\FilePrintConnector("/dev/usb/lp0");
		// $connector = new WindowsPrintConnector("print_kasir");
		$connector = new Escpos\PrintConnectors\WindowsPrintConnector("nama_printer");
		// $profile = CapabilityProfile::load("simple");

		/* Print a "Hello world" receipt" */
		// $printer = new Escpos\Printer($connector);
		$printer = new Escpos\Printer($connector);

		$printer -> text($toko->nm_toko);
		$printer -> text("\n");
		$printer -> text($toko->almt_toko);
		$printer -> text("\n");
		$printer -> text($faktur->no_faktur_penjualan);
		$printer -> text("\n");
		$printer -> text($tgl . " " . $waktu);
		$printer -> text("\n");

		$printer -> text("--------------------------------\n");
		// $printer -> text(buatBaris4Kolom1("Jumlah","Diskon","Harga","Subtotal"));
    $printer -> text(buatBaris3Kolom("Jumlah","Harga","Subtotal"));
		$printer -> text("--------------------------------\n");
		foreach ($produk as $key){

			$printer -> text(buatBaris1Kolom($key->nm_barang));
			// $printer -> text(buatBaris4Kolom($key->jumlah,number_format($key->diskonpersen,  0, ',', '.'),number_format($key->harga, 0, ',', '.'),number_format($key->sub_total_jual, 0, ',', '.')));
      	$printer -> text(buatBaris3Kolom($key->jumlah,number_format($key->harga, 0, ',', '.'),number_format($key->sub_total_jual, 0, ',', '.')));
      	$printer -> text(buatBaris3Kolom("",number_format($key->diskonpersen,  0, ',', '.'),number_format($key->diskonrp, 0, ',', '.')));

			$total_item += $key->jumlah;
			$subtotal += $key->sub_total_jual;

      $total_diskon += $key->diskonrp;

		}
		$printer -> text("--------------------------------\n");
		$printer -> text(buatBaris4Kolom("","","Total :",number_format($subtotal, 0, ',', '.') ));

    if($bayar != 0){
      $printer -> text(buatBaris4Kolom("","","Tunai :",number_format($bayar, 0, ',', '.') ));
    }
    if($debet != 0){
      $printer -> text(buatBaris4Kolom("","","Transfer :",number_format($debet, 0, ',', '.') ));
    }
    if($total_diskon != 0){
      $printer -> text(buatBaris4Kolom("","","Diskon :",number_format($total_diskon, 0, ',', '.') ));
    }
		$printer -> text(buatBaris4Kolom("","","Kembali :",number_format($kembali, 0, ',', '.') ));
		$printer -> text("--------------------------------\n");
    $printer -> text("TERIMA KASIH ATAS KUNJUNGAN ANDA\n");
    $printer -> text($toko->tlp_toko);
		$printer -> text("\n");
		$printer -> text("\n");
		$printer -> text("\n");
		$printer -> text("\n");
		// $printer -> cut();
		// $printer -> close();

		/* Close printer */
		$printer -> close();
} catch (Exception $e) {
	echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}
