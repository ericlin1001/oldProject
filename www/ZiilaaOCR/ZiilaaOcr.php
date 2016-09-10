<?php

class ZiilaaOcr
{
	/////these variances are read only for external
	public  $width  = 0;
	public  $height = 0;
	private $startX = 0;
	private $image  = array();
	private $fontWidth = 0;
	private $templates = array();
	private $unknownId = 0;
	private $value0 = ".";
	private $value1 = "O";
	
	///// load image from data
	public function loadImage ($hexdata, $threshold)
    {
    	//read the image as an image
    	$data = $this->hexDecode($hexdata);
		$orig_img = imagecreatefromstring ($data);
		if ($orig_img === false)	{	return false;	}
		
		//turn it into gray		
		$this->width  = imagesx ($orig_img);
		$this->height = imagesy ($orig_img);
		$img = imagecreatetruecolor ($this->width, $this->height);
		imagecopymergegray ($img, $orig_img, 0,0,0,0, $this->width, $this->height, 100);
		imagedestroy($orig_img);	
		
		//turn image as a bit array	
		$this->image  = array();	
		for ($y = 0; $y < $this->height; $y++)
		{
			for ($x = 0; $x < $this->width; $x++)
			{	$gray = imagecolorat ($img, $x, $y) & 0xFF;		//echo "[$gray]";
				$id   = $this->getId($x, $y);				
				if ($gray >= $threshold)	{	$this->image[$id] = 0;	}
				else						{	$this->image[$id] = 1;	}
			}
	    }
		
		//return the result
		imagedestroy($img);		
        return true;
    }
    
    /////turn hex string to binary data string
    private function getId($x, $y)
	{
		return ($y * $this->width) + $x;
	}
    
    /////turn hex string to binary data string
    private function hexDecode($hex)
	{
		$bin = '';
		
		//get binary length
		$len = intval (round (strlen($hex) / 2));
		
		//turn hex to binary every 2 characters
		for($i = 0; $i < $len; $i++)
		{	$byte = substr ($hex, 2 * $i, 2);
			$bin .= chr (hexdec($byte));
		}
		
		return $bin;
	} 
	
	/////turn 1 to 0 and 0 to 1.
	public function negateImage()
	{
		$len = $this->width * $this->height;
		for ($i = 0; $i < $len; $i++)  
		{  $this->image[$i] = abs(1 - $this->image[$i]);  }
	}
	
	/////show image as string
	public function showImage()
	{
		$str = "";
		for ($y = 0; $y < $this->height; $y++) 
		{
			//show y axis
			$str .= (($y + 1) % 10);
			
			//show a line
			for ($x = 0; $x < $this->width; $x++)
			{	$id   = $this->getId($x, $y);
				if ($this->image[$id] == 1)	{	$str .= $this->value1;	}
				else						{	$str .= $this->value0;	}
			}
			$str .= "<br>";
		}
		
		//show x axis
		$str .= "-";
		for ($x = 0; $x < $this->width; $x++)
		{	$str .= (($x + 1) % 10);	}
		$str .= "<br>";
		return $str;
	}
	
	/////clear image of a rect
	public function clearRect($left, $width, $top, $height)
	{
		for ($y = $top; $y < $height; $y++) 
		{
			for ($x = $left; $x < $width; $x++)
			{	$id   = $this->getId($x, $y);
				$this->image[$id] = 0;
			}
		}
	}
	
	/////set the font width and start position of X
	public function setFontWidth($width, $startX)
	{
		$this->fontWidth = $width;
		$this->startX = $startX;
	}
	
	/////set the font count
	private function getFontCount()
	{
		if ($this->fontWidth <= 0)
		{	return 0;	}
		else
		{	return intval (floor (($this->width - $this->startX) / $this->fontWidth));	}
	}
	
	/////set the font start X
	private function getFontStartX($id)
	{
		return $this->startX + $this->fontWidth * $id;
	}
	
	/////set the font to the center of a block, you shall call it after setFontWidth()
	public function centerFont()
	{
		$count = $this->getFontCount();
		for ($i = 0; $i < $count; $i++)
		{	$this->centerAFontX ($i);	$this->centerAFontY ($i);	}
	}
	
