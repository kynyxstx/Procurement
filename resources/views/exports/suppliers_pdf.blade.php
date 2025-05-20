<!DOCTYPE html>
<html>

<head>
    <title>Suppliers List PDF</title>
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
    <h1>Suppliers List</h1>
    <table>
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Address</th>
                <th>Items</th>
                <th>Contact Person</th>
                <th>Position</th>
                <th>Mobile No.</th>
                <th>Telephone No.</th>
                <th>Email Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->supplier_name }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->items }}</td>
                    <td>{{ $supplier->contact_person }}</td>
                    <td>{{ $supplier->position }}</td>
                    <td>{{ $supplier->mobile_no }}</td>
                    <td>{{ $supplier->telephone_no }}</td>
                    <td>{{ $supplier->email_address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No suppliers available for this report.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>