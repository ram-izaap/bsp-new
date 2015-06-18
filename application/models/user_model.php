<?php
safe_include("models/app_model.php");
class User_Model extends App_model {
    
    
    function __construct()
    {
        parent::__construct();
        $this->_table = 'users';
    }

    

    
}
?>