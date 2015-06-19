<form  method="post"  >

<section class="forminfo1 full clearfix">

<?php //echo $url = $this->url('order', array('id' => 999), array('force_canonical' => false)); ?>

	
	<div class="l_sec pull-left">

		<div class="error"><?php echo $message;?></div>
	
		<label for="textfield"></label>
		<input placeholder="UserName" type="text" name="username" id="username" value="<?php echo set_value('username');?>" >
		<?php echo form_error('username', '<span class="error">', '</span>'); ?>

		<label for="textfield"></label>
		<input placeholder="Password" type="password" name="password" id="password">
		<?php echo form_error('password', '<span class="error">', '</span>'); ?>

		<button type="submit" class="btn btn-primary btn-large pull-right">LOGIN</button>

	</div>
			
</section>


</form>
