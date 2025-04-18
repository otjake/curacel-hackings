<div>
    <x-mail::table>
        |               |          |
        | ------------- | --------:|
        | Status      | <span style="color: {{$invoice->status->color()}}">{{ ucfirst($invoice->status->value) }}</span>     |
        | Due Date      | **{{ $invoice->due_date->format('F j, Y') }}**     |
        | **Amount Due**    | **@currency($invoice->amount_due, $invoice->currency)**      |
    </x-mail::table>
</div>
