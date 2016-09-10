<?php
header('Content-Type:text/html;charset= gb2312');
?>
<HTML><HEAD><TITLE>集成搜索[www.AnyWhy.cn]-安全上网_代理上网_打造最好用的集成搜索引擎和社区门户</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<META name=keywords content="集成搜索,安全浏览,phpproxy,代理上网,无广告上网">
<META name=description content="集成搜索[www.AnyWhy.cn],安全浏览,phpproxy,代理上网,无广告上网">
<LINK REL="SHORTCUT ICON" HREF="http://www.anywhy.cn/favicon.ico">
</HEAD>
<BODY onkeydown="if(event.keyCode==27) return false;">
<CENTER>
<DIV class=mainborder id=jsmenu_parent></DIV>
<DIV class=mainheader>
<DIV class=logo>
<DIV class=maintable>
<DIV align=center><A href="http://www.AnyWhy.cn"><IMG 
alt="www.AnyWhy.cn" src="http://www.AnyWhy.cn/anywhy/anywhy.gif" border=0></A></DIV></DIV></DIV>
<DIV class=menu>
</DIV></DIV>
<DIV class=maintable><BR>
<DIV class="subtable nav" style="WIDTH: 98%"><A 
href="http://www.AnyWhy.cn">集成搜索</A> &raquo; 在线代理系统</DIV><BR>
<DIV class=simpletable style="WIDTH: 98%; TEXT-ALIGN: left">
<DIV class="altbg2 smalltxt" align=center>
<DIV class="st"></div>
<div id="container">
<?php

switch ($data['category'])
{
    case 'auth':
?>
  <div id="auth"><p>
  <b>请输入用户名及密码 "<?php echo htmlspecialchars($data['realm']) ?>" on <?php echo $GLOBALS['_url_parts']['host'] ?></b>
  <form method="post" action="">
    <input type="hidden" name="<?php echo $GLOBALS['_config']['basic_auth_var_name'] ?>" value="<?php echo base64_encode($data['realm']) ?>" />
    <label>用户名 <input type="text" name="username" value="" /></label> <label>密码 <input type="password" name="password" value="" /></label> <input type="submit" value="Login" class="button"/>
  </form></p></div>
<?php
        break;
    case 'error':
        echo '<div id="error"><p>';
        
        switch ($data['group'])
        {
            case 'url':
                echo '<b>网址错误(' . $data['error'] . ')</b>: ';
                switch ($data['type'])
                {
                    case 'internal':
                        $message = '连接指定主机失败. '
                                 . '可能由于服务器不存在，连接超时，或者服务器拒绝访问。'
                                 . '请重新连接，同时检查该网址是否正确。';
                        break;
                    case 'external':
                        switch ($data['error'])
                        {
                            case 1:
                                $message = '您要访问的网址被服务器列入了黑名单，请选择其他网址。';
                                break;
                            case 2:
                                $message = '您输入的URL不正确。';
                                break;
                        }
                        break;
                }
                break;
            case 'resource':
                echo '<b>资源错误:</b> ';
                switch ($data['type'])
                {
                    case 'file_size':
                        $message = '您要下载的文件太大。<br />'
                                 . '本系统只允许大小为<b>' . number_format($GLOBALS['_config']['max_file_size']/1048576, 2) . ' MB</b>以下的文件下载。<br />'
                                 . '您当期要下载的文件大小为： <b>' . number_format($GLOBALS['_content_length']/1048576, 2) . ' MB</b>';
                        break;
                    case 'hotlinking':
                        $message = '您通过异地网站使用本代理程序访问资源。It appears that you are trying to access a resource through this proxy from a remote Website.<br />'
                                 . '出于安全原因，请使下边的表单来访问站点.';
                        break;
                }
                break;
        }
        
        echo '本代理系统访问站点出现错误。 <br />' . $message . '</p></div>';
        break;
}
?>
 <div style="text-align: center">
<div style="width: 50%; text-align: left">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<ul style="margin: 2px auto;">
<label class="f12">Anywhy代理上网-->请输入网址:<input id="address_box" size="50" type="text" name="<?php echo $GLOBALS['_config']['url_var_name'] ?>" value="<?php echo isset($GLOBALS['_url']) ? htmlspecialchars($GLOBALS['_url']) : '' ?>" onfocus="this.select()" /></label> <input id="go" class="button" type="submit" value=" 浏览 " />
      <?php
      
      foreach ($GLOBALS['_flags'] as $flag_name => $flag_value)
      {
          if (!$GLOBALS['_frozen_flags'][$flag_name])
          {
              echo '<li class="optionclass"><label><input type="checkbox" name="' . $GLOBALS['_config']['flags_var_name'] . '[' . $flag_name . ']"' . ($flag_value ? ' checked="checked"' : '') . ' /> ' . $GLOBALS['_labels'][$flag_name][1] . '</label></li>' . "\n";
          }
      }
      ?>
</ul>  </form>
</div>
</div>



</div>

	</DIV></DIV>

	</DIV>

<DIV align=center>
<TABLE>
  <TBODY>
  <TR>
    <TD style="PADDING-LEFT: 30px" width=80></TD>
    <TD style="FONT-SIZE: 11px"><FONT style="FONT-SIZE: 12px"><a href='http://www.anywhy.cn'>集成搜索</a> &copy;2008 版权所有</FONT>　<a href=http://www.miibeian.gov.cn>粤ICP备08003896号</a></DIV>
    </TD>
    <TD style="PADDING-RIGHT: 30px" vAlign=bottom align=right>
      
  </TD></TR></TBODY></TABLE><A name=bottom></A>
<DIV class=mainborder></DIV></CENTER>
<DIV style="DISPLAY: none" align=center>
<script src='http://s73.cnzz.com/stat.php?id=390882&web_id=390882&show=pic' language='JavaScript' charset='gb2312'></script>
</DIV>
</BODY></HTML>