<?php

class MY_Input extends CI_Input
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

	// --------------------------------------------------------------------

	/**
	* Clean Keys
	*
	* This is a helper function. To prevent malicious users
	* from trying to exploit keys we make sure that keys are
	* only named with alpha-numeric text and a few other items.
	*
	* @access	private
	* @param	string
	* @return	string
	*/
	function _clean_input_keys($str)
	{
		if ( ! preg_match("/^[a-z0-9:_\/\-\ ]+$/i", $str))
		{
			exit('Disallowed Key Characters.'.$str);
		}

		return $str;
	}

    // --------------------------------------------------------------------

    /**
     * Is AJAX call
     *
     * @access  public
     * @return  bool
     */
    public function is_ajax()
    {
        $is_ajax = false;
            
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['X-Requested-With'])) {
                $x_header_name = 'X-Requested-With';
            } elseif(isset($headers['x-requested-with'])) {
                $x_header_name = 'x-requested-with';
            }
            if (isset($x_header_name)) {
                $is_ajax = $headers[$x_header_name] == 'XMLHttpRequest';
            }
        }
        parse_str($_SERVER['QUERY_STRING'], $_GET);
        if (isset($_GET['ajax'])) {
            $is_ajax = true;
        }
        return $is_ajax;
    }

    // --------------------------------------------------------------------

} 

?>
