<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Отчет по посещениям за неделю</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Недельный отчет {{ $startDate }} - {{ $endDate }}.</th>
            </tr>
            <tr>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4">ID</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4">ФИО</th>
                @foreach ($classes as $class)
                    <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4">{{ $class['class_name'] }}</th>                    
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($weekdata as $client)
            <tr>
                <td style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client['id'] }}</td>
                <td width="40" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client['fio'] }}</td>
                @foreach($client['clientdata'] as $clientdata)
                    <td width="20" style="border: 1px solid #ccc; word-wrap:break-word; text-align: center;">{{ $clientdata }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>