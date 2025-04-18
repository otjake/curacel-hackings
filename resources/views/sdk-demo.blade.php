<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Curacel Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{ asset("sdk/collect.js")  }}"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        header {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 20px;
            margin-left: 50px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo {
            height: 30px;
            margin-right: 30px;
        }

        h1 {
            font-size: 18px;
            font-weight: 400;
            color: #333;
            margin: 0;
        }

        main {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin-top: 80px;
        }

        .content {
            width: 550px;
        }

        .content h2 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .content .highlight {
            color: #1A1AFF;
        }

        .content p {
            font-size: 16px;
            color: #9b9898;
            width: 400px;
            font-weight: 300;
        }

        .content .bold-black {
            color: black !important;
            font-weight: 500 !important;
        }

        .payment-form {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
            position: relative;
        }

        .input-label {
            position: absolute;
            top: 7%;
            left: 15px;
            transform: translateY(-50%);
            font-size: 12px;
            color: #999;
        }

        .form-group-inline {
            display: flex;
            flex-direction: row;
            gap: 10px;
        }

        .form-group-inline input {
            flex: 1;
        }

        .form-group input {
            padding: 15px;
            border: 1px solid #bfbfbf;
            border-radius: 4px;
            margin-bottom: 15px;
            font-family: outfit;
        }

        .fixed-amount {
            color: #666;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            background-color: #f6f5fc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            font-size: 16px;
            font-family: outfit;
            color: #fff;
            background-color: #1A1AFF;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #3636c3;
        }

        footer {
            width: 100%; 
            text-align: left;
            padding: 10px 0; 
            margin-top: auto;
        }

        footer p {
            margin-left: 10px;
        }

        #amount {
            background-color: #f5f5f5;
        }

        /* Media query for small screens */
        @media (max-width: 990px) {
            main {
                flex-direction: column;
                align-items: center;
                margin-top: 20px;
            }

            .content, .payment-form {
                width: 100%;
                margin: 10px 0;
            }

            .content p {
                width: 100%;
            }

            .content .bold-black {
                color: black !important;
                font-weight: 500 !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <img src="{{ asset('img/curacel-blue.svg') }}" alt="Curacel Logo" class="logo">
            <h1>Payment Collection</h1>
        </header>
        <main>
            <div class="content">
                <h2>Experience the Seamless Efficiency of <span class="highlight">Curacel Pay</span></h2>
                <p>Test Curacel Pay's speed & security! Make a live payment & experience the future of payments.</p>
                <p><span class="bold-black">Please note</span> that this is a <span class="bold-black">real transaction</span>, and that your account will be <span class="bold-black">debited.</span></p>
            </div>
            
            <div class="payment-form">
                <form>
                    <div class="form-group form-group-inline">
                        <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
                        <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="input-label">Amount</label>
                        <input type="text" id="amount" name="amount" value="NGN 50.00" readonly>
                        <p class="fixed-amount">This is a Fixed Amount.</p>
                    </div>
                    <button type="submit" id="btn">Pay ₦50.00</button>
                </form>
            </div>

        </main>
        <footer>
            <p>© 2024 Curacel</p>
        </footer>
    </div>

    <script>

    // Pass Laravel config value to JavaScript
    const apiKey = "{{ config('app.demo.api_key') }}";

    document.querySelector("#btn").addEventListener("click", function(e) {
        e.preventDefault()
        try {

            const amountFieldValue = document.querySelector('#amount').value;
            const numericAmount = amountFieldValue.replace(/[^0-9.]/g, ''); 

            CuracelPay.setup({
                apiKey: apiKey,
                amount: numericAmount,
                email: document.querySelector('#email').value,
                currency: 'NGN',
                metadata: {
                        first_name: document.querySelector('#first-name').value,
                        last_name: document.querySelector('#last-name').value
                    },
                callback: function(payment) {
                    if(payment.status === "success") {
                        alert("Payment successful!")
                    } else {
                        alert("Payment status: "+payment.status)
                    }
                }
            }).pay()
        }
        catch (e) {
            alert(e.message);
        }
    })
</script>
</body>
</html>
