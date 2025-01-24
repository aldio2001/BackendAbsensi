<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izin Report</title>
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
    <h1 class="title">Permission Report</h1>
    <h2 class="title">
        {{ $dateFilter ? 'Date: ' . $dateFilter : '' }}
        {{ $monthFilter ? 'Month: ' . $monthFilter : '' }}
        {{ $yearFilter ? 'Year: ' . $yearFilter : '' }}
    </h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Date Permission</th>
                <th>Is Approval</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permission as $permission)
                <tr>
                    <td>{{ $permission->user->name ?? 'Data user tidak tersedia' }}</td>
                    <td>{{ $permission->user->position ?? 'Data user tidak tersedia' }}</td>
                    <td>{{ $permission->user->department ?? 'Data user tidak tersedia' }}</td>
                    <td>{{ $permission->date_permission }}</td>
                    <td>{{ $permission->is_approved ? 'Approved' : 'Not Approved' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="footer">
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
