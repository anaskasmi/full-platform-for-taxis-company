<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .inter{
            margin-top: 120px !important;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">CHILLIWACK TAXI - Account Slip</h2>
    <div class="inter">
        <p>
            <b>Date</b> &nbsp;{{  Str::limit($row->date , 10 , '') }} <span class="float-right"><b>Digital Signatures</b></span>
        </p>
        <p><b>Time</b> &nbsp;{{ substr($row->date, 11,  18) }}</p>
        <p><b>TAXI #</b>&nbsp; {{ $row->vehicle }} </p>
        <p><b>DRIVER ID</b>&nbsp; {{ $row->badge_id }}</p>
        <p><b>Job ID</b>&nbsp;{{ $row->job_id }}</p>
        <p><b>Name</b>&nbsp; {{ $row->customer_name }}</p>
        <p><b>Pick Up</b>&nbsp; {{ $row->pickup }}</p>
        <p><b>Drop off</b>&nbsp; {{ $row->dropoff }}</p>
        <p><b>Meter Amount</b>&nbsp; {{ $row->fare }}</p>
        <p><b>Tip Amount</b>&nbsp; {{ $row->tip }}</p>
        <p><b>Total Amount</b>&nbsp; {{ $row->total }}</p>
        <p><b>Account #</b>&nbsp; {{  $row->account }}</p>
        <p><b>Account Name</b>&nbsp; {{ $row->customer_name }}</p>
        <p><b>Ref.#</b>&nbsp;</p>
        <p><b>Customer Signatures</b>&nbsp;________________________</p>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>