<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Receipt</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional Custom CSS if needed */
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
        }
        .receipt-heading {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-details p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <img src="https://uppc.go.ug/sites/default/files/UPPC%20logo.png" alt="Home">
        <div class="receipt-heading">
            <h4>Subscription Receipt</h4>
            <hr>
        </div>

        <div class="receipt-details">
            <p><strong>Name:</strong> {{ $document->createdBy->name }}</p>
            <p><strong>Email:</strong> {{ $document->createdBy->email }}</p>
            <p><strong>Category:</strong> {{ $document->sub_type }}</p>
            <p><strong>Mode Of Payment:</strong> {{ $document->sub_payment_method }}</p>
            <p><strong>Amount (UGX):</strong> {!! number_format($document->sub_amount, 0, '.', ',') !!}</p>
            <p><strong>Paid At:</strong> {{ $document->sub_start_date }}</p>
            <p><strong>Valid Until:</strong> {{ $document->sub_end_date }}</p>
        </div>
    </div>
</body>
</html>
