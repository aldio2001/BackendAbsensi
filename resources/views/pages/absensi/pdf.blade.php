<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .title {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="title">Attendance Report</h1>
    <h2 class="title">
        {{ $dateFilter ? 'Date: ' . $dateFilter : '' }}
        {{ $monthFilter ? 'Month: ' . $monthFilter : '' }}
        {{ $yearFilter ? 'Year: ' . $yearFilter : '' }}
    </h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Latlong In</th>
                <th>Latlong Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->time_in }}</td>
                    <td>{{ $attendance->time_out }}</td>
                    <td>{{ $attendance->latlon_in }}</td>
                    <td>{{ $attendance->latlon_out }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
