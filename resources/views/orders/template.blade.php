<!DOCTYPE html>
<html>

<head>
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tfoot {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .customer-info {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: left;
            margin-bottom: 10px;
        }
        .customer-content-info {
            color: blue;
        }

        .customer-info h3 {
            margin: 0;
            width: 100px;
        }

        .customer-info p {
            margin: 0;
        }
        .red {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="red">Order #{{ $order->id }} details</h1>
        <div class="customer-info">
            <h4>Customer Name:</h4>
            <p class="customer-content-info">{{ $order->customer_name }}</p>
        </div>
        <div class="customer-info">
            <h4>Customer Address:</h4>
            <p class="customer-content-info">{{ $order->customer_address }}</p>
        </div>
        <h4 class="customer-info">Order Description:</h4>
        <div>
            <p class="customer-content-info">{{ $order->description }}</p>
        </div>
        <h3>Order Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Count</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderItems as $orderItem)
                    <tr>
                        <td>{{ $orderItem->item->name }}</td>
                        <td>{{ $orderItem->item_count }}</td>
                        <td>{{ $orderItem->item_price }}</td>
                        <td>{{ $orderItem->item_total }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" scope="row">Order Total: {{ $order->total_price}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
