<link rel="shortcut icon" href="<?php echo base_url(); ?>picture/apotek/pay.ico">

<title>Struk</title>

<script src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js" type="text/javascript"></script>

<style type="text/css">
.struk{
    font-family:'Courier New',Courier,monospace;
    width: 30%;
}

table{
   width: 100%;
}
</style>

<div class="struk">
	<table>
	    <tr>
	    	<td style="text-align:center;">APOTEK</td>
	    </tr>
	    <tr>
	      	<td style="text-align:center;">Jl. Mawar</td>
	    </tr>
	    <tr>
	      	<td style="text-align:center;">Telp. (0341) 411755</td>
	    </tr>
	    <tr>
	      	<td style="text-align:center;">
	      		<hr style="border-style: dashed;">
	      	</td>
	    </tr>
	</table>

	<br>

	<table id="tabel_barang">
		<tbody>
		<?php
			$qty = 0;
			foreach ($data2 as $key => $value) {
				$qty += $value->JUMLAH_BELI;
		?>
			<tr>
				<td colspan="3"><?php echo $value->NAMA_OBAT; ?></td>
			</tr>
			<tr>
				<td style="text-align:right;"><?php echo $value->JUMLAH_BELI; ?> @</td>
				<td style="text-align:right;"><?php echo number_format($value->HARGA,0,'.',','); ?></td>
				<td style="text-align:right;"><?php echo number_format($value->SUBTOTAL,0,'.',','); ?></td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>

	<br>

	<table>
		<tr>
			<td colspan="3">--------------------------------------(+)</td>
		</tr>
		<tr>
			<td>TOTAL ITEM</td>
			<td>:</td>
			<td><?php echo $item; ?></td>
		</tr>
		<tr>
			<td>TOTAL QTY</td>
			<td>:</td>
			<td><?php echo $qty; ?></td>
		</tr>
		<tr>
			<td>PPN(10%)</td>
			<td>:</td>
			<td><?php echo number_format($data1->PPN,0,'.',','); ?></td>
		</tr>
		<tr>
			<td>DISKON</td>
			<td>:</td>
			<td><?php echo number_format($data1->DISKON,0,'.',','); ?></td>
		</tr>
		<tr>
			<td>TOTAL HRG</td>
			<td>:</td>
			<td><?php echo number_format($data1->TOTAL,0,'.',','); ?></td>
		</tr>
		<tr>
			<td>BAYAR CASH</td>
			<td>:</td>
			<td><?php echo number_format($data1->BAYAR,0,'.',','); ?></td>
		</tr>
		<tr>
			<td colspan="3">--------------------------------------(-)</td>
		</tr>
		<tr>
			<td>KEMBALI</td>
			<td>:</td>
			<td><?php echo number_format($data1->KEMBALI,0,'.',','); ?></td>
		</tr>
		<tr>
	      	<td style="text-align:center;" colspan="3">
	      		<hr style="border-style: dashed;">
	      	</td>
	    </tr>
	</table>

	<table>
		<tr>
			<td>[<?php echo $invoice; ?>]</td>
			<td style="text-align:left;"><?php echo $data1->TANGGAL; ?></td>
			<td style="text-align:right;"><?php echo $data1->WAKTU; ?></td>
		</tr>
	</table>

	<table>
		<tr>
			<td style="width:10%;" colspan="2">KASIR</td>
			<td>: <?php echo $kasir; ?></td>
		</tr>
		<tr>
			<td style="width:10%;" colspan="2">PELANGGAN</td>
			<td>: <?php echo $data1->ATAS_NAMA; ?></td>
		</tr>
	</table>

	<br/><br/>

	<table>
	    <tr>
	    	<td style="text-align:center;">Terima Kasih Atas Kunjungan Anda</td>
	    </tr>
	    <tr>
	      	<td style="text-align:center;">Barang Yang Sudah Dibeli</td>
	    </tr>
	    <tr>
	      	<td style="text-align:center;">Tidak Bisa Ditukar Kembali</td>
	    </tr>
	</table>
</div>