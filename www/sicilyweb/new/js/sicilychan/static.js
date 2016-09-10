function showSicilyChan(msg){
	var content = "";
	content += '<div id="sicily_chan">';
	content += '<div id="talkbox">';
    content += '<div id="boxtop"></div>';
    content += '<div id="boxcnt">' + msg + '</div>';
    content += '<div id="boxbottom"></div>';
	content += '</div>';
	content += '<div id="sicilychan_body">';
    content += '<div id="face" class="face1"></div>';
	content += '</div>';
	
	$("body").append(content);
	
	$("#sicily_chan").draggable();
	setInterval(function(){
		$("#face").removeClass("face1").addClass("face2");
		setTimeout(function() {
			$("#face").removeClass("face2").addClass("face3");
			setTimeout(function() {
				$("#face").removeClass("face3").addClass("face1");
			}, 100);
		}, 100);
	}, 5000);
};