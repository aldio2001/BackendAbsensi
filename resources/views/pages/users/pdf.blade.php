<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Report</title>
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
                <th>Email</th>
                <th>Phone</th>
                <th>position</th>
                <th>created_at</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->position }}</td>
                <td>{{ $user->created_at }}</td>
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
