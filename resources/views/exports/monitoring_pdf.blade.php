<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Procurement Monitoring Report</title>
    <style>
        /* Basic styling for the PDF */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            /* Use a font that supports a wider range of characters */
            font-size: 10px;
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
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>Procurement Monitoring Report</h1>
    <table>
        <thead>
            <tr>
                <th>PR No</th>
                <th>Title</th>
                <th>Processor</th>
                <th>Supplier</th>
                <th>End User</th>
                <th>Status</th>
                <th>Date Endorsement</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->pr_no }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->processor }}</td>
                    <td>{{ $item->supplier }}</td>
                    <td>{{ $item->end_user }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->date_endorsement }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>