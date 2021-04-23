<?php

namespace App\View\Helper;

use Cake\View\Helper;

class QueryStringHelper extends Helper {

	function add($key, $value)
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = $this->remove($key);
		if (strpos($url, '?') === false) {
			return ($url . '?' . $key . '=' . $value);
		} else {
			return ($url . '&' . $key . '=' . $value);
		}
	}

	function remove($key)
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
		$url = substr($url, 0, -1);

		$url = preg_replace('/(.*)(\?|&)page=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
		$url = substr($url, 0, -1);
		return $url; 
	}
}
