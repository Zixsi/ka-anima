<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">
	<link rel="icon" type="image/x-icon" sizes="56x64" href="<?=TEMPLATE_DIR?>/admin_1/assets/img/favicon.ico?v=<?=VERSION?>">
    <script>!function(e){var t={};function n(i){if(t[i])return t[i].exports;var r=t[i]={i:i,l:!1,exports:{}};return e[i].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(i,r,function(t){return e[t]}.bind(null,r));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=476)}({187:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.getQueryParameter=function(e,t){for(var n=new RegExp("[?&]"+e+"=*([^&#]*)","g"),i=null,r=null;r=n.exec(t);)i=r;return i&&"string"==typeof i[1]?decodeURIComponent(i[1]):void 0}},312:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.isTouchDevice=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:window,t=e.DocumentTouch,n=e.navigator,i=e.document;return"ontouchstart"in e||t&&i instanceof t||n.maxTouchPoints>0||n.msMaxTouchPoints>0}},476:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(312),r=n(187);n(477),n(478);var o={};try{o=JSON.parse(r.getQueryParameter("additional_params",location.href))}catch(e){}try{var a="1.0-519".replace("1.0-","");Ya.Rum.init({beacon:!0,slots:["v"+a],clck:"https://yandex.ru/clck/click",crossOrigin:"",reqid:o.reqid||o.vsid},{143:"28.3413."+(i.isTouchDevice()?"584":"2048")+".64",880:o.from,625:a,287:"213"})}catch(e){}},477:function(e,t){!function(e,t){if(e.Ya=e.Ya||{},Ya.Rum)throw new Error("Rum: interface is already defined");var n=e.performance,i=n&&n.timing&&n.timing.navigationStart||Ya.startPageLoad||+new Date,r=e.requestAnimationFrame;Ya.Rum={enabled:!!n,vsStart:document.visibilityState,vsChanged:!1,_defTimes:[],_defRes:[],_deltaMarks:{},_settings:{},_vars:{},init:function(e,t){this._settings=e,this._vars=t},getTime:n&&n.now?function(){return n.now()}:Date.now?function(){return Date.now()-i}:function(){return new Date-i},time:function(e){this._deltaMarks[e]=[this.getTime()]},timeEnd:function(e){var t=this._deltaMarks[e];t&&0!==t.length&&t.push(this.getTime())},sendTimeMark:function(e,t,n,i){void 0===t&&(t=this.getTime()),this._defTimes.push([e,t,i]),this.mark(e,t)},sendResTiming:function(e,t){this._defRes.push([e,t])},sendRaf:function(e){if(r&&!this.isVisibilityChanged()){var t=this,n="2616."+e;r(function(){t.isVisibilityChanged()||(t.sendTimeMark(n+".205"),r(function(){t.isVisibilityChanged()||t.sendTimeMark(n+".1928")}))})}},isVisibilityChanged:function(){return this.vsStart&&("visible"!==this.vsStart||this.vsChanged)},mark:n&&n.mark?function(e,t){n.mark(e+(t?": "+t:""))}:function(){}},document.addEventListener&&document.addEventListener("visibilitychange",function e(){Ya.Rum.vsChanged=!0,document.removeEventListener("visibilitychange",e)})}(window)},478:function(e,t){!function(){"use strict";if(window.PerformanceLongTaskTiming){var e=Ya.Rum._tti={events:[],observer:new PerformanceObserver(function(t){e.events=e.events.concat(t.getEntries()),e.events.length>100&&e.events.shift()})};e.observer.observe({entryTypes:["longtask"]})}}()}});</script>
    <script>
        var video_playes_params = {
            post_message_config: false,
            volume: 50,
            muted: false,
            auto_quality: false,
            report: false,
            preload: false,
            from: 'other',
            stream_url: '<?=$video?>',
            preview: '<?=$video_img?>',
            additional_params: {"from":"other","vsid":"0000000000000000000000000000000000000000000000000000000000000000"}
        };
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style>
        html {
            height: 100%;
            width: 100%;
        }

        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #000000;
            position: relative;
        }
		#player-watermark {position: absolute; top: 0; bottom: 0; left: 0; right: 0; z-index: 5; height: 100px; width: 300px;}
    </style>
</head>
<body>
<canvas id="player-watermark"></canvas>
<script src="<?=TEMPLATE_DIR?>/admin_1/assets/scripts/player.js"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) { 

       /* var b = document.body;
        var c = document.getElementById("player-watermark");
        var p = document.getElementById("ya-html5-video-player");
        var ctx = c.getContext("2d");
        ctx.clearRect(0,0, 100, 300);
        ctx.fillStyle = "#fff";
        ctx.strokeStyle = "#fff";
        ctx.font = "5rem Arial";
        ctx.fillText('UID_1', 0, 100);

        setInterval(function(){
            var x = Math.round(Math.random() * 600);
            var y = Math.round(Math.random() * 400);
            c.style.left = x + 'px';
            c.style.top = y + 'px';
        }, 1000);*/

        //var e=document.getElementById("ya-html5-video-player2")
        //var e=document.getElementById("ya-html5-video-player2"),t=document.createElement("canvas");t.id="player-watermark",t.style.zIndex=5,t.style.width="100%",t.style.height="100%",t.style.position="absolute",t.style.top=0,t.style.left=0,t.style.opacity=.5,e.appendChild(t);

        
        //window.player.on("pause",function(){clearInterval(window.inter);var e=document.getElementById("player-watermark");e.getContext("2d").clearRect(0,0,e.width,e.height)});

        /*var watermark=function(n){var a=document.getElementById("player-watermark"),i=a.getContext("2d");i.fillStyle="#fff",i.strokeStyle="#fff",i.font="italic 5rem Arial";var r=i.measureText(n).width,l=getFontHeight(i.font);window.inter=setInterval(function(){i.clearRect(0,0,a.width,a.height);var e=Math.round(Math.random()*a.width),t=Math.round(Math.random()*a.height);e=e>a.width-r?a.width-r:e,t=t>a.height-l?a.height-l:t,i.fillText(n,e,t)},1e3)},getFontHeight=function(e){var t=document.createElement("span");t.appendChild(document.createTextNode("height")),document.body.appendChild(t),t.style.cssText="font: "+e+"; white-space: nowrap; display: inline;";var n=t.offsetHeight;return document.body.removeChild(t),n};

        //watermark("UID<?=$user['id']?>");
        watermark("UID_1");*/
    });
</script>
</body>
</html>
