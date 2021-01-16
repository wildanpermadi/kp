<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Legalisir</title>

    <style>
        @page {
            margin: 0px;
        }

        .qrcode {
            position: absolute;
            width: 150px;
            height: 150px;
            right: 125px;
            top: 108px;
        }

        body {
            background-image: url("{{ public_path('ijazah/legalisir/'.$legalisir_jpg) }}");
            background-size: cover;
            background-position: center;
            width: 100%;
            height: 100vh;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('ijazah/qrcode/'.$qrcode_file_name) }}" class="qrcode">
</body>
</html>