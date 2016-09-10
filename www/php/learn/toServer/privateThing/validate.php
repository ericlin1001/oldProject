<?php
define('WORD_WIDTH',8);
define('WORD_HIGHT',10);
define('OFFSET_X',2);
define('OFFSET_Y',4);
define('WORD_SPACING',2);
class valite
{
	public function setImage($Image)
	{
		$this->ImagePath = $Image;
	}
	public function getData()
	{
		return $data;
	}
	public function getResult()
	{
		return $DataArray;
	}
	public function imageCreateFrom($imgpath){
		$img=imagecreatefrompng($this->ImagePath);
		return $img;
	}
	public function getHec()
	{
		$res = imagecreatefrompng($this->ImagePath);
		$size = getimagesize($this->ImagePath);
		$data = array();
		for($i=0; $i < $size[1]; ++$i)
		{
			for($j=0; $j < $size[0]; ++$j)
			{
				$rgb = imagecolorat($res,$j,$i);
				$rgbarray = imagecolorsforindex($res, $rgb);
				if($rgbarray['red'] < 125 || $rgbarray['green']<125
				|| $rgbarray['blue'] < 125)
				{
					$data[$i][$j]=1;
				}else{
					$data[$i][$j]=0;
				}
			}
		}
		$this->DataArray = $data;
		$this->ImageSize = $size;
	}
	public function getChars(){
		$data = array("","","","");
		for($i=0;$i<4;++$i)
		{
			$x = ($i*(WORD_WIDTH+WORD_SPACING))+OFFSET_X;
			$y = OFFSET_Y;
			for($h = $y; $h < (OFFSET_Y+WORD_HIGHT); ++ $h)
			{
				//$data[$i].="<br/>\n";
				for($w = $x; $w < ($x+WORD_WIDTH); ++$w)
				{
					$data[$i].=$this->DataArray[$h][$w];
				}
			}
			
		}
		$chars=$data;
		print_r($chars);
		return $chars;
	}
	public function run()
	{
		$result="";
		// 查找4个数字
		$data = array("","","","");
		for($i=0;$i<4;++$i)
		{
			$x = ($i*(WORD_WIDTH+WORD_SPACING))+OFFSET_X;
			$y = OFFSET_Y;
			for($h = $y; $h < (OFFSET_Y+WORD_HIGHT); ++ $h)
			{
				for($w = $x; $w < ($x+WORD_WIDTH); ++$w)
				{
					$data[$i].=$this->DataArray[$h][$w];
				}
			}
			
		}
		// 进行关键字匹配
		foreach($data as $numKey => $numString)
		{
			$max=0.0;
			$num = 0;
			foreach($this->Keys as $key => $value)
			{
				$percent=0.0;
				similar_text($value, $numString,$percent);
				if(intval($percent) > $max)
				{
					$max = $percent;
					$num = $key;
					if(intval($percent) > 95)
						break;
				}
			}
			$result.=$num;
		}
		$this->data = $result;
		// 查找最佳匹配数字
		return $result;
	}
	public function Draw()
	{
		for($i=0; $i<$this->ImageSize[1]; ++$i)
		{
	        for($j=0; $j<$this->ImageSize[0]; ++$j)
		    {
			    echo $this->DataArray[$i][$j];
	        }
		   echo "<br/>\n";
		}
	}
				
	public function __construct()
	{
	$this->Keys = array(
		'5'=>'11111110110000001100000011011100111001100000001100000011110000110110011000111100',
		'3'=>'01111100110001100000001100000110000111000000011000000011000000111100011001111100',
		'2'=>'00111100011001101100001100000011000001100000110000011000001100000110000011111111',
		'1'=>'00011000001110000111100000011000000110000001100000011000000110000001100001111110',
		'8'=>'00111100011001101100001101100110001111000110011011000011110000110110011000111100',
		'9'=>'00111100011001101100001111000011011001110011101100000011010000110110011000111100',
		'7'=>'11111111000000110000001100000110000011000001100000110000011000001100000011000000',
		'6'=>'00111100011001101100001011000000110111001110011011000011110000110110011000111100',
		'4'=>'00000110000011100001111000110110011001101100011011111111000001100000011000000110',
	);
	}
	protected $ImagePath;
	protected $DataArray;
	protected $ImageSize;
	protected $data;
	protected $Keys;
	protected $NumStringArray;
	protected $chars;
}
function getImgCode($img){
	$v=new valite();
	$v->setImage($img);
	$v->getHec();
	//$v->getChars();
	$res=$v->run();
	//$v->draw();
	return $res;
}
?>