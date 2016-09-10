<?php
class GetAns {
	var $myClient;
	function GetAns($user='11348076',$pass='11348076'){
	$this->	$myClient=new HttpClient('222.200.191.58',8080);
	//$this->$myClient->setDebug(true);
		$this->$myClient->post('/onlineTest/resources/j_spring_security_check',array('j_username'=>$user,'j_password'=>$pass));
	//echo 	$this->$myClient->getContent();
	}
	function getQuestion($id){

	}
	function getAn($id){

$this->$client->get('/onlineTest/questions/exam?type=1&page=1&size=1');
		$questionId=$id.',';
		$psize=1;
		$ans=array('A','B','C','D','E','F','AB','AC','AD','AE','AF','BC','BD','BE','BF','CD','CE','CF','DE','DF','EF','ABC ','ABD','ABE','ABF','ACD','ACE','ACF','ADE','ADF','AEF','BCD','BCE','BCF','BDE','BDF','BEF','CDE','CDF','CEF','DEF');
		$an='';
	//echo '***********checking qid='.$questionId;
		$realAn='Unknow';
		$realRes="";
		foreach($ans as $an){
			$answer=$an.',';
		//	echo 'testing answer with an='.$an."<br/>";
			$r=$this->$myClient->post('/onlineTest/questions/getScore.action',array('answer'=>$answer,'questionId'=>$questionId,'psize'=>$psize));
			$failCount=7;
			while(!$r){
				$failCount--;
				if($failCount<0)break;
				$r=$this->$myClient->post('/onlineTest/questions/getScore.action',array('answer'=>$answer,'questionId'=>$questionId,'psize'=>$psize));
			}
			//echo 'post result:'.$r;
			if(!$r){
				echo 'post fail!';
			}
			$this->$myClient->get('/onlineTest/questions/showWrong?size=20&page=1&type=1');
			$result=$this->$myClient->getContent();
			//echo $result;
			//echo '<hr/>';
			if(preg_match('/做对/',$result,$m)){
				$realAn=$an;
				echo "find answer:".$an."<br/>";
				//return $an;
			}else{
				$realRes=$result;
			}
		}
			if(preg_match('/(<form>[^`]*<\/form>)[^`]*<div>/',$realRes,$m)){
				print_r($m);
				if(isset($m[0])){
					$questContent=$m[0];

					echo '********quest='.$questContent;
					echo 'end*********';
					if(preg_match('/<p>[^`]*?<\/p>/',$questContent,$m)){
						print_r($m);
						//echo $m[1];
					}
					print_r($m);

				}
				echo "<div> id=".$id."<br/>".$m[0]."</div>";
			}else{
				echo $realRes;
			}
			return $realAn;
	}
}
?>
