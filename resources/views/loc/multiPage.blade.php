<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>locHome</title>
    <!-- Fonts -->
    <!-- Styles -->
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        iframe {
            width: calc(50% - 25px);
            height: 200px;
            overflow: hidden;
            margin: 0 20px 20px 0;
            background: #eee;
        }
    </style>
</head>
<body>
@foreach ($pages as $value)
    <iframe src="{{ $value }}" scrolling="auto" frameborder="0"></iframe>
@endforeach
</body>
</html>
