<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['subject'] }}</title>
</head>
<body>
<h2>{{$data['title']}}</h2>
<h4>Здравствуйте, {{ $data['recipient'] }}</h4>
<p>{{ $data['body'] }}</p>
<p>Код подтверждения: {{ $data['additional_data']['code'] }}</p>
<p>Действителен до: {{ $data['additional_data']['valid_until'] }}</p>
<p>{{ $data['footer'] }}</p>
</body>
</html>
