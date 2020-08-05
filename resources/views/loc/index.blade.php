<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>locHome</title>
    <link rel="stylesheet" href="{{URL::asset('/static/layui-v2.4.3/layui/css/layui.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">
    <!-- Fonts -->
    <!-- Styles -->
    <style type="text/css">
        * {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: Arial, "Microsoft YaHei", sans-serif;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        #container, .sections, .section {
            height: 100%;
            position: relative;
        }

        #section0,
        #section1,
        #section2,
        #section3 {
            background-color: #000;
            background-size: cover;
            background-position: 50% 50%;
        }

        #section0 {
            background-image: url({{URL::asset('images/wallpaper.jpg')}});
            color: #fff;
            text-shadow: 1px 1px 1px #333;
        }

        #section1 {
            color: #fff;
            text-shadow: 1px 1px 1px #333;
            background-image: url({{URL::asset('images/3.jpg')}});
        }

        #section2 {
            background-image: url({{URL::asset('images/2.jpg')}});
            color: #fff;
            text-shadow: 1px 1px 1px #666;
        }

        #section3 {
            color: #008283;
            background-image: url({{URL::asset('images/1.jpg')}});
            text-shadow: 1px 1px 1px #fff;
        }

        /*#section0 p {*/
        /*color: #F1FF00;*/
        /*}*/

        #section3 p {
            color: #00A3AF;
        }

        .left {
            float: left;
        }

        .intro {
            position: absolute;
            top: 50%;
            width: 100%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            text-align: center;
        }

        .pages {
            position: fixed;
            list-style: none;
        }

        .vertical.pages {
            right: 10px;
            top: 50%;
        }

        .horizontal.pages {
            left: 50%;
            bottom: 10px;
        }

        .pages li {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
            margin: 10px 5px;
            cursor: pointer;
        }

        .horizontal.pages li {
            display: inline-block;
            vertical-align: middle;
        }

        .pages li.active {
            width: 14px;
            height: 14px;
            border: 2px solid #FFFE00;
            background: none;
            margin-left: 0;
        }

        /*#section0 .title {*/
        /*-webkit-transform: translateX(-100%);*/
        /*transform: translateX(-100%);*/
        /*-webkit-animation: sectitle0 1s ease-in-out 100ms forwards;*/
        /*animation: sectitle0 1s ease-in-out 100ms forwards;*/
        /*}*/

        /*#section0 p {*/
        /*-webkit-transform: translateX(100%);*/
        /*transform: translateX(100%);*/
        /*-webkit-animation: sec0 1s ease-in-out 100ms forwards;*/
        /*animation: sec0 1s ease-in-out 100ms forwards;*/
        /*}*/

        #section0 .load-move:nth-of-type(odd) {
            -webkit-transform: translateX(-100%);
            transform: translateX(-100%);
            -webkit-animation: sectitle0 1s ease-in-out 100ms forwards;
            animation: sectitle0 0.3s ease-in-out 100ms forwards;
        }

        #section0 .load-move:nth-of-type(even) {
            -webkit-transform: translateX(100%);
            transform: translateX(100%);
            -webkit-animation: sec0 1s ease-in-out 100ms forwards;
            animation: sec0 0.6s ease-in-out 100ms forwards;
        }

        @-webkit-keyframes sectitle0 {
            0% {
                -webkit-transform: translateX(-100%);
                transform: translateX(-100%);
            }
            100% {
                -webkit-transform: translateX(0);
                transform: translateX(0);
            }
        }

        @keyframes sectitle0 {
            0% {
                -webkit-transform: translateX(-100%);
                transform: translateX(-100%);
            }
            100% {
                -webkit-transform: translateX(0);
                transform: translateX(0);
            }
        }

        @-webkit-keyframes sec0 {
            0% {
                -webkit-transform: translateX(100%);
                transform: translateX(100%);
            }
            100% {
                -webkit-transform: translateX(0);
                transform: translateX(0);
            }
        }

        @keyframes sec0 {
            0% {
                -webkit-transform: translateX(100%);
                transform: translateX(100%);
            }
            100% {
                -webkit-transform: translateX(0);
                transform: translateX(0);
            }
        }

        /*卡片样式*/
        .card-tab {
            width: 200px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            text-align: center;
            display: inline-block;
            font-size: 14px;
            border-radius: 10px;
            /*box-shadow: 10px 10px 10px #888888;*/
            transition: all 0.2s;
            margin: 0 10px 16px 0;
        }

        .card-tab:hover {
            transform: scale(1.1);
        }

        .card-tab a {
            color: white;
            text-decoration: none;
        }

        .card-tab-header {
            color: #fff;
            padding: 10px;
            font-size: 16px;
        }

        .card-tab-body {
            padding: 10px;
        }

        .card-tab-body li {
            list-style-type: none;
        }

        .card-tab-body a {
            padding: 5px 0;
            display: block;
        }

        .card-tab-body a:hover {
            background: #64B5F6;
            color: #FF8F00;
            background-color: rgba(0, 0, 0, 0.2);

        }

        /*卡片样式*/

        .search-form {
            padding-bottom: 10px;
        }

        .search-text {
            width: 540px;
            height: 40px;
        }

        .search-button {
            height: 40px;
        }

        .search-button:hover {
            border-color: #D84315;
        }

        .swiper-container {
            width: 800px;
            height: 400px;
        }
    </style>
