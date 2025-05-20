<!DOCTYPE html>
<html>

<head>
    <title>Summary of Item Procurement List PDF</title>
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
    <h1>Summary of Item Procurement List</h1>
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
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->received_date }}</td>
                    <td>{{ $item->end_user }}</td>
                    <td>{{ $item->pr_no }}</td>
                    <td>{{ $item->particulars }}</td>
                    <td>{{ $item->amount }}</td>
                    <td>{{ $item->creditor }}</td>
                    <td>{{ $item->remarks }}</td>
                    <td>{{ $item->responsibility }}</td>
                    <td>{{ $item->received_by }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No items available for this report.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>