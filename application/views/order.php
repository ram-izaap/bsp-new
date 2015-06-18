	<?php //echo '<pre>';print_r($order_data);?>
<form method="post" id="order-form">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<section class="executive_ara clearfix mb40">

		<div class="stylefor">
			<h2>STYLES FOR</h2>
			<h3><?php echo $order_data['name'];?></h3>
		</div>

		<div class="executive_info">
			<p><img src="<?php echo base_url('public/img/userimg.jpg');?>" width="185" height="176" alt=""></p>
			<p><h2><span>curated by</span> <?php echo $order_data['executive'];?></h2></p>
		</div>

	</section>

	<section class="forminfo1 clearfix full mb40">
		<div class="l_sec pull-left">
			<label for="textfield"></label>
			<input placeholder="COMPANY NAME:" type="text" name="name" id="name" value="<?php echo set_value('name', $order_data['name']);?>" >
			<?php echo form_error('name', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<textarea placeholder="SHIP TO ADDRESS:" name="ship_to" cols=""><?php echo set_value('ship_to', $order_data['ship_to']);?></textarea>
			<?php echo form_error('ship_to', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<textarea placeholder="BILL TO ADDRESS:" name="bill_to" cols=""><?php echo set_value('bill_to', $order_data['bill_to']);?></textarea>
			<?php echo form_error('bill_to', '<span class="error">', '</span>'); ?>

			<textarea placeholder="BUYER’S NOTES:" name="notes" cols=""><?php echo set_value('notes', $order_data['notes']);?></textarea>
			<?php echo form_error('notes', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input class="startdate pull-left mr5" placeholder="START DATE:" type="text" name="start_date" id="start_date" value="<?php echo set_value('start_date', $order_data['start_date']);?>">
			<?php echo form_error('start_date', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input class="enddate pull-right ml5" placeholder="COMPLETION DATE:" type="text" name="end_date" id="end_date" value="<?php echo set_value('end_date', $order_data['end_date']);?>">
			<?php echo form_error('end_date', '<span class="error">', '</span>'); ?>

		</div>

		<div class="r_sec pull-right">
			<label for="textfield"></label>
			<input placeholder="PO#:" type="text" name="po" id="po" value="<?php echo set_value('po', $order_data['po']);?>">
			<?php echo form_error('po', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input placeholder="TERMS:" type="text" name="terms" id="terms" value="<?php echo set_value('terms', $order_data['terms']);?>">
			<?php echo form_error('terms', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input placeholder="BUYERS EMAIL:" type="text" name="buyer_email" id="buyer_email" value="<?php echo set_value('buyer_email', $order_data['buyer_email']);?>"> 
			<?php echo form_error('buyer_email', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input placeholder="ACCOUNT EXECUTIVE:" type="text" name="executive" id="executive" value="<?php echo set_value('executive', $order_data['executive']);?>"> 
			<?php echo form_error('executive', '<span class="error">', '</span>'); ?>

			<div class="row clearfix">
				<div class="lsec wid_auto">TOTAL  (without shipping):
					<p class="total text-center grand-total">$00.00</p>
				</div> 
				<div class="rsec wid_auto pull-right suborder">
					<input class="btn btn-primary btn-large submit-order" type="button" value="SUBMIT&#x00A;ORDER">
				</div>

			</div>

			<!--<button class="btn btn-primary btn-large pull-right">CREATE<br/>LINK</button>-->

		</div>

	</section>

	<div class="productlist clearfix">

		<?php foreach ($parent_details as $parent_id => $parent): ?>
			<?php 
				$mpc = current(array_keys($pdetails[$parent_id]));
				$color_name = $options[1][$mpc];
			?>
			<div class="item">
				<a href="#"><img class="prothumb main" src="<?php echo base_url('public/media/'.$parent['sku'].'-'.strtoupper($color_name).'.jpg');?>" width="380" height="700" alt=""> </a>
				<div style="position: absolute; top: 0px;" id="videoModal_<?php echo $parent_id;?>">
					<video height="700" width="380" loop controls id="videoPlayer_<?php echo $parent_id;?>">
						<source type="video/mp4" src="<?php echo base_url('public/media/'.$parent['sku'].'.mp4');?>"></source>
						<source type="video/ogg" src="<?php echo base_url('public/media/'.$parent['sku'].'.ogg');?>"></source>
						Your browser does not support the video tag.
					</video> 
				</div>
				<div class="pro_shortinfo">
					<div class="colorswaprow pull-left">
						<ul>
							<?php foreach ($pdetails[$parent_id] as $color_id => $varaints):?>
							<?php
								$color_name = $options[1][$color_id];
								$swatch_img = $parent['sku'].'-'.strtoupper($color_name).'.jpg';
							?>
							<li class="<?php echo strtolower($color_name);?>">
								<a href="<?php echo base_url("public/media/$swatch_img");?>" onclick="swap(this); return false;"></a>
							</li>
							<?php endforeach;?>
						</ul>
						
					</div>
					<div class="video pull-right">
						<img  class="videoPlay" data-id="<?php echo $parent_id;?>" id="videoPlay_<?php echo $parent_id;?>" src="<?php echo base_url('public/img');?>/play_icon.png" width="66" height="75" alt="Play Video">
					</div>

					<div class="clearfix"></div>
					<div class="text-center">

						<h1><a href="#"><?php echo $parent['sku'];?></a></h1>

						<p> <?php echo $parent['description'];?></p>

						<div class="clearfix"></div>

						<div class="sizes">
							<ul>
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
							<ul class="colors" >
								<li class="text-right"><?php echo $options[1][$color_id];?></li>
								<?php foreach ($options[2] as $size_id=>$size_name): ?>
									<?php if( in_array( $size_id, array_keys($varaints) ) ): $cpid = $varaints[$size_id]['id'];?>
										<li><input class="cart_qty" data-min-qty="<?php echo $parent['min_qty'];?>" data-psku="<?php echo $parent['sku'];?>" data-sku-color="<?php echo $parent['sku'].'-'.$color_id;?>" name="product[<?php echo $parent_id;?>][<?php echo $cpid;?>]" type="text"></li>
									<?php else: ?>
										<li><input class="soldout" disabled name="" value="SOLD&#x00A;OUT" type="submit"></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
							<?php endforeach;?>	

							<div class="clearfix"></div>
							<ul class="subtot" data-sku="<?php echo $parent['sku'];?>" data-price="<?php echo $parent['price'];?>" data-min-qty="<?php echo $parent['min_qty'];?>" >
								<li>$<?php echo number_format($parent['price'], 2, '.', '');?> x</li>
								<li>
									<input disabled type="submit"  value="0" class="tqty">
								</li>
								<li class="total_price" >= $0 </li>
							</ul>
						</div>

					</div>

				</div>
			</div>			
		<?php endforeach; ?>	
	
	</div>

	<!--//closeouts section-->
	<?php if( count($close_outs) ): ?>
	<section class=" clearfix">
		<div class="closeouts"><h2>CLOSEOUTS</h2></div>
		<div class="productlist clearfix">

			<?php foreach ($close_outs as $parent_id => $parent): ?>
				<div class="item">
					<a href="#"><img class="prothumb main" src="<?php echo base_url('public/img');?>/proimg.jpg" width="380" height="700" alt=""> </a>
					<div style="position: absolute; top: 0px;" id="videoModal_<?php echo $parent_id;?>">
						<video height="700" width="380" loop controls id="videoPlayer_<?php echo $parent_id;?>">
							<source type="video/mp4" src="<?php echo base_url('public/media/'.$parent['sku'].'.mp4');?>"></source>
							<source type="video/ogg" src="<?php echo base_url('public/media/'.$parent['sku'].'.ogg');?>"></source>
							Your browser does not support the video tag.
						</video> 
					</div>
					<div class="pro_shortinfo">
						<div class="colorswaprow pull-left">
							<ul>
								<?php foreach ($pdetails[$parent_id] as $color_id => $varaints):?>
								<?php
									$color_name = $options[1][$color_id];
									$swatch_img = $parent['sku'].'-'.strtoupper($color_name).'.jpg';
								?>
								<li class="<?php echo strtolower($color_name);?>">
									<a href="<?php echo base_url("public/media/$swatch_img");?>" onclick="swap(this); return false;"></a>
								</li>
								<?php endforeach;?>
							</ul>
							
						</div>
						
						<div class="video pull-right">
							<img  class="videoPlay" data-id="<?php echo $parent_id;?>" id="videoPlay_<?php echo $parent_id;?>" src="<?php echo base_url('public/img');?>/play_icon.png" width="66" height="75" alt="Play Video">
						</div>
						<div class="clearfix"></div>
						<div class="text-center">

							<h1><a href="#"><?php echo $parent['sku'];?></a></h1>

							<p> <?php echo $parent['description'];?></p>

							<div class="clearfix"></div>

							<div class="sizes">
								<ul>
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
								<ul class="colors">
									<li class="text-right"><?php echo $options[1][$color_id];?></li>
									<?php foreach ($options[2] as $size_id=>$size_name): ?>
										<?php if( in_array( $size_id, array_keys($varaints) ) ): $cpid = $varaints[$size_id]['id'];?>
											<li><input class="cart_qty" data-min-qty="<?php echo $parent['min_qty'];?>" data-psku="<?php echo $parent['sku'];?>" data-sku-color="<?php echo $parent['sku'].'-'.$color_id;?>" name="product[<?php echo $parent_id;?>][<?php echo $cpid;?>]" type="text"></li>
										<?php else: ?>
											<li><input class="soldout" disabled name="" value="SOLD&#x00A;OUT" type="submit"></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
								<?php endforeach;?>	

								<div class="clearfix"></div>
								<ul class="subtot" data-sku="<?php echo $parent['sku'];?>" data-price="<?php echo $parent['price'];?>" data-min-qty="<?php echo $parent['min_qty'];?>" >
									<li>$<?php echo number_format($parent['price'], 2, '.', '');?> x</li>
									<li>
										<input disabled type="submit"  value="0" class="tqty">
									</li>
									<li class="total_price" >= $0 </li>
								</ul>
							</div>

						</div>

					</div>
				</div>			
			<?php endforeach; ?>
			<!--item loop-->


			

		</div>
	</section>
	<?php endif; ?>
	<!--closeouts section\\-->

	<!--//Total submit-order section-->
	<div class="submitorder row wid_auto pull-right clearfix">
		<div class="lsec wid_auto mr0"><p class="total text-center"><span>TOTAL:</span> <span class="grand-total">$00.00</span></p>
		</div> 

		<div class="rsec wid_auto pull-right suborder">		
				<input class="btn btn-primary btn-large submit-order" type="button" value="SUBMIT&#x00A;ORDER">		
		</div>
	</div>
		<!--Total submit-order section\\-->

</form>