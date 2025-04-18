<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Payment #{{ $payout->reference }}</title>

        <style>
        body {
            font-family: Outfit, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            margin: 0;
            padding: 0;
            display: flex;
            text-align: center;
            height: 100vh;
        }

        body > * {
            margin: auto;
        }

        .receipt-container {
            background-color: #f1f1f1;
            padding: 30px;
            border-radius: 10px;
            width: 600px;
        }

        .receipt-header .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 0;
        }

        .receipt-header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }

        .receipt-header .left-content,
        .receipt-header .right-content {
            display: table-cell;
            vertical-align: middle;
        }

        .receipt-header .right-content {
            text-align: right;
        }

        .receipt-header .left-content {
            text-align: left;
        }

        .receipt-header .date {
            color: #5E626A;
            font-size: 11px;
        }

        .receipt-header img {
            height: 30px;
        }
  
        .amount {
            font-size: 21px;
            font-weight: 700;
            color: #030124;
            margin-bottom: 5px;
            text-align: left;
        }
        .status {
            color: #06B856;
            font-size: 10px;
            font-weight: 400;
            margin-bottom: 20px;
            text-align: left;
        }
        .amount-title {
            color: #5E626A;
            font-size: 10px;
            font-weight: 400;
            margin-bottom: 5px;
            text-align: left;
        }
        .details {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            text-align: left;
        }

        .details div {
            margin-bottom: 10px;
        }
        .details div span {
            display: block;
            font-weight: bold;
            font-size: 10px;
            color: #5E626A;
            margin-bottom: 5px;
        }

        .details div p {
            margin: 0;
            color: #030124;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
        }
        </style>
    </head>
    <body>
         <div class="receipt-container">
            <div class="receipt-header">
                <div class="left-content">
                    <p class="title">Payment Receipt</p>
                    <div class="date">{{ \Carbon\Carbon::parse($payout->created_at)->format('j M. Y; H:i') }} (UTC)</div>
                </div>
                <div class="right-content">
                    <img src="https://res.cloudinary.com/ddble5id6/image/upload/q_auto/v1/pay/curacel-small" alt="Curacel Logo">
                </div>
            </div>


        <div class="amount-title">Amount</div>
        <div class="amount">@currency($payout->amount, $payout->currency)</div>
        <div>

        @if($payout->status->value == 1)
            <div class="status">Success</div>
        @elseif($payout->status->value == 3)
            <div class="status" style="color: red;">Failed</div>
        @endif
    
        </div>
        <div class="details">
            <div>
                <span>Beneficiary</span>
                <p class="">{{ $payout->beneficiary->name }}</p>
            </div>
            <div>
                <span>Sender</span>
                <p>{{ $payout->payer->name }}</p>
            </div>
            <div>
                <span>Bank</span>
                <p>{{ $payout->beneficiary->bank_name }}</p>
            </div>
            <div>
                <span>Account Number</span>
                <p>{{ $payout->beneficiary->account_number }}</p>
            </div>
            <div>
                <span>Transaction ID</span>
                <p>{{ $payout->reference }}</p>
            </div>
            <div>
                <span>Transaction Time</span>
                <p>{{ $payout->initiated_at->timezone('Africa/Lagos')->format('F jS, Y. h:ia (T)') }}</p>
            </div>
            <div>
                @if(!empty($payout->description))
                    <span>Description</span>
                    <p>{{ $payout->description }}</p>
                @endif
            </div>
        </div>
    </div>
    </div>
    </body>
</html>