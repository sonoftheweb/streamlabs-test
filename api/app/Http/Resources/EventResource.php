<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'summary' => $this->buildSummary($this),
            'read' => $this->read,
            'related_data' => $this->eventable,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function buildSummary($row): string
    {
        return match (true) {
            $row->eventable instanceof \App\Models\Follower => "{$row->eventable->name} followed you!",
            $row->eventable instanceof \App\Models\MerchSale => "{$row->eventable->item} was purchased!",
            $row->eventable instanceof \App\Models\Subscriber =>
            sprintf(
                "%s (%s) subscribed to you!",
                $row->eventable->name,
                'Tier ' . $row->eventable->subscription_tier

            ),
            $row->eventable instanceof \App\Models\Donation => "{$row->eventable->name} donated {$row->eventable->amount} {$row->eventable->currency} to you! Thank you for being awesome",
            default => 'Unknown event type'
        };
    }
}
