<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignQuestion;
use Illuminate\Http\Request;

class CampaignQuestionController extends Controller
{
    /**
     * Store a new question
     */
    public function store(Request $request, Campaign $campaign)
    {
        // If user is logged in, allow unlimited questions
        if (auth()->check()) {
            $validated = $request->validate([
                'question' => 'required|string|max:1000',
            ]);

            $campaign->questions()->create([
                'user_id' => auth()->id(),
                'question' => $validated['question'],
            ]);

            return back()->with('success', 'Your question has been posted!');
        }

        // Guest user logic
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
        ]);

        // Check if this guest email has already asked a question for this campaign
        $existingQuestion = CampaignQuestion::where('campaign_id', $campaign->id)
            ->where('guest_email', $validated['guest_email'])
            ->exists();

        if ($existingQuestion) {
            return redirect()->route('login')
                ->with('error', 'You have already asked a question. Please log in to ask more questions.');
        }

        // Check session to prevent multiple questions in same session
        $sessionKey = 'guest_question_asked_' . $campaign->id;
        if (session()->has($sessionKey)) {
            return redirect()->route('login')
                ->with('error', 'You have already asked a question. Please log in to ask more questions.');
        }

        // Create guest question
        $campaign->questions()->create([
            'user_id' => null,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'question' => $validated['question'],
        ]);

        // Mark in session that this guest has asked a question
        session()->put($sessionKey, true);

        return back()->with('success', 'Your question has been posted! Log in to ask more questions.');
    }

    /**
     * Answer a question (only campaign owner or admin)
     */
    public function answer(Request $request, Campaign $campaign, CampaignQuestion $question)
    {
        // Check authorization
        if (!auth()->user()->isAdmin() && $campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'answer' => 'required|string|max:2000',
        ]);

        $question->update([
            'answer' => $validated['answer'],
            'answered_at' => now(),
        ]);

        return back()->with('success', 'Answer posted successfully!');
    }

    /**
     * Toggle pin status (only campaign owner or admin)
     */
    public function togglePin(Campaign $campaign, CampaignQuestion $question)
    {
        // Check authorization
        if (!auth()->user()->isAdmin() && $campaign->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->update([
            'is_pinned' => !$question->is_pinned,
        ]);

        return back()->with('success', $question->is_pinned ? 'Question pinned!' : 'Question unpinned!');
    }

    /**
     * Delete a question
     */
    public function destroy(Campaign $campaign, CampaignQuestion $question)
    {
        // Only question owner, campaign owner, or admin can delete
        if (!auth()->user()->isAdmin() &&
            $campaign->user_id !== auth()->id() &&
            $question->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->delete();

        return back()->with('success', 'Question deleted successfully!');
    }
}
