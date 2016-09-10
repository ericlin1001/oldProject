<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

    <head>
        <title>Sicily Online Judge</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="shortcut icon" href="images/favicon.ico"/>
        <link type="text/css" rel="stylesheet" href="css/A.global.css+project_hord.css+jquery-ui-1.8.6.custom.css,Mcc.NAw-UGY-oF.css.pagespeed.cf.OjnQhdBzpN.css"/>
        
        
        <script type="text/javascript" src="js/jquery-1.4.2.min.js.pagespeed.jm.fJ_6UN6xpQ.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.6.custom.min.js.pagespeed.jm.pV-0vJ_ORZ.js"></script>
        <script type="text/javascript" src="js/nav.js.pagespeed.jm.XxxUOYXAwT.js"></script>
        <script type="text/javascript">var is_logged=0;</script>
        
    </head>

    <body>

        <div id="msg-dialog" title="Infomation"></div>
        <div class="main_frame">

            <div id="sicily_logo">

                <div id="loginbox">

                    
                        <form id="loginform" action="action.php?act=Login" method="post" name="loginform">
                            <div style="width: 0px; height: 0px; overflow: hidden"><input type="submit"/></div>                            
                            <div style="text-align: right">
                                <div>
                                    <label for="username">Username:</label>
                                    <input name="username" type="text" id="username" size="10" maxlength="30"/>
                                    <label for="password">Password:</label>
                                    <input name="password" type="password" id="password" size="10" maxlength="16"/>
                                    <div class='hord_button ' onclick='$("#loginform").submit();return false'>Login</div>                                </div>
                                <div id="msg">username or password incorrect. </div>
                                                                    <div>
                                        <input id="lsession" name="lsession" type="checkbox" value="1"/>
                                        <label for="lsession">Remember me</label>
                                        | <a href='register.php' title='Sign up for a new account' class='hord_link'>Register</a>                                        | <a href='profile_forgetpwd.php' title='Forget your password?' class='hord_link'>Forget</a>                                    </div>
                                 
                            </div>

                        </form>


                                        </div>



            </div>

            <div style="position: relative;">
                <div id="topbar">
                </div>
            </div>

            
<div id="searchbox">
    <form id="search_form">
        <input id="search_input" class="search_bar" type="text" title="Search problem id or title..."/>
        <div class='hord_button ' onclick='$("#search_form").submit();return false'>Search</div>    </form>
</div>

<script type="text/javascript">jQuery.fn.inputHints=function(){$(this).each(function(i){$(this).val($(this).attr('title')).addClass('input_hint');});return $(this).focus(function(){if($(this).val()==$(this).attr('title'))
$(this).val('').removeClass('input_hint');}).blur(function(){if($(this).val()=='')
$(this).val($(this).attr('title')).addClass('input_hint');});};var first_suggest_item="";function open_problem(id){location.href="problem.php?pid="+id;}
$(function(){$("#search_input[title]").inputHints();$("#topbar").append($("#searchbox"));$("#search_form").submit(function(){if(first_suggest_item){open_problem(first_suggest_item);}
return false;});$("#search_input").autocomplete({source:'fast_json.php?mod=problem&func=search_suggest',minLength:2,search:function(event,ui){first_suggest_item="";},select:function(event,ui){if(ui.item){open_problem(ui.item.id);}else{alert("no");}},focus:function(event,ui){$("#search_input").val(ui.item.id);first_suggest_item=ui.item.id;return false;}}).data("autocomplete")._renderItem=function(ul,item){var str=item.id+"."+item.title+"<br>"+item.info;if(item.match||!first_suggest_item){str+="<hr />";first_suggest_item=item.id;}
return $("<li></li>").data("item.autocomplete",item).append("<a>"+str+"</a>").appendTo(ul);};});</script>            <div class="topmenu ui-corner-all">
                <ul>

                    <li><a href='index.php' title='Return to Homepage'>Home</a></li><li><a href='problem_list.php' title='View all problems'>Problems</a></li><li><a href='contests.php' title='View all contests'>Contests</a></li><li><a href='courses.php' title='Edit Courses'>Courses</a></li><li><a href='ranklist.php' title='View user ranklist'>Ranklist</a></li><li><a href='post_market.php' title='Discussion about the problems in sicily'>Discuss</a></li>                </ul>
                <div class="rssbut">
                    <a onclick="javascript: ToggleWidth();" title="change window's width" class="links"> &lt;-> </a>


                </div>
            </div>




