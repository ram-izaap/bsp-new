<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Clara Sunwoo (Official Site)</title>        

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        
        <link href="<?php echo base_url();?>/public/css/jquery-ui.min.css" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>/public/css/style.css" media="screen" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>public/img/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
        <!-- Scripts -->

        <script>
          //declare global JS variables here
          var base_url = '<?php echo base_url();?>';
          var current_controller = '<?php echo $this->uri->segment(1, 'index');?>';
          var current_method = '<?php echo $this->uri->segment(2, 'index');?>';     
        </script>
        
        <script type="text/javascript" src="<?php echo base_url();?>/public/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/public/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/public/js/custom.js"></script>
        

    </head>
    <body>

        <div class="wrapper">
            <div class="container">

                <!--header start here-->
                <header class="clearfix">
                    <a href="#">
                        <img src="<?php echo base_url('public/img/logo.jpg');?>" width="93" height="89" alt="Clarasunwoo.com Logo">
                    </a>
                </header>
                 <!--header end here-->  

                <!--content start here-->
                <div class="content_inner clearfix">

                    <?php echo $content; ?>

                </div>

                 <!--footer start here-->
                <footer class="clearfix">
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
                <!--footer end here-->

            </div>
        </div>        
        

        
        
                
        <script>
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

    </body>

</html>
