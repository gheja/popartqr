<?php
	$scale = 6;
	$padding = array(20, 40, 20, 40);
	$brightness = max(min((int) $_GET['brightness'], 100), 0) / 100 * 240;
	$contrast = max(min((int) $_GET['contrast'], 100), 0) / 100 * 190 + 10;
	$rand2_seed = array_key_exists('seed', $_GET) ? (int) $_GET['seed'] : rand(0, 9999999999);
	$text = array_key_exists('text', $_GET) && trim(substr($_GET['text'], 0, 100)) ? trim(substr($_GET['text'], 0, 100)) : "http://qr.x0.hu/";
	$padder = array_key_exists('inverted', $_GET) && (bool) $_GET['inverted'] ? "1" : "0";
	
	if ($rand2_seed < 0)
	{
		$rand2_seed *= -1;
	}

	function rand2($min, $max)
	{
		global $rand2_seed;
		
		$rand2_seed = ($rand2_seed * 158615216) % 1972900636 + 1;
		
		return ($rand2_seed % ($max - $min + 1)) + $min;
	}
	
	require_once("phpqrcode.php");
	
	/* generate the QR code */	
	$qr_code = QRcode::text($text, false, QR_ECLEVEL_L, 1, 1);
	$width = strlen($qr_code[0]);
	$height = count($qr_code);
	
	/* add the "quiet zone" padding */
	$pad_line = str_repeat("0", 2 + $width + 2);
	$new = array();
	$new[] = $pad_line;
	$new[] = $pad_line;
	for ($i=0; $i<$height; $i++)
	{
		$new[] = "00" . $qr_code[$i] . "00" ;
	}
	$new[] = $pad_line;
	$new[] = $pad_line;
	$qr_code = $new;
	$width = strlen($qr_code[0]);
	$height = count($qr_code);
	
	/* add the other paddings */
	$pad_line = str_repeat($padder, $padding[3] + $width + $padding[1]);
	$new = array();
	for ($i=0; $i<$padding[0]; $i++)
	{
		$new[] = $pad_line;
	}
	for ($i=0; $i<$height; $i++)
	{
		$new[] = str_repeat($padder, $padding[3]) . $qr_code[$i] . str_repeat($padder, $padding[1]);
	}
	for ($i=0; $i<$padding[2]; $i++)
	{
		$new[] = $pad_line;
	}
	$qr_code = $new;
	
	$width = strlen($qr_code[0]);
	$height = count($qr_code);	
	
	/* initialize the image */
	$img = imagecreatetruecolor($width * $scale, $height * $scale);
	
	/* draw the image */
	for ($y=0; $y<$height; $y++)
	{
		/* allocate colors based on the "invert" flag */
		if ($padder == "0")
		{
			do {
				$r = rand2(0, 255);
				$g = rand2(0, 255);
				$b = rand2(0, 255);
			} while ($r * 0.21 + $g * 0.71 + $b *0.08 < $brightness);
		
			$colors = array("0" => array(), "1" => array());
			for ($i=0; $i<=10; $i++)
			{
				$n = $i / $contrast;
				$m = ($i + ($contrast - 10)) / $contrast;
				$colors["1"][$i] = imagecolorallocate($img, $r * $n, $g * $n, $b * $n);
				$colors["0"][$i] = imagecolorallocate($img, $r * $m, $g * $m, $b * $m);
			}
		}
		else
		{
			do {
				$r = rand2(0, 255);
				$g = rand2(0, 255);
				$b = rand2(0, 255);
			} while ($r * 0.21 + $g * 0.71 + $b *0.08 > ($brightness + 15));
		
			$colors = array("0" => array(), "1" => array());
			for ($i=0; $i<=10; $i++)
			{
				$n = $i / 10;
				$m = 3 * $i / $contrast;
				$colors["1"][$i] = imagecolorallocate($img, $r * $n, $g * $n, $b * $n);
				$colors["0"][$i] = imagecolorallocate($img, 255 - $r * $m, 255 - $g * $m, 255 - $b * $m);
			}
		}
		
		/* draw a row */
		for ($x=0; $x<$width; $x++)
		{
			$bit = $qr_code[$y][$x];
			$rand = rand2(0, 10);
			imagefilledrectangle($img, $x * $scale, $y * $scale, ($x + 1) * $scale, ($y + 1) * $scale, $colors[$bit][$rand]);
		}
	}
	
	/* output the image */
	header("Content-type: image/png");
	imagepng($img);
?>
