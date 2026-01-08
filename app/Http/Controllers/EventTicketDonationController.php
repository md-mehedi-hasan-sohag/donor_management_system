<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicketDonation;
use App\Services\DonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventTicketDonationController extends Controller
{
    // 1) Ongoing Events list page
    public function index()
    {
        $events = Event::where('is_active', true)->latest()->get();
        return view('events.index', compact('events'));
    }

    // 2) Buy ticket (donor only)
    public function buy(Request $request, Event $event, DonationService $donationService)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'donor') {
            return back()->withErrors('Only donors can buy tickets.');
        }

        $campaign = $event->campaign;

        // Create a normal donation so campaign amount increases like usual
        $donation = $donationService->processDonation($campaign, $user, [
            'amount' => (float) $event->ticket_price,
            'donation_type' => 'monetary',
            'payment_method' => 'event_ticket',
            'message' => 'Event Ticket: ' . $event->title,
            'is_anonymous' => false,
            'show_amount' => true,
        ]);

        // Create ticket record
        $ticketCode = strtoupper(Str::random(10)) . '-' . strtoupper(Str::random(6));

        $ticket = EventTicketDonation::create([
            'event_id' => $event->id,
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'donation_id' => $donation->id,
            'amount' => $event->ticket_price,
            'ticket_code' => $ticketCode,
            'purchased_at' => now(),
        ]);

        $amount = (int) $event->price; // or the amount you stored

        $impact = app(\App\Services\DonationImpactService::class)->getImpactForAmount($amount);

        if ($impact) {
                session()->flash('impact', [
                'title' => $impact->title,
                'message' => $impact->message,
            ]);
        }

        return redirect()
            ->route('events.ticket.show', $ticket->id)
            ->with('success', 'Bought ticket successfully! Thank you for the donation 🎉');
    }

    // 3) Show ticket page
    public function showTicket($id)
    {
        $ticket = EventTicketDonation::with(['event', 'campaign'])
            ->findOrFail($id);

        if (Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        return view('events.ticket', compact('ticket'));
    }

    // 4) Donor ticket history (optional list)
    public function myTickets()
    {
        $user = Auth::user();

        $tickets = EventTicketDonation::with('event')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('events.my_tickets', compact('tickets'));
    }
}
