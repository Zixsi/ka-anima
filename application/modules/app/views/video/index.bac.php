<!DOCTYPE html> 
<html lang="ru" dir="ltr" data-cast-api-enabled="true">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">
	<link rel="icon" type="image/x-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">
	<style type="text/css">
		html, body {width: 100%; height: 100%; margin: 0px; padding: 0px;}
		.container {margin: 0; width: 100%; height: 100%;}
		#player-watermark {position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px;}
	</style>
	<title>Video</title>
</head>
<body>
	<div class="container">
		<iframe scrolling="no" allowfullscreen="" allow="autoplay; encrypted-media" src="<?=$video?>" style="width: 100%; height: 100%; border-width: 0px; display: block;" id="videoframe"></iframe>
	</div>
	<script type="text/javascript">
		var iframe = document.getElementById('videoframe');
    	var iframediv = iframe.contentWindow.document;
    	iframediv.body.innerHTML += '<canvas id="player-watermark" style="width:100%; height:100%"></canvas>';
    	var c = iframediv.getElementById('player-watermark');

    	var ctx = c.getContext('2d');
		ctx.fillRect(25,25,100,100);
		ctx.clearRect(45,45,60,60);
		ctx.strokeRect(50,50,50,50);


    	/*console.log(c);
		// var e=document.getElementById("player-watermark");e.getContext("2d").clearRect(0,0,e.width,e.height)
		var watermark=function(n){var a=n,i=a.getContext("2d");i.fillStyle="#fff",i.strokeStyle="#fff",i.font="italic 14pt Arial";var r=i.measureText(n).width,l=getFontHeight(i.font);window.inter=setInterval(function(){i.clearRect(0,0,a.width,a.height);var e=Math.round(Math.random()*a.width),t=Math.round(Math.random()*a.height);e=e>a.width-r?a.width-r:e,t=t>a.height-l?a.height-l:t,i.fillText(n,e,t)},1e3)},getFontHeight=function(e){var t=document.createElement("span");t.appendChild(document.createTextNode("height")),document.body.appendChild(t),t.style.cssText="font: "+e+"; white-space: nowrap; display: inline;";var n=t.offsetHeight;return document.body.removeChild(t),n};
		watermark(c);*/
	</script>
</body>
</html>