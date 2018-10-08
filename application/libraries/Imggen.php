<?
class Imggen
{
	private $randseed = array(0,0,0,0);

	public function __construct()
	{
		
	}

	private function seedrand($seed)
	{
		$this->randseed = array(0,0,0,0);

		for($i = 0; $i < strlen($seed); $i++)
		{
			$this->randseed[$i%4] = ($this->intval32bits($this->randseed[$i%4] << 5) - $this->randseed[$i%4]) + ord(substr($seed, $i, 1));
		}
	}

	private function rand()
	{
		$t = $this->randseed[0] ^ $this->intval32bits($this->randseed[0] << 11);
		$t = $this->intval32bits($t);

		$this->randseed[0] = $this->randseed[1];
		$this->randseed[1] = $this->randseed[2];
		$this->randseed[2] = $this->randseed[3];
		$this->randseed[3] = $this->randseed[3] ^ ($this->intval32bits($this->randseed[3] & 0xFFFFFFFF) >> 19) ^ $t ^ ($t >> 8);
		$this->randseed[3] = $this->intval32bits($this->randseed[3]);
		
		return ($this->randseed[3]>>0) / ((1 << 31)>>0);
	}

	private function intval32bits($value)
	{
		$value = ($value & 0xFFFFFFFF);

		if($value & 0x80000000)
		{
			$value = -((~$value & 0xFFFFFFFF) + 1);
		}

		return $value;
	}

	private function createColor()
	{
		//saturation is the whole color spectrum
		$h = floor($this->rand() * 360);
		//saturation goes from 40 to 100, it avoids greyish colors
		$s = (($this->rand() * 60) + 40);
		//lightness can be anything from 0 to 100, but probabilities are a bell curve around 50%
		$l = (($this->rand() + $this->rand() + $this->rand() + $this->rand()) * 25);

		return array($h, $s, $l);
	}

	private function hslToRgb($h, $s, $l)
	{
		$s = $s / 100;
	    $l = $l / 100;

		$r; 
		$g; 
		$b;
		$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
		$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
		$m = $l - ( $c / 2 );
		if ( $h < 60 ) {
			$r = $c;
			$g = $x;
			$b = 0;
		} else if ( $h < 120 ) {
			$r = $x;
			$g = $c;
			$b = 0;			
		} else if ( $h < 180 ) {
			$r = 0;
			$g = $c;
			$b = $x;					
		} else if ( $h < 240 ) {
			$r = 0;
			$g = $x;
			$b = $c;
		} else if ( $h < 300 ) {
			$r = $x;
			$g = 0;
			$b = $c;
		} else {
			$r = $c;
			$g = 0;
			$b = $x;
		}
		$r = ( $r + $m ) * 255;
		$g = ( $g + $m ) * 255;
		$b = ( $b + $m  ) * 255;

	    return array( floor( $r ), floor( $g ), floor( $b ) );
	}

	private function createImageData($size)
	{
		$width = $size; // Only support square icons for now
		$height = $size;


		$dataWidth = ceil($width / 2);
		$mirrorWidth = $width - $dataWidth;

		$data = array();
		for($y = 0; $y < $height; $y++)
		{
			$row = array();
			for($x = 0; $x < $dataWidth; $x++)
			{
				// this makes foreground and background color to have a 43% (1/2.3) probability
				// spot color has 13% chance
				$row[$x] = floor($this->rand() * 2.3);
			}

			$r = array_slice($row, 0, $mirrorWidth);
			$r = array_reverse($r);
			$row = array_merge($row, $r);

			for($i = 0; $i < count($row); $i++)
			{
				$data[] = $row[$i];
			}
		}

		return $data;
	}

	private function buildOpts($opts)
	{
		$newOpts = array();

		$newOpts['seed'] = $opts['seed'];
		$this->seedrand($newOpts['seed']);

		$newOpts['size'] = !empty($opts['size'])?intval($opts['size']):8;
		$newOpts['scale'] = !empty($opts['scale'])?intval($opts['scale']):4;
		$newOpts['color'] = !empty($opts['color'])?$opts['color']:$this->createColor();
		$newOpts['bgcolor'] = !empty($opts['bgcolor'])?$opts['bgcolor']:$this->createColor();
		$newOpts['spotcolor'] = !empty($opts['spotcolor'])?$opts['spotcolor']:$this->createColor();

		return $newOpts;
	}

	private function renderIcon($opts)
	{
		$imageData = $this->createImageData($opts['size']);
		$width = sqrt(count($imageData));
		$img_width = $img_height = $opts['size'] * $opts['scale'];

		$img = ImageCreateTrueColor($img_width, $img_height);
		$c_bg = $this->hslToRgb($opts['bgcolor'][0], $opts['bgcolor'][1], $opts['bgcolor'][2]);
		$bg_color = ImageColorAllocate($img, $c_bg[0], $c_bg[1], $c_bg[2]);
		ImageFilledRectangle($img, 0, 0, $img_width, $img_height, $bg_color);

		$c_color = $this->hslToRgb($opts['color'][0], $opts['color'][1], $opts['color'][2]);
		$color = ImageColorAllocate($img, $c_color[0], $c_color[1], $c_color[2]);
		$c_spotcolor = $this->hslToRgb($opts['spotcolor'][0], $opts['spotcolor'][1], $opts['spotcolor'][2]);
		$spotcolor = ImageColorAllocate($img, $c_spotcolor[0], $c_spotcolor[1], $c_spotcolor[2]);

		foreach($imageData as $key => $val)
		{
			// if data is 0, leave the background
			if($val)
			{
				$row = floor($key / $width);
				$col = $key % $width;

				// if data is 2, choose spot color, if 1 choose foreground
				$c = ($val == 1)?$color:$spotcolor;
				ImageFilledRectangle($img, $col * $opts['scale'], $row * $opts['scale'], ($col * $opts['scale']) + $opts['scale'], ($row * $opts['scale']) + $opts['scale'], $c);

			}
		}

		ob_start (); 
		ImagePng($img);
		$image_data = ob_get_contents (); 
		ob_end_clean (); 
		ImageDestroy($img);
		$result = base64_encode($image_data);

		return $result;
	}

	public function createIcon($opts = [])
	{
		$opts = $this->buildOpts($opts);
		return $this->renderIcon($opts);
	}

	public function createIconSrc($opts = [])
	{
		return 'data:image/png;base64,'.$this->createIcon($opts);
	}
	
}