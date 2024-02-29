<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Список клиентов</title>
</head>
<body>
    <table>
        <thead>
            <tr height="40">
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">ID</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">ФИО</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">День рождения</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Диагноз</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Противопоказания</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Пожелания родителей</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Мама</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Телефон мамы</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Папа</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Телефон папы</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Адрес</th>
                <th style="font-size:12; font-weight:bold; vertical-align:middle; border: 1px solid #ccc;">Созданно</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->id }}</td>
                <td width="40" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->fio }}</td>
                <td width="15" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->burndate }}</td>
                <td width="20" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->diagnos }}</td>
                <td width="20" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->contras }}</td>
                <td width="20" style="border: 1px solid #ccc; word-wrap:break-word;">
                    @foreach($client->wishes as $wish) 
                    Направление-{{ $wish->clas }},Кол-во-{{ $wish->prefer_amount_of_classes }},Дни-{{ $wish->prefer_day }},Время-{{ $wish->prefer_time }}<br /> <br /> 
                    @endforeach
                </td>
                <td width="20" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->mother }}</td>
                <td width="20" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->mother_phone }}</td>
                <td width="20" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->father }}</td>
                <td width="20" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->father_phone }}</td>
                <td width="25" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->adress }}</td>
                <td width="25" style="border: 1px solid #ccc; word-wrap:break-word;">{{ $client->created_by_user->name}} - {{ $client->created_at->format('d.m.Y H:m:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>