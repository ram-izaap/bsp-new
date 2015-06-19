
<div class="text-center"> <h2>Inventory Upload</h2> </div>
<?php $userdata = $this->session->userdata('userdata');
  if(is_array($userdata) && isset($userdata['username'])):
?>  
    <div class="pull-right"><a class=" btn btn-primary " href="<?php echo site_url('logout');?>">Logout </a></div>
  <?php endif; ?>
<br/>
<br/>
<br/>

<section class="forminfo1 full clearfix">

    <div class="upload-div">
        
        <form id="upload-form" enctype="multipart/form-data" name="upload-form" method="POST">
            
            <input type="file" id="csv-file" name="csv-file">
            <?php if( count($_FILES) && $upload_error != '' ):?>
                <span class="error"><?php echo $upload_error; ?></span>
            <?php endif; ?>
            <div class="clearfix"></div>

            <button class="btn btn-primary btn-large">Submit</button>

        </form>
        
    </div>
</section>

<?php if( $success_uploads ): ?>
    <section class="forminfo1 full clearfix success">
      <h3>No of successfull uploads : <span style="font-weight:bold;"><?php echo $success_uploads;?></span></h3>
    </section>
<?php endif; ?>

<?php if( count($errors) ): ?>
<section class="forminfo1 full clearfix error">
  <h3>Errors in uploading....</h3>
  <ul>
    <?php 
        foreach ($errors as $error) 
        {
           echo "<li>$error</li>";
        } 
    ?>
  </ul>
</section>
<?php endif; ?>
