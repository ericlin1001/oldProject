<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

    <head>
        <title>Sicily Online Judge</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="./images/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="./css/global.css"/>
		<link type="text/css" rel="stylesheet" href="./css/sicilychan/static.css"/>
        <link type="text/css" rel="stylesheet" href="./css/project_hord.css"/>
        <link type="text/css" rel="stylesheet" href="./css/jquery-ui-1.8.6.custom.css"/>
        <script type="text/javascript" src="./js/jquery-1.4.2.min.js" > </script>
        <script type="text/javascript" src="./js/jquery-ui-1.8.6.custom.min.js" > </script>
        <script type="text/javascript" src="./js/nav.js" > </script>
		<script type="text/javascript" src="./js/sicilychan/static.js" > </script>
        <script type="text/javascript">var is_logged = 1;</script>
        
    </head>

    <body>

        <div id="msg-dialog" title="Infomation"></div>
        <div class="main_frame">

            <div id="global_banner">
				<div id="sicily_logo">
					<a href="./index.php" title="Return Home">
						<img src="./images/banner_logo.jpg" alt="Sicily"/> 
					</a>
				</div>
				<div id="topbar">
				</div>
                <div id="loginbox" >

                    <a title='Unauthorized'><img src='./images/authorize0.gif' alt='Unauthorized'/></a><a href='user.php?id=18981' class='nickname'>tempaccount</a><div id='signature'>You havn't any signature yet.</div><a href="process.php?act=Logout"> Logout</a>                </div>
            </div>
			<div class="clear"></div>
            
<div style="display:none">
<div id="searchbox">
    <form id="search_form">
        <input id="search_input" class="search_bar" type="text" title="Search problem id or title..." />
        <div class='hord_button ' onclick='$("#search_form").submit();return false'>Search</div>    </form>
</div>
</div>

<script type="text/javascript">
    // jQuery Input Hints plugin
    // Copyright (c) 2009 Rob Volk
    // http://www.robvolk.com

    jQuery.fn.inputHints=function() {
        // hides the input display text stored in the title on focus
        // and sets it on blur if the user hasn't changed it.

        // show the display text
        $(this).each(function(i) {
            $(this).val($(this).attr('title'))
            .addClass('input_hint');
        });

        // hook up the blur & focus
        return $(this).focus(function() {
            if ($(this).val() == $(this).attr('title'))
                $(this).val('')
            .removeClass('input_hint');
        }).blur(function() {
            if ($(this).val() == '')
                $(this).val($(this).attr('title'))
            .addClass('input_hint');
        });
    };



    var first_suggest_item = "";
    function open_problem(id) {
        location.href="problem.php?pid=" + id;
    }
	$("#topbar").append($("#searchbox"));
    $(function(){        
        $("#search_input[title]").inputHints();
        $("#search_form").submit(function(){
            if (first_suggest_item) {
                open_problem(first_suggest_item);
            }
            return false;
        });
 