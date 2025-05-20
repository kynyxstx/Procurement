<!DOCTYPE html>
<html>

<head>
    <title>Item Procurement</title>
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
                <th>ID</th>
                <th>Supplier</th>
                <th>Item/Project</th>
                <th>Unit Cost</th>
                <th>Year</th>
                <th>Month</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->supplier }}</td>
                    <td>{{ $item->item_project }}</td>
                    <td>{{ number_format((float) $item->unit_cost, 2) }}</td>
                    <td>{{ $item->year }}</td>
                    <td>{{ $item->month }}</td>
                    <td>{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    <td>{{ $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : 'N/A' }}</td>

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