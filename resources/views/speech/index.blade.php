<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>H5音乐播放器</title>
    <script type="text/javascript" src="{{URL::asset('/css/speech/index.css')}}"></script>
</head>
<body>
<div class="player">
    <div class="header">音乐播放器</div>
    <div class="albumPic"></div>
    <div class="trackInfo">
        <div class="name"></div>
        <div class="artist"></div>
        <div class="album"></div>
    </div>
    <div class="progress"></div>
    <div class="controls">
        <div class="play">
            <i class="icon-play"></i>
        </div>
        <div class="previous">
            <i class="icon-previous"></i>
        </div>
        <div class="next">
            <i class="icon-next"></i>
        </div>
    </div>
    <div class="time">
        <div class="current"></div>
        <div class="total"></div>
    </div>
    <audio id="audio">
        <source src="">
    </audio>
</div>
</body>
<script type="text/javascript" src="{{URL::asset('/static/jquery/jquery-1.11.2.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/js/speech/playList.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/js/speech/index.js')}}"></script>
</html>
