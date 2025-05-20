<!DOCTYPE html>
<html>

<head>
    <title>Procurement Monitoring List PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>Procurement Monitoring List</h1>
    <table>
        <thead>
            <tr>
                <th>Received Date</th>
                <th>End User</th>
                <th>PR No.</th>
                <th>Particulars</th>
                <th>Amount</th>
                <th>Creditor</th>
                <th>Remarks</th>
                <th>Responsibility</th>
                <th>Received By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monitorings as $monitoring)
                <tr>
                    <td>{{ $monitoring->received_date }}</td>
                    <td>{{ $monitoring->end_user }}</td>
                    <td>{{ $monitoring->pr_no }}</td>
                    <td>{{ $monitoring->particulars }}</td>
                    <td>{{ $monitoring->amount }}</td>
                    <td>{{ $monitoring->creditor }}</td>
                    <td>{{ $monitoring->remarks }}</td>
                    <td>{{ $monitoring->responsibility }}</td>
                    <td>{{ $monitoring->received_by }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No procurement monitoring available for this report.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>