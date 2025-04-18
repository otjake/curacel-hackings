<x-mail::message>

Dear {{ $user->name }},

Please see your OTP below:

<table width="100%" style="background: #f0f4f8; margin-top: 20px; border-radius: 10px; text-align: center; margin-bottom: 20px">
    <tr>
        <td>
            <h3 style="font-size: 25px; text-align: center; margin-top: 30px;">{{ $otp }}</h3>
        </td>
    </tr>
    <tr>
        <td>
            @lang('Powered by ')
            <a href="https://www.curacel.co/">
                <img width="auto" height="13" src="https://res.cloudinary.com/ddble5id6/image/upload/q_auto/v1/pay/curacel-small" alt="curacel_logo">
            </a>
        </td>
    </tr>
</table>

It is valid for 2 minutes.

If you did not request this OTP, please ignore this email.

</x-mail::message>