	/////set a font to the center of a block, you shall call it after setFontWidth()
	private function centerAFontX($id)
	{
		$left = $this->getFontStartX($id);
		$weights = array();
		
		//statistic image weight (X) into $weights
		for ($x = 0; $x < $this->fontWidth; $x++) 
		{	$sum = 0;
			for ($y = 0; $y < $this->height; $y++)
			{	$id   = $this->getId($left + $x, $y);
				$sum += $this->image[$id];
			}
			$weights[$x] = $sum;
		}
		
		//get mid position of X axis
		$total = array_sum ($weights);
		$sum  = 0;
		$midX = 0;
		for ($x = 0; $x < $this->fontWidth; $x++)
		{	$sum += $weights[$x];	
			if ($sum >= ($total/2))	{	$midX = ($x+1);	break;	}
		}
		
		//now, move dada
		$centerX = intval ( round ($this->fontWidth / 2) );
		if ($midX < $centerX)
		{
			//move rightward, so move right parts firstly
			for ($x = ($this->fontWidth - 1); $x >= ($centerX - $midX); $x--) 
			{	$xfrom = $x - ($centerX - $midX);
				for ($y = 0; $y < $this->height; $y++)
				{	$to   = ($y * $this->width) + ($left + $x);
					$from = ($y * $this->width) + ($left + $xfrom);
					$this->image[$to] = $this->image[$from];
				}
			}
			//fill 0 for blank part
			for ($x = ($centerX - $midX - 1); $x >= 0; $x--) 
			{
				for ($y = 0; $y < $this->height; $y++)
				{	$to   = ($y * $this->width) + ($left + $x);
					$this->image[$to] = 0;
				}
			}
		}
		else
		{
			//move leftward, so move left parts firstly
			for ($x = 0; $x < ($this->fontWidth - ($midX - $centerX)); $x++) 
			{	$xfrom = $x + ($midX - $centerX);
				for ($y = 0; $y < $this->height; $y++)
				{	$to   = ($y * $this->width) + ($left + $x);
					$from = ($y * $this->width) + ($left + $xfrom);
					$this->image[$to] = $this->image[$from];
				}
			}
			//fill 0 for blank part
			for ($x = ($this->fontWidth - ($midX - $centerX)); $x < $this->fontWidth; $x++) 
			{
				for ($y = 0; $y < $this->height; $y++)
				{	$to   = ($y * $this->width) + ($left + $x);
					$this->image[$to] = 0;
				}
			}
		}		
	}
	
	/////set a font to the center of a block, you shall call it after setFontWidth()
	private function centerAFontY($id)
	{
		$left = $this->getFontStartX($id);
		$weights = array();
		
		//statistic image weight (Y) into $weights
		for ($y = 0; $y < $this->height; $y++)		
		{	$sum = 0;
			for ($x = 0; $x < $this->fontWidth; $x++) 
			{	$id   = $this->getId($left + $x, $y);
				$sum += $this->image[$id];
			}
			$weights[$y] = $sum;
		}
		
		//get mid position of Y axis
		$total = array_sum ($weights);
		$sum  = 0;
		$midY = 0;
		for ($y = 0; $y < $this->height; $y++)
		{	$sum += $weights[$y];	
			if ($sum >= ($total/2))	{	$midY = ($y+1);	break;	}
		}
		
		//now, move dada
		$centerY = intval ( round ($this->height / 2) );
		if ($midY < $centerY)
		{
			//move upward, so move up parts firstly
			for ($y = ($this->height - 1); $y >= ($centerY - $midY); $y--)			
			{	$yfrom = $y - ($centerY - $midY);
				for ($x = 0; $x < $this->fontWidth; $x++) 
				{	$to   = ($y * $this->width) + ($left + $x);
					$from = ($yfrom * $this->width) + ($left + $x);
					$this->image[$to] = $this->image[$from];
				}				
			}
			//fill 0 for blank part
			for ($y = ($centerY - $midY - 1); $y >= 0; $y--) 
			{
				for ($x = 0; $x < $this->fontWidth; $x++) 
				{	$to   = ($y * $this->width) + ($left + $x);
					$this->image[$to] = 0;
				}
			}
		}
		else
		{
			//move upward, so move up parts firstly
			for ($y = 0; $y < $this->height - ($midY - $centerY); $y++)
			{	$yfrom = $y + ($midY - $centerY);				
				for ($x = 0; $x < $this->fontWidth; $x++) 
				{	$to   = ($y * $this->width) + ($left + $x);
					$from = ($yfrom * $this->width) + ($left + $x);
					$this->image[$to] = $this->image[$from];
				}
			}
			//fill 0 for blank part
			for ($y = $this->height - ($midY - $centerY); $y < $this->height; $y++) 
			{
				for ($x = 0; $x < $this->fontWidth; $x++) 
				{	$to   = ($y * $this->width) + ($left + $x);
					$this->image[$to] = 0;
				}
			}
		}
	}
	
