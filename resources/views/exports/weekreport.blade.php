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
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4" rowspan="2">ID</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4" rowspan="2">ФИО</th>
                @foreach ($classes as $class)
                    <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4" colspan="3">{{ $class->class_name }}</th>                    
                @endforeach
            </tr>
            <tr>
                @foreach ($classes as $class)
                    <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4">Пожелания родителей</th>
                    <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4">Факт</th>
                    <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc; text-align: center; background: #eceff4">Остаток</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($weekdata as $client)
            <tr>
                <td style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->id }}</td>
                <td width="40" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->fio }}</td>
                @foreach($client->clientdata as $clientdata)
                @if($clientdata[0]<1) 
                    <td width="20" style="border: 1px solid #ccc; word-wrap:break-word; background: #ffc9c9; text-align: center;">{{ $clientdata[0] }}</td>
                @else
                    <td width="20" style="border: 1px solid #ccc; word-wrap:break-word; text-align: center;">{{ $clientdata[0] }}</td>
                @endif
                    <td width="20" style="border: 1px solid #ccc; word-wrap:break-word; text-align: center;">{{ $clientdata[1] }}</td>
                @if($clientdata[2]>0)
                    <td width="20" style="border: 1px solid #ccc; word-wrap:break-word; background: #ffc9c9; text-align: center;">{{ $clientdata[2] }}</td>
                @else
                    <td width="20" style="border: 1px solid #ccc; word-wrap:break-word; text-align: center;">{{ $clientdata[2] }}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>