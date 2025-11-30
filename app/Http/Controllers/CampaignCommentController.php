<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignComment;
use Illuminate\Http\Request;

class CampaignCommentController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:campaign_comments,id',
        ]);

        $comment = new CampaignComment();
        $comment->campaign_id = $campaign->id;
        $comment->user_id = auth()->id();
        $comment->parent_id = $request->parent_id;
        $comment->comment = $request->comment;
        $comment->save();

        return back()->with('success', 'Comment posted!');
    }

    public function destroy(CampaignComment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}