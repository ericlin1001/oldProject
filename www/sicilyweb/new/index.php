<?php
require("./navigation.php");

function randomTalk() {
	global $conn;	
	$rs = new RecordSet($conn);
	$rs->Query("SELECT content FROM sicilychan WHERE avail = 1 ORDER BY RAND() LIMIT 1");
	$rs->MoveNext();
	return $rs->Fields['content'];
}

function recommandTalk() {
	global $conn;	
	$rs = new RecordSet($conn);
	$rs->Query('select problems.pid, title, accepted, problems.submissions, time, user.uid, username, nickname FROM status LEFT JOIN problems ON status.pid = problems.pid LEFT JOIN user ON user.uid = status.uid WHERE status = "Accepted" AND status.contest = 0 ORDER BY sid DESC LIMIT 0, 20');
	$recommand = array();
	while ($rs->MoveNext()) {
		$pid = intval($rs->Fields['pid']);
		$title = $rs->Fields['title'];
		$accepted = intval($rs->Fields['accepted']);
		$submissions = intval($rs->Fields['submissions']);
		$time = $rs->Fields['time'];
		$uid = intval($rs->Fields['uid']);
		$nickname = trim(htmlspecialchars($rs->Fields['nickname']));
		if (empty($nickname)) {
			$nickname = trim(htmlspecialchars($rs->Fields['username']));
		}
		if ($pid <= 2000 && $accepted <= 300) {
			$recommand[] = array('pid' => $pid, 'title' => $title, 'time' => $time, 'uid' => $uid, 'nickname' => $nickname);
		}
	}
	if (count($recommand) > 0) {
		$selected = $recommand[rand(0, count($recommand) -1)];	
		return $selected;
	} else {
		return "";
	}
}

function formatRT($item) {
	$user = "<a href=\"user.php?id={$item['uid']}\">{$item['nickname']}</a>"; 
	$prob = "<a href=\"problem.php?pid={$item['pid']}\">{$item['pid']}-{$item['title']}</a>"; 
	$template = array(
		"撒花！{$user}同学成功切掉了题目{$prob}！",
		"如果你对题目{$prob}没想法的话，可以向{$user}童鞋请教哦，我听说TA刚过了这道题"
	);
	return $template[rand(0, count($template)-1)];
}

if (rand(1, 10) <= 3) {
	$talk = randomTalk();
} else {
	$item = recommandTalk();
	if (empty($item)) $talk = randomTalk();
	else $talk = formatRT($item);
}

$talk = trim(str_replace(array("\r\n", "\n"), "", $talk));

?>

<div class='main_weibo' style="">
<iframe width="100%" height="450" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=0&height=450&fansRow=2&ptype=1&speed=0&skin=5&isTitle=1&noborder=1&isWeibo=1&isFans=0&uid=2687206183&verifier=6b62aa09&dpc=1"></iframe>
</div>

<div class="main_news">
	<img src="images/logo.jpg" />
</div>

<script type="text/javascript">
	$(function(){
		var talkmsg = '<?=$talk?>';
		showSicilyChan(talkmsg);
	});
</script>

<?php
require("./footer.php");
?>
