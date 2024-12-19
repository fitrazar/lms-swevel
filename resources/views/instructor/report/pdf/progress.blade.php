<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Progress Kursus</title>
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
    <h1>Laporan Progress Kursus</h1>
    <h3>Mentor: {{ Auth::user()->instructor->name }}</h3>

    <table>
        <thead>
            <tr>
                <th>Nama Peserta</th>
                <th>Judul Kursus</th>
                <th>Progress</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr>
                    <td>{{ $course['participant_name'] }}</td>
                    <td>{{ $course['course_title'] }}</td>
                    <td>{{ $course['progress'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
