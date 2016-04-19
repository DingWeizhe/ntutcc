<?php
	function debug($obj){
		if (defined('DEBUG')){
			$dump = var_export($obj, true);
			print "<div class='debug'>";
			print nl2br(str_replace(" ", "&nbsp;", $dump));
			print "</div>";
		}
	}

	function camelize($string){
		$result = ucwords(str_replace('_', ' ', $string));
		$result = str_replace(' ','', $result);
		return $result;
	}

	function underscore($string){
		return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
	}

	function array_select_keys(array $data, array $keys){
		$filterData = array();
		$keys = array_map("strtolower", $keys);
		foreach ($data as $key => $value)
			if (in_array(strtolower($key), $keys))
				$filterData[$key] = $value;
		return $filterData;
	}

	function __autoload($class){
		$r = explode('_', underscore($class));
		$scope = $r[count($r)-1];
		if (file_exists(WEBROOT . "/{$scope}/{$class}.php"))
			require_once(WEBROOT . "/{$scope}/{$class}.php");
		else if (file_exists(WEBROOT . "/plugin/{$class}.php"))
			require_once(WEBROOT . "/plugin/{$class}.php");
	}

	function addColon($data){
		$newdata = array();
		foreach ($data as $k => $val){
			$newdata[":".$k] = $val;
		}
		return $newdata;
	}

	function evaluate($filename, $data){
		extract($data);
		ob_start();
		include $filename;
		return ob_get_clean();
	}

	function getInnerStr($str, $startStr, $endStr, $pos = -1){
		static $__pos=0;
		static $__str="";
		if ($str != $__str) $__pos=0;
		$__str = $str;
		if ($pos!=-1) $__pos = $pos;
		$start = strpos($str, $startStr, $__pos);
		if ($start === false) return false;
		$end = strpos($str, $endStr, $start);
		if ($end === false) $endStr = strlen($str);
		$__pos = $end;
		return substr($str, $start, $end - $start + strlen($endStr));
	}

	function idx($idx){
		return func_get_arg($idx);
	}
	
	function remover_html_tag($str){
		return str_replace("　", "", preg_replace("/\<[^\>]*\>/", "", $str));
	}
?>