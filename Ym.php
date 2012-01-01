<?php
/*
 * Yahoo Mesengger Checker
 *
 * @package		Yahoo Mesengger Status Checker
 * @author		Dwi Setiyadi / @dwisetiyadi / dwi.setiyadi@gmail.com
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://dwi.web.id
 * @version		1.0
 * Last changed	16 March, 2011
 */

// ------------------------------------------------------------------------

if (!function_exists('curl_init')) {
	throw new Exception('This library needs the CURL PHP extension.');
}

/**
 * This class object
 */
class Ym {
	var $yahooID = '';
	var $offline_picture = '';
	var $online_picture = '';
	var $checkURI = 'http://opi.yahoo.com/online?u=%s&m=t';
	
	/**
	 * Constructor
	 * Configure API setting
	 */
	function __construct($params = array()) {
		if (count($params) > 0) {
			foreach ($params as $key=>$value) {
				$this->$key = $value;
	 		}
	 	}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get online status
	 * @access public
	 * @param yahoo ID
	 * @return boolean
	 */
	function check($yahooID = '') {
		if ($yahooID == '') $yahooID = $this->yahooID;
		$uri = str_replace('%s', $yahooID, $this->checkURI);
		$fetchstatus = $this->getcontent($uri);
		$fetchstatus = str_replace($yahooID.' is ', '', $fetchstatus);
		if ($fetchstatus == 'ONLINE') return TRUE;
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * print chat link / online status
	 * @access public
	 * @param yahoo ID
	 * @param offline / online pic
	 * @return boolean
	 */
	function status($yahooID = '', $offline_picture = '', $online_picture = '') {
		if ($yahooID == '') $yahooID = $this->yahooID;
		if ($offline_picture == '') $offline_picture = $this->offline_picture;
		if ($online_picture == '') $online_picture = $this->online_picture;
		if ($this->check($yahooID)) {
			echo '<a href="ymsgr:sendIM?'.$yahooID.'"><img src="'.$online_picture.'" border="0" /></a>';
		} else {
			echo '<a href="ymsgr:sendIM?'.$yahooID.'"><img src="'.$offline_picture.'" border="0" /></a>';
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get content
	 * @access private
	 * @param string url
	 * @return string
	 */
	private function getcontent($url = '') {
		if ($url != '') {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			ob_start();
			$result = curl_exec($ch);
			if ($result === false) $result = curl_error($ch);
			ob_end_clean();
			curl_close($ch);
			
			return $result;
		} else {
			die('Undefined parameter url.');
		}
	}
}

/* end of file */