<?php
header('Content-Type:text/html;charset= gb2312');
?>
<HTML><HEAD><TITLE>��������[www.AnyWhy.cn]-��ȫ����_��������_��������õļ�����������������Ż�</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<META name=keywords content="��������,��ȫ���,phpproxy,��������,�޹������">
<META name=description content="��������[www.AnyWhy.cn],��ȫ���,phpproxy,��������,�޹������">
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
href="http://www.AnyWhy.cn">��������</A> &raquo; ���ߴ���ϵͳ</DIV><BR>
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
  <b>�������û��������� "<?php echo htmlspecialchars($data['realm']) ?>" on <?php echo $GLOBALS['_url_parts']['host'] ?></b>
  <form method="post" action="">
    <input type="hidden" name="<?php echo $GLOBALS['_config']['basic_auth_var_name'] ?>" value="<?php echo base64_encode($data['realm']) ?>" />
    <label>�û��� <input type="text" name="username" value="" /></label> <label>���� <input type="password" name="password" value="" /></label> <input type="submit" value="Login" class="button"/>
  </form></p></div>
<?php
        break;
    case 'error':
        echo '<div id="error"><p>';
        
        switch ($data['group'])
        {
            case 'url':
                echo '<b>��ַ����(' . $data['error'] . ')</b>: ';
                switch ($data['type'])
                {
                    case 'internal':
                        $message = '����ָ������ʧ��. '
                                 . '�������ڷ����������ڣ����ӳ�ʱ�����߷������ܾ����ʡ�'
                                 . '���������ӣ�ͬʱ������ַ�Ƿ���ȷ��';
                        break;
                    case 'external':
                        switch ($data['error'])
                        {
                            case 1:
                                $message = '��Ҫ���ʵ���ַ�������������˺���������ѡ��������ַ��';
                                break;
                            case 2:
                                $message = '�������URL����ȷ��';
                                break;
                        }
                        break;
                }
                break;
            case 'resource':
                echo '<b>��Դ����:</b> ';
                switch ($data['type'])
                {
                    case 'file_size':
                        $message = '��Ҫ���ص��ļ�̫��<br />'
                                 . '��ϵͳֻ�����СΪ<b>' . number_format($GLOBALS['_config']['max_file_size']/1048576, 2) . ' MB</b>���µ��ļ����ء�<br />'
                                 . '������Ҫ���ص��ļ���СΪ�� <b>' . number_format($GLOBALS['_content_length']/1048576, 2) . ' MB</b>';
                        break;
                    case 'hotlinking':
                        $message = '��ͨ�������վʹ�ñ�������������Դ��It appears that you are trying to access a resource through this proxy from a remote Website.<br />'
                                 . '���ڰ�ȫԭ����ʹ�±ߵı�������վ��.';
                        break;
                }
                break;
        }
        
        echo '������ϵͳ����վ����ִ��� <br />' . $message . '</p></div>';
        break;
}
?>
 <div style="text-align: center">
<div style="width: 50%; text-align: left">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<ul style="margin: 2px auto;">
<label class="f12">Anywhy��������-->��������ַ:<input id="address_box" size="50" type="text" name="<?php echo $GLOBALS['_config']['url_var_name'] ?>" value="<?php echo isset($GLOBALS['_url']) ? htmlspecialchars($GLOBALS['_url']) : '' ?>" onfocus="this.select()" /></label> <input id="go" class="button" type="submit" value=" ��� " />
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
    <TD style="FONT-SIZE: 11px"><FONT style="FONT-SIZE: 12px"><a href='http://www.anywhy.cn'>��������</a> &copy;2008 ��Ȩ����</FONT>��<a href=http://www.miibeian.gov.cn>��ICP��08003896��</a></DIV>
    </TD>
    <TD style="PADDING-RIGHT: 30px" vAlign=bottom align=right>
      
  </TD></TR></TBODY></TABLE><A name=bottom></A>
<DIV class=mainborder></DIV></CENTER>
<DIV style="DISPLAY: none" align=center>
<script src='http://s73.cnzz.com/stat.php?id=390882&web_id=390882&show=pic' language='JavaScript' charset='gb2312'></script>
</DIV>
</BODY></HTML>