<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>locHome</title>
    <link rel="stylesheet" href="{{URL::asset('/static/layui-v2.4.3/layui/css/layui.css')}}">
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
            background-image: url({{URL::asset('images/4.jpg')}});
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
                @foreach ($configTab as $tabValue)
                    <div class="card-tab load-move-layz">
                        <div class="card-tab-header" style="background-color: {{ $tabValue['color'] }};">
                            <p>{{ $tabValue['name'] }}</p>
                        </div>
                        <div class="card-tab-body">
                            <ul>
                                @foreach ($configTabUrl as $urlKey=> $urlValue)
                                    @if ($tabValue['type']===$urlValue['type'])
                                        <li>
                                            <a target="{{ $urlValue['target'] }}"
                                               href="{{ $tabValue['domain'].$urlValue['url'] }}">{{ $urlKey }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="section" id="section1">
            <div class="intro">
                <h1 class="title">Example</h1>
                <p>HTML markup example to define 4 sections</p>
            </div>
        </div>
        <div class="section" id="section2">
            <div class="intro">
                <h1 class="title">Example</h1>
                <p>The plug-in configuration parameters</p>
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
<script>
    function loadMove() {
        //$('.load-move-layz').addClass('load-move');
    }
</script>
<!--<script type="text/javascript">
$("#container").PageSwitch({
    direction : "horizontal"
});
</script>-->
</body>
</html>
