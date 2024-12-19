<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kursus Selesai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Laporan Penyelesaian Kursus</h1>
    <h3>Mentor: {{ Auth::user()->instructor->name }}</h3>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Nama Bulan</th>
                <th>Total Peserta Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tableData as $record)
                <tr>
                    <td>{{ $record['month'] }}</td>
                    <td>{{ $record['month_name'] }}</td>
                    <td>{{ $record['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
