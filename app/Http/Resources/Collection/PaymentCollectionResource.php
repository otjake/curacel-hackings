<?php

namespace App\Http\Resources\Collection;

use App\Enums\PaymentCollectionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'reference' => $this->reference,
            'payer_reference' => $this->payer_reference,
            'status' => $this->status,
            'customer' => $this->whenLoaded('customer', fn () => [
                'code' => $this->customer->code,
                'name' => $this->customer->name,
                'email' => $this->customer->email,
            ], null),
            'metadata' => $this->metadata,
            'initiated_at' => $this->created_at,
            'completed_at' => in_array($this->status, [PaymentCollectionStatus::SUCCESS->value, PaymentCollectionStatus::FAILED->value, PaymentCollectionStatus::CANCELLED->value])
                ? $this->updated_at
                : null,
        ];
    }
}
