<div class="pdf_containner">

<header class="clearfix">
    <a href="#">
        <img src="<?php echo base_url('public/img/logo.jpg');?>" width="93" height="89" alt="Clarasunwoo.com Logo">
    </a>
</header>

<section class="cartview clearfix" style="margin-bottom:20px;">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="50%" align="left" style=" padding:0px;font-size:14px;" valign="top">
				<table>
					<tr>
						<td align="left" style=" padding:2px 5px;font-size:14px;"><b>COMPANY NAME:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->name;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;font-size:14px;"><b>SHIP TO ADDRESS:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->ship_to;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;font-size:14px;"><b>BILL TO ADDRESS:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->bill_to;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;font-size:14px;"><b>BUYER’S NOTES:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->notes;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;font-size:14px;"><b>START DATE:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->start_date;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;font-size:14px;"><b>COMPLETION DATE:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->end_date;?></td>
					</tr>
				</table>

			</td>

			<td width="50%" align="left" style=" padding:0px;font-size:14px;" valign="top">
				<table cellpadding="5">
					<tr>
						<td align="left" style=" padding:2px 5px;;font-size:14px;"><b>PO#:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->po;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;;font-size:14px;"><b>TERMS:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->terms;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;;font-size:14px;"><b>BUYERS EMAIL:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->buyer_email;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;;font-size:14px;"><b>ACCOUNT EXECUTIVE:</b></td>
						<td style=" padding:0px;font-size:14px;"><?php echo $order_data->executive;?></td>
					</tr>
					<tr>
						<td align="left" style=" padding:2px 5px;;font-size:14px;"><b><small>TOTAL  (without shipping):</small></b></td>
						<td style=" padding:0px;font-size:25px;"><b>$<?php echo number_format($order_total, 2, '.', '');?></b></td>
					</tr>
				</table>

			</td>
		</tr>

	</table>

</section>

<section class="cartview clearfix" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th align="left">Style</th>
			<th align="center">Description</th>
			<th align="left" class="color">Color</th>
			<th align="left">Size Scale</th>
			<th align="center">Unit<br>	Cost</th>
			<th align="center">Total<br>QTY</th>
			<th align="center">Total<br>Cost</th>
		</tr>

		<?php foreach ($parent_details as $parent_id => $parent): ?>
		<tr>
			<td align="left" class="style" style=" padding:0px;"><?php echo $parent['sku'];?></td>
			<td align="center" class="" style=" padding:0px;"><?php echo $parent['description'];?></td>
			<td colspan="2" align="left" style=" padding:0px;">
				<table>
					<tr>
						<td style=" padding:0px;">&nbsp;</td>
						<?php 
							foreach ($options[2] as $size) 
							{
								echo '<td style=" padding:2px 5px 0px;font-size:12px;"><small><b>'.$size.'</b></small></td>';
							}
						?>
					</tr>
					
					<?php foreach ($pdetails[$parent_id] as $color_id => $varaints):?>
					<tr>
						<td style=" padding:0px;font-size:12px;"><b><?php echo $options[1][$color_id];?></b></td>
						<?php foreach ($options[2] as $size_id=>$size_name): ?>
							<?php  $cpid = isset($varaints[$size_id]['id'])?$varaints[$size_id]['id']:0; ?>
							<?php if( $cpid && isset($cart_data['products'][$parent_id][$cpid]) ): ?>
								<td style="border:1px solid #333;padding:0px 9px;"><?php echo $cart_data['products'][$parent_id][$cpid];?></td>
							<?php else: ?>
								<td style="border:1px solid #333;padding:0px;">SOLD&#x00A;OUT</td>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
					<?php endforeach;?>	
					
				</table>
				
			</td>
			<td colspan="3" align="" style=" padding:0px;text-align:center;">
				<span style="color:#333;font-weight:bold;">$<?php echo number_format($parent['price'], 2, '.', '');?> x</span>
				<span><?php echo $parent['order_qty'];?></span>
				<span style="color:#333;font-weight:bold;">= $<?php echo number_format($parent['sub_total'], 2, '.', '');?></span>
											
			</td>
			
		</tr>
		<?php endforeach; ?>					
	</table>

</section>

<hr/>

<footer class="clearfix" style="width:25%;float:right;border-bottom:0 none;">
    <div class="footbg"></div>
    <section>
        <h2>Clara S. inc</h2>
        <p>142 W.36th street, fl 14<br>
          New york, ny  10018</p>
        <p>P: 212-564-0736</p>
        <p>F: 212-564-1098</p>
        <p>E: <a href="mailto:iNFO@clarasunwoo.com">iNFO@clarasunwoo.com</a></p>
        <p>© 2015 CLARA S. INC</p>
    </section>
</footer>

</div>