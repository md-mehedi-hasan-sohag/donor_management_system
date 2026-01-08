<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Campaign;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Pick 2 existing active campaigns
        $campaigns = Campaign::where('status', 'active')->take(2)->get();

        if ($campaigns->count() < 2) {
            return;
        }

        $events = [
            [
                'title' => 'Coldplay Concert',
                'description' => 'Buy a ticket and your contribution will be donated to the campaign below. Enjoy the event while supporting a real cause.',
                'ticket_price' => 200,
                'campaign_id' => $campaigns[0]->id,
            ],
            [
                'title' => 'Winter Charity Event',
                'description' => 'A warm charity night. Ticket sales directly support the linked campaign below — thank you for making a difference.',
                'ticket_price' => 350,
                'campaign_id' => $campaigns[1]->id,
            ],
        ];

        foreach ($events as $e) {
            Event::updateOrCreate(
                ['slug' => Str::slug($e['title'])],
                [
                    'title' => $e['title'],
                    'slug' => Str::slug($e['title']),
                    'description' => $e['description'],
                    'ticket_price' => $e['ticket_price'],
                    'campaign_id' => $e['campaign_id'],
                    'is_active' => true,
                ]
            );
        }
    }
}
