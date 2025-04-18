### CuracelPay SDK Usage

Simple instructions on how to use CuracelPay SDK to collect payment on your website.

- Create an API key for your account [in settings](https://sandbox.pay.curacel.co/settings)
- Add script in the header of your webpage

```
<script src="https://sandbox.pay.curacel.co/sdk/collect.js"></script>
```

- Setup payment and initiate, CuracelPay SDK will come up

```
<script>
    const payment = CuracelPay.setup({
        apiKey: "<payer-api-key>",
        amount: "<amount>",
        description: "<description>",
        email: "<email>",
        currency: "<currency>"
        reference: "<reference>"
        metadata: [],
        callback: function(payment) {
            console.log(payment)
        }
    })
    payment.pay();
</script>
```