<table width="100%" border="0" cellspacing="0" cellpadding="0">

	<tr valign="top">
		<td width="300">
			<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellspacing="2" cellpadding="2">
				<thead>
					<tr class="ui-widget-header">
						<th width="100" colspan="2">Detail of tempaccount</th>
					</tr>
				</thead>
				<tr>
					<td align="right">Nickname</td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="right">Signature</td>
					<td align="left">2;i*i&lt;=s;i++) if (s%i==0) return 0;
    return 1;
}
int judge(int j,int sum)
{
    for (int i=j-1;i&gt;</td>
				</tr>
				<tr>
					<td align="right">Rank</td>
					<td align="left">8636</td>
				</tr>
				<tr>
					<td align="right">Solved No.</td>
					<td align="left">1</td>
				</tr>
				<tr>
					<td align="right">Submissions No.</td>
					<td align="left">1</td>
				</tr>
				<tr>
					<td align="right">Email</td>
					<td align="left">
						<a href="mailto:tempaccount@1.1" class="black">
							tempaccount@1.1						</a>
					</td>
				</tr>
				<tr>
					<td align="right">Contact1</td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="right">Contact2</td>
					<td align="left"></td>
				</tr>
								<tr>
					<td align="right">Register time</td>
					<td align="left">2012-03-15 00:24:52</td>
				</tr>
				<tr>
					<td align="right">Level</td>
					<td align="left">Class 1 - Spider					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<img alt="" src="images/icon/150x150xspider.gif.pagespeed.ic.MNo1TJrfpi.png" width="150" height="150">
					</td>
				</tr>

			</table>
		</td>
		<td width="50"></td>
		<td>

			<table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellspacing="2" cellpadding="2">
				<thead>
					<tr class="ui-widget-header">
						<td align="center">List of solved problems</td>
					</tr>
				</thead>
				<tr align="center">
					<td>
						<form action=usercmp.php method=get>
							Compare <input type="text" size="10" name="user1" value="tempaccount"/> and							<input type="text" size="10" name="user2"/>
							<input type="submit" value="GO"/>
						</form>
					</td>
				</tr>
				<tr>
					<td align="center" style="font-size: large; word-spacing: 0.3em">
																					<a href="show_problem.php?pid=1000" class="black">1000</a>
																		</td>
				</tr>
			</table>

		</td>
	</tr>
</table>

<div id="footer">
    <hr/>
    <p style="display: none" id="about">
        Sicily Chan designed by <a href="http://kiki-box.com" class="black">Kikichan</a>
        <br/>
        Formerly powered by <a href="mailto:henryouly.bbs@bbs.zsu.edu.cn" class="black">Henry</a> and 
        <a href="mailto:Northming.bbs@bbs.zsu.edu.cn" class="black">Northming</a>.
        <br/>
        Powered by 
        <a href="mailto:hoveychen@gmail.com" class="black">Hovey</a> ,
        <a href="mailto:tigersoldi@gmail.com" class="black">Tigersoldier</a> and
		<a href="mailto:xyb.0606@gmail.com" class="black">Xbingo</a>.
    </p>

    <p>
        Sicily Online Judge System(Rev 20120310-924)        <br/>
        <a href='process.php?act=ChangeLocale&locale=cn' title='' class='hord_link'>中文</a>        | <a href='process.php?act=ChangeLocale&locale=en' title='' class='hord_link'>English</a>        | <a href='http://bbs.sysu.edu.cn/bbs0an?path=boards/ACMICPC/D.1044598815.A/D.1111840644.A' title='Archives about sicily' class='hord_link'>Archives</a>        | <a href='faq.php' title='Submittion guileline etc.' class='hord_link'>Help</a>        | <a href='javascript:void(0)' title='' class='hord_link' onclick='$("#about").show();return false'>About</a>        
        
        
        <br>
        Copyright © 2005-2011 Informatic Lab in SYSU. All rights reserved.    </p>

    </div>
</body>

<script type="text/javascript">var uvOptions={};(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src=('https:'==document.location.protocol?'https://':'http://')+'widget.uservoice.com/I0q0Uxl2Q0uLbwiJqxGHA.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s);})();</script>
<script type='text/javascript'>//<![CDATA[
var _gaq=_gaq||[];_gaq.push(['_setAccount','UA-19434272-1']);_gaq.push(['_trackPageview']);(function(){var ga=document.createElement('script');ga.type='text/javascript';ga.async=true;ga.src=('https:'==document.location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s);})();
//]]></script>
</html>
