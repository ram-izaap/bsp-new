


<?php	if( $order_links_id ):
	 
	$message = 'Your Order linki is http://bsp.local'.site_url('order/'.$order_links_id);

?>
	<section class="forminfo1 full clearfix success text-center">
  		<h2>Your order link is : <a href="<?php echo site_url('order/'.$order_links_id);?>"><?php echo site_url('order/'.$order_links_id);?></a></h2>
	</section>
<?php endif; ?>	
	
<form  method="post"  >

	<section class="forminfo1 full clearfix">



		<div class="l_sec pull-left">
			<label for="textfield"></label>
			<input placeholder="COMPANY NAME:" type="text" name="name" id="name" value="<?php echo set_value('name');?>" >
			<?php echo form_error('name', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<textarea placeholder="SHIP TO ADDRESS:" name="ship_to" cols=""><?php echo set_value('ship_to');?></textarea>
			<?php echo form_error('ship_to', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<textarea placeholder="BILL TO ADDRESS:" name="bill_to" cols=""><?php echo set_value('bill_to');?></textarea>
			<?php echo form_error('bill_to', '<span class="error">', '</span>'); ?>

			<textarea placeholder="BUYER’S NOTES:" name="notes" cols=""><?php echo set_value('notes');?></textarea>
			<?php echo form_error('notes', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input class="startdate pull-left mr5" placeholder="START DATE:" type="text" name="start_date" id="start_date" value="<?php echo set_value('start_date');?>">
			<?php echo form_error('start_date', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input class="enddate pull-right ml5" placeholder="COMPLETION DATE:" type="text" name="end_date" id="end_date" value="<?php echo set_value('end_date');?>">
			<?php echo form_error('end_date', '<span class="error">', '</span>'); ?>

		</div>
		
		<div class="r_sec pull-right">
			<label for="textfield"></label>
			<input placeholder="PO#:" type="text" name="po" id="po" value="<?php echo set_value('po');?>">
			<?php echo form_error('po', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input placeholder="TERMS:" type="text" name="terms" id="terms" value="<?php echo set_value('terms');?>">
			<?php echo form_error('terms', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input placeholder="BUYERS EMAIL:" type="text" name="buyer_email" id="buyer_email" value="<?php echo set_value('buyer_email');?>"> 
			<?php echo form_error('buyer_email', '<span class="error">', '</span>'); ?>

			<label for="textfield"></label>
			<input placeholder="ACCOUNT EXECUTIVE:" type="text" name="executive" id="executive" value="<?php echo set_value('executive');?>"> 
			<?php echo form_error('executive', '<span class="error">', '</span>'); ?>

			<!--<input class="btn btn-primary btn-large pull-right" type="submit" value="CREATE&#x00A;LINK">-->
			<button class="btn btn-primary btn-large pull-right" type="submit">CREATE<br/>LINK</button>

		</div>
	</section>



<div class="checkboxlist clearfix">

<div class="error text-center mb15"><h2><?php echo $product_validate;?></h2></div>
<?php 

	$cats = isset($_POST['categories'])?$_POST['categories']:array();
	$prdts = isset($_POST['products'])?$_POST['products']:array();
	//echo '<pre>';print_r($products);
?>
<?php if(is_array($products) && !empty($products)):?>

		<?php $i=1; foreach($products as $key => $catlist):

			$cat_name = $categories[$key];


		?>

			<ul id="catlist-<?php echo $key;?>">
				<li><label><input name="categories[]" id="catecheck" type="checkbox" class="chekbx" onclick="select_checkbox('catlist-<?php echo $key;?>')" value="<?php echo $key;?>" <?php echo in_array($key, $cats)?'checked':'';?>> <b>All <?php echo $cat_name;?></b></label></li>

				<?php foreach($catlist as $val):?>
					

					<li><label><input name="products[<?php echo $key;?>][]" id="list_products" class="chekbx"type="checkbox" value="<?php echo $val['id'];?>" <?php echo in_array($val['id'], isset($prdts[$key])?$prdts[$key]:array())?'checked':'';?>> <?php echo $val['sku'];?></label></li>

				<?php endforeach;?>	
				
			</ul>

			<?php if($i%3==0):?>
				<div class="clearfix"></div>
			<?php endif;?>	

		<?php $i++; endforeach;?>

		<ul>
			<li><label><input name="essential" id="essential" type="checkbox"  value="1"> <b>All Essentials</b></label></li>
			<li><label><input name="clearance" id="clearance" type="checkbox"  value="1"> <b>All clearance</b></label></li>
		</ul>		

    <?php else: ?>
    	<div class="text-center"> <h1>No Products Found </h1></div>
 	<?php endif;?>

</div>

</form>


<script>

function select_checkbox(elm){

	var cat = $("#"+elm).find('#catecheck');

    if(cat.is(':checked')) { // check select status
        $("#"+elm+' .chekbx').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"              
        });
    }else{
        $("#"+elm+' .chekbx').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"                      
        });        
    }   
}

  $(function() {
    $( "#start_date" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#end_date" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#end_date" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#end_date" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });
  </script>