</head>
<body onload="loadMove()">
<div id="container" data-PageSwitch>
    <div class="sections">

        <div class="section active" id="section0">

            <div class="intro">
                <div class="search-form">
                    <form action="http://www.baidu.com/baidu" target="_self">
                        <input name=tn type=hidden value=baidu>
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <input class="layui-input search-text" lay-verify="title" type='text' autocomplete="off"
                                       name=word size=30
                                       autofocus>
                            </div>
                            <div class="layui-inline">
                                <input class="layui-btn layui-btn-primary search-button" type="submit" value="百度搜索">
                            </div>
                        </div>
                    </form>
                </div>
                @foreach ($configs as $value)
                    <div class="card-tab load-move-layz">
                        <div class="card-tab-header" style="background-color: {{ $value['color'] }};">
                            <p>{{ $value['name'] }}</p>
                        </div>
                        <div class="card-tab-body">
                            <ul>
                                @if ($value['items'])
                                    @foreach ($value['items'] as $item)
                                        <li>
                                            <a target="{{ $item['target'] }}"
                                               href="{{ $value['domain'].$item['url'] }}">{{ $item['name'] }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="section" id="section1">
{{--            <div class="intro">--}}
{{--                <h1 class="title">Example</h1>--}}
{{--                <p>HTML markup example to define 4 sections</p>--}}

{{--            </div>--}}
            <div class="intro">
                <div style="width: 1800px;height: 800px;margin: 0 auto;overflow: auto;color: red;">
                    @foreach ($qcTest['location'] as $location=> $locationQrCode)
                        <div style="display: flex;height: 400px;">
                            <div style="flex: 1;">
                                <img width="200px;" src="{{$locationQrCode}}" alt="{{$location}}">
                                <div style="padding: 10px;">
                                    <p>{{$location}}</p>
                                </div>
                            </div>
                            <div style="flex: 4;">
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        @if(isset($qcTest['skuCode'][$location]))
                                            @foreach ($qcTest['skuCode'][$location] as $item)
                                                <div class="swiper-slide">
                                                    <img width="250px;" src="{{$item->sku_code_qr_code}}"
                                                         alt="{{$item->sku_code}}">
                                                    <div style="padding: 10px;">
                                                        <p>{{$item->sku_code}}</p>
                                                        <p>{{$item->quality_level}}</p>
                                                        <p>可用{{$item->sum_existing_number}}</p>
                                                        <p>锁定{{$item->sum_allocate_number}}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <!-- 如果需要分页器 -->
                                    <div class="swiper-pagination"></div>

                                    <!-- 如果需要导航按钮 -->
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>

                                    <!-- 如果需要滚动条 -->
                                    <div class="swiper-scrollbar"></div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="section" id="section2">
            <div class="intro" style="">
                @foreach($timeTo as $val)
                    <h1 class="title">{{$val['name']}}</h1>
                    <span>{{$val['1']}}</span><br>
                    <span>{{$val['2']}}</span><br>
                    <span>{{$val['3']}}</span><br>
                    <span>{{$val['4']}}</span><br>
                    <span>{{$val['5']}}</span><br>
                    <span>{{$val['6']}}</span><br><br>
                @endforeach
            </div>
        </div>
        <div class="section" id="section3">
            <div class="intro">
                <h1 class="title">THE END</h1>
                <p>Everything will be okay in the end. If it's not okay, it's not the end.</p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{URL::asset('/static/jquery/jquery-1.11.2.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/static/layui-v2.4.3/layui/layui.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/js/extend/pageswitch.js')}}"></script>
<script src="https://unpkg.com/swiper/js/swiper.js"> </script>
<script src="https://unpkg.com/swiper/js/swiper.min.js"> </script>
<script>
    function loadMove() {
        //$('.load-move-layz').addClass('load-move');
    }
    var mySwiper = new Swiper ('.swiper-container', {
        direction: 'horizontal', // 垂直切换选项
        loop: false, // 循环模式选项

        // 如果需要分页器
        pagination: {
            el: '.swiper-pagination',
        },

        // 如果需要前进后退按钮
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // 如果需要滚动条
        scrollbar: {
            el: '.swiper-scrollbar',
        },
    })
</script>
<!--<script type="text/javascript">
$("#container").PageSwitch({
    direction : "horizontal"
});
</script>-->
</body>
</html>
