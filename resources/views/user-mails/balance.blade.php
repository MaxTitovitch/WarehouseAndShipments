<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Mail</title>
</head>
<body>
    <div class="text-align-center">
        <h1>Hi, {{$user->name}}!</h1>
        <p>Your Balance has changed. Now:  {{$user->balance}} ({{$balanceHistory->transaction_cost}}$, {{$balanceHistory->type}})</p>
        <a href="{{$url}}" class="btn btn-dark">Look It</a>
        <p>Or you can put this URL at your browser: <a href="{{$url}}">{{$url}}</a></p>
    </div>
</body>
</html>
