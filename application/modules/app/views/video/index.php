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
            debug: false,
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
    </style>
</head>
<body>
<script src="<?=TEMPLATE_DIR?>/tools/player.js?v=0.1"></script>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(event) {

    String.random = function (length) {
        let radom13chars = function () {
            return Math.random().toString(16).substring(2, 15)
        }
        let loops = Math.ceil(length / 13)
        return new Array(loops).fill(radom13chars).reduce((string, func) => {
            return string + func()
        }, '').substring(0, length)
    }

    function createCanvas(parent)
    {
        if(parent == null || parent == undefined)
            return;

        var id, canvas;
        id = String.random(16);
        canvas = document.createElement("canvas");
        canvas.id = id;
        parent.insertBefore(canvas, parent.childNodes[0]);

        return id;
    }

    function updateCanvas(canvas)
    {
        if(canvas == null || canvas == undefined)
            return;

        var parent, width, height, pos; 
        parent = canvas.parentNode;
        canvas.style.position = 'absolute';
        // canvas.style.backgroundColor = '#ff0000';
        canvas.style.opacity = 0.65;
        width = parseInt(parent.offsetWidth * 0.3);
        height = parseInt(width / 5);
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';

        pos = calcPos(canvas, parent);
        canvas.style.left = pos.x + 'px';
        canvas.style.top = pos.y + 'px';
    }

    function calcPos(canvas, parent)
    {
        if(posUpdateTimer <= 0)
        {
            var x, y;
            x = Math.random() * ((parent.offsetWidth - canvas.offsetWidth) - 1) + 1
            y = Math.random() * ((parent.offsetHeight - canvas.offsetHeight) - 1) + 1
            posLast = {x: x, y: y};
            posUpdateTimer = posUpdateInterval;
        }

        return posLast;
    }

    function checkCanvas(parent)
    {
        var canvas;
        canvas = document.getElementById(canvasId);
        if(canvasId == null || canvas == null || canvas == undefined)
        {
            var item = document.getElementsByTagName("canvas")[0];
            if(item !== undefined)
                parent.removeChild(item);

            canvasId = createCanvas(parent);
            canvas = document.getElementById(canvasId);
        }

        updateCanvas(canvas);
    }

    function drawText(canvasId, text)
    {
        var canvas, ctx, canvasWidth, canvasHeight, fontSize, scaleWidth, scaleHeight, scaleWidthReverse;

        if(canvasId == null)
            return;

        canvas = document.getElementById(canvasId);
        if(canvas == null || canvas == undefined)
            return;

        canvasWidth = canvas.offsetWidth;
        canvasHeight = canvas.offsetHeight;
        scaleWidth = 200 / canvasWidth;
        scaleWidthReverse = canvasWidth / 200;
        scaleHeight = 100 / canvasHeight;
        fontSize = 20 * scaleWidthReverse;

        ctx = canvas.getContext("2d");
        // console.log(text);
        ctx.save();
        ctx.clearRect(0, 0, 10000, 10000);
        ctx.scale(scaleWidth, scaleHeight);
        ctx.fillStyle = "#ffffff";
        ctx.strokeStyle = "#ffffff";
        ctx.font = "bold " + fontSize + "px Arial";
        ctx.fillText(text, parseInt(10 * scaleWidthReverse), parseInt(45 * scaleWidthReverse));

        ctx.restore();
    }

    function listenPlayerEvents(videoPlayer)
    {
        videoPlayer.addEventListener("play", function() { pause = false; }, true);
        videoPlayer.addEventListener("pause", function() { pause = true; }, true);
    }

    var parent, canvasId, posLast, posUpdateInterval, posUpdateTimer, tickTime, pause, player;
    posUpdateInterval = 5000;
    posUpdateTimer = 0;
    tickTime = 100;
    pause = false;
    setInterval(function(){
        if(parent == null)
        {
            player = document.getElementById("ya-html5-video-player");
            if(player && player != undefined)
            {
                var videoPlayer = player.getElementsByTagName("video")[0];
                parent = player.children[0].children[0].children[1];

                listenPlayerEvents(videoPlayer);
            }
        }

        checkCanvas(parent);
        drawText(canvasId, '<?=$mark?>');

        if(pause == false)
            posUpdateTimer -= tickTime;

    }, tickTime);
});
</script>
</body>
</html>
