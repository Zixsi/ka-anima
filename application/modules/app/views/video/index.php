<!DOCTYPE html> 
<html lang="ru" dir="ltr" data-cast-api-enabled="true">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.plyr.io/3.4.3/plyr.css">
	<script src="https://cdn.plyr.io/3.4.3/plyr.js"></script>
	<style type="text/css">
		html, body {width: 100%; height: 100%; margin: 0px; padding: 0px;}
		.container {margin: 0; width: 100%; height: 100%;}
	</style>
	<title>Video</title>
</head>
<body>
	<div class="container">
		<video id="player" playsinline controls></video>
	</div>
	<script type="text/javascript">
		window.player=new Plyr("#player",{}),window.player.source={type:"video",title:"Example title",sources:[<?foreach($video as $source):?>{src:'<?=$source['video_url']?>',type:'video/<?=$source['type']?>',size: 720},<?endforeach;?>]},window.inter=null,window.trig=!1,window.player.on("ready",function(){if(trig)return!1;var e=document.getElementsByClassName("plyr__video-wrapper"),t=document.createElement("canvas");t.id="player-watermark",t.style.zIndex=5,t.style.width="100%",t.style.height="100%",t.style.position="absolute",t.style.top=0,t.style.left=0,t.style.opacity=.5,e[0].appendChild(t),window.trig=!0}),window.player.on("ready",function(){if(trig)return!1;var e=document.getElementsByClassName("plyr__video-wrapper"),t=document.createElement("canvas");t.id="player-watermark",t.style.zIndex=5,t.style.width="100%",t.style.height="100%",t.style.position="absolute",t.style.top=0,t.style.left=0,t.style.opacity=1,e[0].appendChild(t),window.trig=!0}),window.player.on("play",function(){watermark("UID<?=$user['id']?>")}),window.player.on("pause",function(){clearInterval(window.inter);var e=document.getElementById("player-watermark");e.getContext("2d").clearRect(0,0,e.width,e.height)});var watermark=function(n){var a=document.getElementById("player-watermark"),i=a.getContext("2d");i.fillStyle="#fff",i.strokeStyle="#fff",i.font="italic 14pt Arial";var r=i.measureText(n).width,l=getFontHeight(i.font);window.inter=setInterval(function(){i.clearRect(0,0,a.width,a.height);var e=Math.round(Math.random()*a.width),t=Math.round(Math.random()*a.height);e=e>a.width-r?a.width-r:e,t=t>a.height-l?a.height-l:t,i.fillText(n,e,t)},1e3)},getFontHeight=function(e){var t=document.createElement("span");t.appendChild(document.createTextNode("height")),document.body.appendChild(t),t.style.cssText="font: "+e+"; white-space: nowrap; display: inline;";var n=t.offsetHeight;return document.body.removeChild(t),n};
	</script>
</body>
</html>