<?php

	function ORC($filepath){
		$recognizeTable = Array(
			'a' => '252,510,962,896,896,1016,1022,911,903,967,2047,1854',
			'b' => '7,7,7,7,7,503,1023,1807,3591,3591,3591,3591,3591,3847,1935,1023,503',
			'c' => '504,1020,542,15,7,7,7,7,15,542,1020,504',
			'd' => '3584,3584,3584,3584,3584,3832,4092,3870,3599,3591,3591,3591,3591,3591,3982,4094,3704',
			'f' => '496,504,28,28,28,255,255,28,28,28,28,28,28,28,28,28,28',
			'g' => '3832,4092,3870,3599,3591,3591,3591,3591,3591,3982,4094,3704,3584,1798,1022,508',
			'h' => '7,7,7,7,7,487,503,927,911,903,903,903,903,903,903,903,903',
			'e' => '248,1020,910,1799,1799,2047,2047,7,7,1038,2044,1016',
			'i' => '7,7,0,0,7,7,7,7,7,7,7,7,7,7,7,7',
			'j' => '112,112,0,0,112,112,112,112,112,112,112,112,112,112,112,112,112,113,63,30',
			'k' => '7,7,7,7,7,775,391,199,103,55,127,247,231,455,967,1927,3847',
			'l' => '7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7',
			'm' => '61927,130039,116623,115591,115591,115591,115591,115591,115591,115591,115591,115591',
			'o' => '504,1020,1806,3855,3591,3591,3591,3591,3855,1806,1020,504',
			'n' => '487,503,927,911,903,903,903,903,903,903,903,903',
			'p' => '503,1023,1807,3591,3591,3591,3591,3591,3847,1935,1023,503,7,7,7,7',
			'q' => '3832,4092,3870,3599,3591,3591,3591,3591,3591,3982,4094,3704,3584,3584,3584,3584',
			'r' => '231,247,31,15,7,7,7,7,7,7,7,7',
			's' => '60,126,71,7,31,62,124,240,224,225,127,62',
			't' => '14,14,127,127,14,14,14,14,14,14,14,30,124,120',
			'u' => '903,903,903,903,903,903,903,903,967,999,959,926',
			'v' => '3079,1550,1550,1550,796,796,444,440,440,240,240,240',
			'w' => '100231,100231,51086,51086,52942,28380,28380,28380,27740,15480,15480,15480',
			'x' => '1551,782,412,252,248,120,240,248,504,460,902,1923',
			'y' => '3079,1550,1550,1822,796,956,440,440,240,240,240,96,112,48,56,60',
			'z' => '2047,2047,1920,960,480,240,120,60,30,15,2047,2047'
		);
		$im = imagecreatefrompng($filepath);
		$binary = image2Binary($im, 170);
		$binarys = splitBinaryString($binary);
		$text = "";

		foreach ($binarys as $binary){
			$code = binaryMap2String($binary);
			foreach($recognizeTable as $k => $_code)
				if ($code == $_code)
					$text .= $k;
		}
		return $text;
	}
	function image2Binary($im, $base){
		$w = imagesx($im);
		$h = imagesy($im);
		$length = $w * $h;
		$binary = Array($h);
		for ($y=0; $y<$h; $y++){
			$binary[$y] = Array($w);
			for ($x=0; $x<$w; $x++){
				$color = imagecolorat($im, $x, $y);
				$r = ($color >> 16) & 0xFF;
				$g = ($color >> 8) & 0xFF;
				$b = $color & 0xFF;
				$gray = $r * .33 + $g * .33 + $b * .34;
				$binary[$y][$x] = $gray > $base ? 1 : 0;
			}
		}
		return $binary;
	}


	function splitBinaryString($binary){
		$binarys = Array();
		$h = count($binary);
		$w = count($binary[0]);
		$fontCount = 0;
		$inWord = 0;
		for ($x=0; $x<$w; $x++){
			$hasColor = 0;
			for ($y=0; $y<$h; $y++){
				$hasColor |= $binary[$y][$x];
			}
			if ($hasColor && !$inWord){
				$startX[] = $x;
				$inWord = 1;
			}
			if (!$hasColor && $inWord){
				$inWord = 0;
				$endX[] = $x - 1;
			}
		}

		for ($i=0; $i<count($startX); $i++){
			for ($startY[$i]=0; $startY[$i]<$h; $startY[$i]++){
				$hasColor = 0;
				for ($x=$startX[$i]; $x<$endX[$i]; $x++){
					$hasColor |= $binary[$startY[$i]][$x];
				}
				if ($hasColor){
					break;
				}
			}
			for ($endY[$i]=$h-1; $endY[$i]>=0; $endY[$i]--){
				$hasColor = 0;
				for ($x=$startX[$i]; $x<$endX[$i]; $x++){
					$hasColor |= $binary[$endY[$i]][$x];
				}
				if ($hasColor){
					break;
				}
			}
			$_y = 0;
			for($y=$startY[$i]; $y<=$endY[$i]; $y++){
				$_x = 0;
				for($x=$startX[$i]; $x<=$endX[$i]; $x++){
					$binarys[$i][$_y][$_x++] = $binary[$y][$x];
				}
				$_y++;
			}
		}

		return $binarys;
	}
	function printBinaryMap($binary){
		$str = "";
		for($i=0; $i<count($binary); $i++){
			for($j=0; $j<count($binary[$i]); $j++)
				$str .= $binary[$i][$j] . " ";
			$str .= "\n";
		}
		return $str;
	}

	function binary2Int($binary){
		$v = 0;
		for($i=0; $i<count($binary); $i++)
			$v |= $binary[$i] << $i;
		return $v;
	}

	function binaryMap2String($binary){
		$s = "";
		for($i=0; $i<count($binary); $i++)
			$s .= binary2Int($binary[$i]) . ",";
		$s = substr($s,0, strlen($s)-1);
		return $s;
	}
?>