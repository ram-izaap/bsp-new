<section class="forminfo1 clearfix full mb40">
	<div class="l_sec pull-left">
		<div class="row clearfix">
			<div class="lsec">COMPANY NAME:</div> <div class="rsec"><?php echo $order_data->name;?></div>
		</div>

		<div class="row clearfix"><div class="lsec">SHIP TO ADDRESS:</div> <div class="rsec"><p><?php echo $order_data->ship_to;?></p></div></div>

		<div class="row clearfix"><div class="lsec">BILL TO ADDRESS:</div> <div class="rsec"><p><?php echo $order_data->bill_to;?></p></div></div>

		<div class="row clearfix"><div class="lsec">BUYER’S NOTES:</div> <div class="rsec"><p><?php echo $order_data->notes;?></p></div></div>

		<div class="half pull-left">
			<div class="row clearfix">
				<div class="lsec wid_auto">START DATE:</div> <div class="rsec wid_auto"><p><?php echo $order_data->start_date;?></p>
			</div></div>
		</div>
		<div class="half pull-right">
			<div class="row clearfix pull-right"><div class="lsec wid_auto nowrap">COMPLETION DATE:</div> <div class="rsec wid_auto"><?php echo $order_data->end_date;?>
		</div></div>
	</div>

</div>

<div class="r_sec pull-right">
	<div class="row clearfix"><div class="lsec">PO#:</div> <div class="rsec"><?php echo $order_data->po;?></div></div>

	<div class="row clearfix"><div class="lsec">TERMS:</div> <div class="rsec"><?php echo $order_data->terms;?></div></div>

	<div class="row clearfix"><div class="lsec">BUYERS EMAIL:</div> <div class="rsec"><?php echo $order_data->buyer_email;?></div></div>

	<div class="row clearfix"><div class="lsec">ACCOUNT EXECUTIVE:</div> <div class="rsec"><?php echo $order_data->executive;?></div></div>

	<div class="row clearfix">
		<div class="lsec wid_auto"><small>TOTAL  (without shipping):</small>
			<span class="total text-center wid_auto">$<?php echo number_format($order_total, 2, '.', '');?></span>
		</div> 

	</div>

	<!--<button class="btn btn-primary btn-large pull-right">CREATE<br/>LINK</button>-->

</div>

</section>

<section class="cartview clearfix">
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
			<td align="left" class="style"><?php echo $parent['sku'];?></td>
			<td align="center" class="desc"><?php echo $parent['description'];?></td>
			<td colspan="2" align="right" class="color productlist">
				<div class="sizes">
					<ul class="mb0">
						<li></li>
						<?php 
							foreach ($options[2] as $size) 
							{
								echo "<li><small>$size</small></li>";
							}
						?>
					</ul>
					<div class="clearfix"></div>
					<?php foreach ($pdetails[$parent_id] as $color_id => $varaints):?>
					<ul>
						<li class="text-right"><?php echo $options[1][$color_id];?></li>
						<?php foreach ($options[2] as $size_id=>$size_name): ?>
							<?php 
								$cpid = isset($varaints[$size_id]['id'])?$varaints[$size_id]['id']:0;;
								if( $cpid && isset($cart_data['products'][$parent_id][$cpid]) ): ?>
								<li><input type="text" value="<?php echo $cart_data['products'][$parent_id][$cpid];?>" disabled></li>
							<?php else: ?>
								<li><input class="soldout" disabled name="" value="SOLD&#x00A;OUT" type="submit"></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<div class="clearfix"></div>
					<?php endforeach;?>	
						
				</div>
			</td>
			<td colspan="3" align="center" class="unitcost productlist">
				<div class="sizes">
					<ul class="subtot">
						<li>$<?php echo number_format($parent['price'], 2, '.', '');?> x</li>
						<li><input type="text" value="<?php echo $parent['order_qty'];?>" disabled></li>
						<li>= $<?php echo number_format($parent['sub_total'], 2, '.', '');?></li>
					</ul>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>					
	</table>

</section>