	/////load templates from file
	public function loadTemplate($filename)
	{
		$this->templates = file ("$filename.tpl");
	}
	
	/////add a template to file
	public function saveTemplate($filename, $template)
	{
		$f = fopen ("$filename.tpl", "a");
		if ($f !== false)
		{	fwrite ($f, "$template\n");		fclose ($f);	}
	}
		
	/////try to match a character
	private function matchTemplate($id, $likenessMax, $likenessMin, $debug)
	{
		//extract this character as a string
		$character = $this->getCharacter($id);
		$chars = str_split ($character, 1);
		$len  = count($chars);
		
		//calculate the likeness for every template
		$likeness = array();
		$maxValue = 0;
		$maxChar  = "";
		foreach ($this->templates as $i => $template)
		{
			//omit the error lines
			if (strlen($template) < $len)	{	continue;	}
			$tpldata = substr ($template, 2);
			$tplchar = substr ($template, 0, 1);
			
			//calculate the likeness
			$same = 0;
			$data = str_split ($tpldata, 1);			
			for ($bit = 0; $bit < $len; $bit++)
			{	if ($chars[$bit] == $data[$bit])	$same++;	}
			$likeness[$i] = $same / $len;
			//echo "[$tplchar=" . $likeness[$i] . "]";
			
			//update the likest one
			if ($likeness[$i] > $maxValue)
			{	$maxValue = $likeness[$i];  $maxChar = $tplchar;   }
			
			//if match perfect, return it.
			if ($maxValue >= $likenessMax)	
			{	if ($debug)		echo "$id: [this is ($maxChar), perfect = $maxValue]<br>";
				return $maxChar;	
			}
		}
		
		//return the result
		if ($debug)		echo "$id: [this is ($maxChar), likeness = $maxValue]<br>";
		if ($maxValue < $likenessMin)	return -1;
		else							return $maxChar;
	}
	
	/////try to match all text
	public function matchAll($likenessMax, $likenessMin, $debug)
	{
		$count  = $this->getFontCount();
		$result = "";
		for ($i = 0; $i < $count; $i++)
		{
			$char = $this->matchTemplate ($i, $likenessMax, $likenessMin, $debug);
			if ($char == -1)
			{	$this->unknownId = $i;	return -1;	}
			else
			{	$result .= $char;	}
		}
		return $result;
	}
	
	/////show the unkown font as string
	public function showUnknown()
	{
		$str = "[UNKNOWN]<br>";
		$left = $this->getFontStartX($this->unknownId);
		for ($y = 0; $y < $this->height; $y++) 
		{
			for ($x = 0; $x < $this->fontWidth; $x++)
			{	$id   = $this->getId($left + $x, $y);
				if ($this->image[$id] == 1)	{	$str .= $this->value1;	}
				else						{	$str .= $this->value0;	}
			}
			$str .= "<br>";
		}
		return $str;
	}
	
	/////show the character as string
	private function getCharacter($id)
	{
		$str = "";
		$left = $this->getFontStartX($id);
		for ($y = 0; $y < $this->height; $y++) 
		{
			for ($x = 0; $x < $this->fontWidth; $x++)
			{	$id   = $this->getId($left + $x, $y);
				$str .= $this->image[$id];
			}
		}
		return $str;
	}
	
}
		
?>
