<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cover</title>
    <link rel="stylesheet" href="{{URL::asset('/css/speech/index.css')}}">
</head>
<body>
<div style="text-align:center;clear:both">
</div>
<div id="container">
    <label class="to-back-label" for="to-back"><i class="fa fa-bars fa-lg"></i></label><!-- 歌曲播放列表按钮 -->
    <input type="checkbox" id="to-back">
    <canvas id="progress" width="320" height="320"></canvas><!-- 进度条 -->
    <div id="player">
        <audio id="audio" controls>
            <source src="" type="audio/mpeg" codecs="mp3"></source>
        </audio>
        <img src="/images/cover.jpeg"><!-- 专辑封面 -->
        <label class="to-lyrics-label" for="to-lyrics"><i class="fa fa-caret-down fa-lg"></i></label>
        <input type="checkbox" id="to-lyrics"><!-- 歌词切换 -->
        <div class="cover">
            <div class="controls">
                <button id="backward" title="播放模式"><i class="fa fa-retweet fa-lg"></i></button>
                <button id="backward" title="后退"><i class="fa fa-backward fa-2x"></i></button>
                <!-- 暂停 -->
                <button id="play-pause" title="暂停" onclick="togglePlayPause()"><i class="fa fa-play fa-3x"></i></button>
                <!-- 前进 -->
                <button id="forward" title="前进"><i class="fa fa-forward fa-2x"></i></button>
                <!-- 循环播放 -->
                <button id="backward" title="循环播放"><i class="fa fa-random fa-lg"></i></button>
                <!-- 音量控制 -->
                <input id="volume" name="volume" min="0" max="1" step="0.1" type="range" onchange="setVolume()"/>
            </div>
            <div class="info">
                <p class="song"><a href="javascript:void(0);" target="_blank">爱的就是你</a></p>
                <p class="author"><a href="javascript:void(0);" target="_blank">王力宏</a></p>
            </div>
            <div class="lyrics">
                <p>爱的就是你</p>
                <p>演唱：王力宏</p>
                <p></p>
                <p>失去才会懂得珍惜</p>
                <p>但我珍惜你</p>
                <p>伤越痛就是爱越深</p>
                <p>我不相信</p>
                <p>你和我同时停止呼吸</p>
                <p>每一次我们靠近</p>
                <p>你让我忘了困惑</p>
                <p>忘了所有烦心</p>
                <p>我把你紧紧拥入怀里</p>
                <p>捧你在我手心</p>
                <p>谁叫我真的爱的就是你</p>
                <p>在爱的纯净世界</p>
                <p>你就是我唯一</p>
                <p>永远永远不要怀疑</p>
                <p>我把你当作我的空气</p>
                <p>如此形影不离</p>
                <p>我大声说我爱的就是你</p>
                <p>在爱的幸福国度</p>
                <p>你就是我唯一</p>
                <p>我唯一爱的就是你</p>
                <p>我真的爱的就是你</p>
                <p></p>
                <p>失去才会懂得珍惜</p>
                <p>但我珍惜你</p>
                <p>伤越痛就是爱越深</p>
                <p>我不相信</p>
                <p>你和我同时停止呼吸</p>
                <p>每一次我们靠近</p>
                <p>你让我忘了困惑</p>
                <p>忘了所有烦心 OH</p>
                <p>我把你紧紧拥入怀里</p>
                <p>捧你在我手心</p>
                <p>谁叫我真的爱的就是你</p>
                <p>在爱的纯净世界</p>
                <p>你就是我唯一</p>
                <p>永远永远不要怀疑</p>
                <p>我把你当作我的空气</p>
                <p>如此形影不离</p>
                <p>我大声说我爱的就是你</p>
                <p>在爱的就幸福国度</p>
                <p>你就是我唯一</p>
                <p>我唯一爱的就是你</p>
                <p>我真的爱的就是你</p>
                <p>就是你 YEYE</p>
                <p>就是你</p>
                <p>就是你 YEYE</p>
                <p>唯一爱的就是你</p>
                <p></p>
                <p>我把你紧紧拥入怀里</p>
                <p>捧你在我手心</p>
                <p>谁叫我真的爱的就是你</p>
                <p>在爱的纯净世界</p>
                <p>你就是我唯一</p>
                <p>永远永远不要怀疑</p>
                <p>我把你当作我的空气</p>
                <p>如此形影不离</p>
                <p>我大声说我爱的就是你</p>
                <p>在爱的就幸福国度</p>
                <p>你就是我唯一</p>
                <p>我唯一爱的就是你</p>
                <p>我真的爱的就是你</p>
                <p>我唯一爱的就是你</p>
                <p>我真的爱的就是你</p>
            </div><!-- 歌词 -->
            <p class="scroll">向下滚动</p>
        </div>
    </div><!-- #播放列表 -->
    <div id="flip-back">
        <ul class="playlist">
            <h3>专辑列表</h3>
            <li><a href="#">1. 习大大爱着彭麻麻</a></li>
            <li><a href="#">2. 一路上有你</a></li>
            <li><a href="#" style="color:#26C5CB;"><i class="fa fa-play" style="margin-right:4px;"></i> 3. 爱的就是你</a>
            </li><!-- 正在播放列表 -->
            <li><a href="#">4. 老公赚钱老婆花</a></li>
            <li><a href="#">5. 北京北京</a></li>
            <li><a href="#">6. 小苹果</a></li>
            <li><a href="#">7. 好男人都死哪去了</a></li>
            <li><a href="#">8. 你是我的眼</a></li>
            <li><a href="#">9. 朋友</a></li>
            <li><a href="#">10. 吻别</a></li>
        </ul>
    </div>
</div>
<div id="page"></div>
{{--<div id="audioBox">adad</div>--}}
</body>
<script type="text/javascript" src="{{URL::asset('/static/jquery/jquery-1.11.2.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/js/speech/index.js')}}"></script>
</html>
