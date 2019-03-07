var page = null, playList = [];

var progress = document.getElementById('progress');
var playpause = document.getElementById("play-pause");
var volume = document.getElementById("volume");
var audio = document.getElementById('audio');
audio.controls = false;
audio.addEventListener('timeupdate', function () {
    updateProgress();
}, false);

/**
 * 暂停播放
 */
function togglePlayPause() {
    if (audio.paused || audio.ended) {
        playpause.title = "Pause";
        playpause.innerHTML = '<i class="fa fa-pause fa-3x"></i>';
        audio.play();
    } else {
        playpause.title = "Play";
        playpause.innerHTML = '<i class="fa fa-play fa-3x"></i>';
        audio.pause();
    }
}

/**
 * 设置音量
 */
function setVolume() {
    audio.volume = volume.value;
}

/**
 * 更新进度
 */
function updateProgress() {
    var percent = Math.floor((100 / audio.duration) * audio.currentTime);
    progress.value = percent;
    if (parseInt(percent) > 10 && playList.length === 0) {
        getAjaxList();
        console.log('下一页');
    }
    var canvas = document.getElementById('progress');
    var context = canvas.getContext('2d');
    var centerX = canvas.width / 2;
    var centerY = canvas.height / 2;
    var radius = 150;
    var circ = Math.PI * 2;
    var quart = Math.PI / 2;
    var cpercent = percent / 100;
    context.beginPath();
    context.arc(centerX, centerY, radius, 0, ((circ) * cpercent), false);
    context.lineWidth = 10;
    context.strokeStyle = '#26C5CB';
    context.stroke();
    //if (audio.ended) resetPlayer();
    audio.addEventListener('ended', playEndedHandler, false)
}

function resetPlayer() {
    var canvas = document.getElementById('progress');
    var context = canvas.getContext('2d');
    var centerX = canvas.width / 2;
    var centerY = canvas.height / 2;
    audio.currentTime = 0;
    context.clearRect(0, 0, canvas.width, canvas.height);
    playpause.title = "Play";
    playpause.innerHTML = '<i class="fa fa-play fa-3x"></i>';
}

function getAjaxList() {
    $("#page").text(page);
    $.ajax({
        url: '/speech/api/' + page,
        type: 'GET',
        async: false,
        success: function (res) {
            if (res.state === 200) {
                page = res.data.nextPage;
                playList.push(res.data.url);
            } else {
                alert(res.info);
            }
        },
        error: function () {
            alert('AIP ERR');
        },

    });
}

/**
 * 描述 : 播放完成回调
 * 作者 : jiangshangjun
 */
function playEndedHandler() {
    resetPlayer();
    play();
    togglePlayPause();
}

function play() {
    if (playList.length === 0) {
        getAjaxList();
    }
    audio.src = playList.pop();
}

$(function () {
    if (page === null) {
        page = parseInt(prompt("请输入页数"));
    }
    play();
});
// window.onload = function(){
// 	var arr = ["http://lan.exeweb.lan/uploads/cache/voice/20190307/141c1ebedf7f666ee52563c37d4aa825.mp3","http://lan.exeweb.lan/uploads/cache/voice/20190307/141c1ebedf7f666ee52563c37d4aa825.mp3"];               //把需要播放的歌曲从后往前排，这里已添加两首音乐，可继续添加多个音乐
// 	var myAudio = new Audio();
// 	myAudio.preload = true;
// 	myAudio.controls = true;
// 	myAudio.src = arr.pop();         //每次读数组最后一个元素
// 	myAudio.addEventListener('ended', playEndedHandler, false);
// 	myAudio.play();
// 	document.getElementById("audioBox").appendChild(myAudio);
// 	myAudio.loop = false;//禁止循环，否则无法触发ended事件
// 	function playEndedHandler(){
// 		myAudio.src = arr.pop();
// 		myAudio.play();
// 		console.log(arr.length);
// 		!arr.length && myAudio.removeEventListener('ended',playEndedHandler,false);//只有一个元素时解除绑定
// 	}
// }
