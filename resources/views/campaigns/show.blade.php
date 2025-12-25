<!-- FILE: resources/views/campaigns/show.blade.php -->
@extends('layouts.app')

@section('title', $campaign->title . ' - DonorLink')

@section('content')
<style>
    .campaign-hero {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(139, 92, 246, 0.9) 100%);
        padding: 3rem 0;
        color: white;
    }

    .campaign-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .campaign-main {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .campaign-sidebar {
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .donation-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .campaign-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 1rem;
        margin-bottom: 2rem;
    }

    .tab-nav {
        display: flex;
        gap: 1rem;
        border-bottom: 2px solid var(--gray-200);
        margin-bottom: 2rem;
    }

    .tab-btn {
        padding: 1rem 1.5rem;
        border: none;
        background: none;
        cursor: pointer;
        font-weight: 600;
        color: var(--gray-600);
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s;
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .comment {
        padding: 1rem;
        border-left: 3px solid var(--primary);
        background: var(--gray-50);
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .update-item {
        padding: 1.5rem;
        border-left: 4px solid var(--primary);
        background: white;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="campaign-hero">
    <div class="container">
        <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
            {!! $campaign->getStatusBadge() !!}
            @if($campaign->is_verified)
                <span class="badge badge-verified">✓ Verified Campaign</span>
            @endif
            @if($campaign->is_urgent && $campaign->canAcceptDonations())
                <span class="badge badge-urgent">🔥 Urgent</span>
            @endif
            <span class="badge badge-info">{{ $campaign->category->name }}</span>
        </div>
        <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">{{ $campaign->title }}</h1>
        <p style="font-size: 1.125rem; opacity: 0.9;">By {{ $campaign->user->name }} • 📍 {{ $campaign->location }}</p>

        @if($campaign->isCompleted())
            <div style="background: #dbeafe; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #3b82f6; margin-top: 1rem;">
                <strong style="color: #1e40af;">Campaign Completed</strong>
                <p style="margin: 0.5rem 0 0 0; color: #1e40af;">This campaign ended on {{ $campaign->completed_at->format('F d, Y') }}. Final amount raised: <strong>${{ number_format($campaign->current_amount, 2) }}</strong> ({{ number_format($campaign->progressPercentage(), 1) }}% of goal)</p>
            </div>
        @endif

        @if($campaign->isArchived())
            <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #6b7280; margin-top: 1rem;">
                <strong style="color: #374151;">Archived Campaign</strong>
                <p style="margin: 0.5rem 0 0 0; color: #4b5563;">This campaign was archived on {{ $campaign->archived_at->format('F d, Y') }}. It is no longer accepting donations.</p>
            </div>
        @endif
    </div>
</div>

<div class="container campaign-content">
    <div>
        <div class="campaign-main">
            <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://via.placeholder.com/800x400?text=Campaign+Image' }}" alt="{{ $campaign->title }}" class="campaign-image">

            <!-- Tabs -->
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab('story')">📖 Story</button>
                <button class="tab-btn" onclick="switchTab('updates')">📢 Updates ({{ $campaign->updates->count() }})</button>
                <button class="tab-btn" onclick="switchTab('comments')">💬 Comments ({{ $campaign->comments->count() }})</button>
                <button class="tab-btn" onclick="switchTab('qa')">❓ Q&A ({{ $campaign->questions->count() }})</button>
                <button class="tab-btn" onclick="switchTab('donors')">❤️ Donors ({{ $campaign->total_donors }})</button>
            </div>

            <!-- Tab: Story -->
            <div id="story-tab" class="tab-content active">
                <div style="line-height: 1.8; color: var(--gray-700);">
                    {!! nl2br(e($campaign->description)) !!}
                </div>

                @if($campaign->in_kind_needs)
                    <div style="margin-top: 2rem; padding: 1.5rem; background: var(--gray-50); border-radius: 0.5rem;">
                        <h3 style="font-weight: 600; margin-bottom: 1rem; color: var(--gray-900);">📦 In-Kind Donations Needed</h3>
                        <p>{{ $campaign->in_kind_needs }}</p>
                    </div>
                @endif

                @if($campaign->accepts_volunteers)
                    <div style="margin-top: 1rem; padding: 1.5rem; background: var(--info); background: #dbeafe; border-radius: 0.5rem;">
                        <h3 style="font-weight: 600; margin-bottom: 1rem; color: #1e40af;">👋 Volunteers Needed</h3>
                        <p style="color: #1e40af;">This campaign is looking for volunteers. Sign up to help make a difference!</p>
                        @auth
                            <a href="#volunteer-signup" class="btn btn-primary mt-2">
                                🙋 Sign Up as Volunteer
                            </a>

                        @endauth
                    </div>
                @endif
            </div>

            <!-- Tab: Updates -->
            <div id="updates-tab" class="tab-content">
                @forelse($campaign->updates as $update)
                    <div class="update-item">
                        @if($update->update_type === 'milestone')
                            <span class="badge badge-success" style="margin-bottom: 0.5rem;">🎉 Milestone</span>
                        @endif
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h3 style="font-weight: 600; color: var(--gray-900);">{{ $update->title }}</h3>
                            <span style="color: var(--gray-500); font-size: 0.875rem;">{{ $update->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="color: var(--gray-700);">{{ $update->content }}</p>
                    </div>
                @empty
                    <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No updates yet.</p>
                @endforelse
            </div>

            <!-- Tab: Comments -->
            <div id="comments-tab" class="tab-content">
                @auth
                    <form action="{{ route('comments.store', $campaign) }}" method="POST" style="margin-bottom: 2rem;">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Leave a comment or ask a question</label>
                            <textarea name="comment" class="form-control" placeholder="Your comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>
                @else
                    <div style="padding: 1.5rem; background: var(--gray-50); border-radius: 0.5rem; margin-bottom: 2rem; text-align: center;">
                        <p style="color: var(--gray-600);">Please <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">login</a> to leave a comment.</p>
                    </div>
                @endauth

                @forelse($campaign->comments as $comment)
                    <div class="comment">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <strong>{{ $comment->user->name }}</strong>
                            <span style="color: var(--gray-500); font-size: 0.875rem;">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="color: var(--gray-700);">{{ $comment->comment }}</p>
                    </div>
                @empty
                    <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>

            <div class="text-muted mb-2">
                👁️ {{ number_format($campaign->views) }} views
            </div>


            <!-- Tab: Q&A -->
            <div id="qa-tab" class="tab-content">
                @auth
                    <form action="{{ route('questions.store', $campaign) }}" method="POST" style="margin-bottom: 2rem; padding: 1.5rem; background: var(--gray-50); border-radius: 0.75rem;">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Ask a Question</label>
                            <textarea name="question" class="form-control" rows="3" placeholder="Have a question about this campaign? Ask here..." required maxlength="1000"></textarea>
                            <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem;">Maximum 1000 characters</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Post Question</button>
                    </form>
                @else
                    <!-- Guest Question Form -->
                    @php
                        $sessionKey = 'guest_question_asked_' . $campaign->id;
                        $hasAskedQuestion = session()->has($sessionKey);
                    @endphp

                    @if($hasAskedQuestion)
                        <div style="padding: 1.5rem; background: #fef3c7; border-radius: 0.75rem; margin-bottom: 2rem; text-align: center; border: 2px solid #fbbf24;">
                            <p style="color: #92400e; font-weight: 600; margin-bottom: 0.5rem;">✨ You've already asked a question!</p>
                            <p style="color: #92400e; font-size: 0.875rem;">
                                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: underline;">Log in</a>
                                or
                                <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: underline;">create an account</a>
                                to ask more questions and engage with the campaign.
                            </p>
                        </div>
                    @else
                        <form action="{{ route('questions.store', $campaign) }}" method="POST" style="margin-bottom: 2rem; padding: 1.5rem; background: var(--gray-50); border-radius: 0.75rem; border: 2px solid #dbeafe;">
                            @csrf
                            <div style="margin-bottom: 1rem; padding: 0.75rem; background: #dbeafe; border-radius: 0.5rem;">
                                <p style="color: #1e40af; font-size: 0.875rem; margin: 0;">
                                    💡 <strong>First question is free!</strong> You can ask one question without logging in.
                                    <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">Log in</a> to ask more.
                                </p>
                            </div>

                            <div class="row" style="margin-bottom: 1rem;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Your Name *</label>
                                        <input type="text" name="guest_name" class="form-control" placeholder="Enter your name" required maxlength="255" value="{{ old('guest_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Your Email *</label>
                                        <input type="email" name="guest_email" class="form-control" placeholder="your@email.com" required maxlength="255" value="{{ old('guest_email') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Your Question *</label>
                                <textarea name="question" class="form-control" rows="3" placeholder="Have a question about this campaign? Ask here..." required maxlength="1000">{{ old('question') }}</textarea>
                                <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem;">Maximum 1000 characters</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Question</button>
                        </form>
                    @endif
                @endauth

                @forelse($campaign->questions as $question)
                    <div style="padding: 1.5rem; background: {{ $question->is_pinned ? '#fef3c7' : 'white' }}; border: 1px solid var(--gray-200); border-radius: 0.75rem; margin-bottom: 1.5rem;">
                        <!-- Question Header -->
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div style="flex: 1;">
                                @if($question->is_pinned)
                                    <span class="badge badge-warning" style="font-size: 0.75rem; margin-bottom: 0.5rem;">📌 Pinned</span>
                                @endif
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <strong>{{ $question->getAuthorName() }}</strong>
                                    @if($question->isGuest())
                                        <span class="badge" style="background: #e0e7ff; color: #4338ca; font-size: 0.7rem; padding: 0.125rem 0.5rem;">Guest</span>
                                    @endif
                                    <span style="color: var(--gray-500); font-size: 0.875rem;">asked {{ $question->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Actions for campaign owner/admin -->
                            @if(auth()->check() && (auth()->user()->isAdmin() || $campaign->user_id === auth()->id() || (!$question->isGuest() && $question->user_id === auth()->id())))
                                <div style="display: flex; gap: 0.5rem;">
                                    @if(auth()->user()->isAdmin() || $campaign->user_id === auth()->id())
                                        <form action="{{ route('questions.toggle-pin', [$campaign, $question]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" title="{{ $question->is_pinned ? 'Unpin' : 'Pin' }}">
                                                {{ $question->is_pinned ? '📍' : '📌' }}
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('questions.destroy', [$campaign, $question]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; color: #ef4444;" title="Delete">🗑️</button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Question Content -->
                        <div style="padding: 1rem; background: white; border-radius: 0.5rem; margin-bottom: 1rem;">
                            <p style="color: var(--gray-900); margin: 0;">{{ $question->question }}</p>
                        </div>

                        <!-- Answer Section -->
                        @if($question->isAnswered())
                            <div style="padding: 1rem; background: #ecfdf5; border-left: 4px solid #10b981; border-radius: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <span class="badge badge-success" style="font-size: 0.75rem;">✓ Answered</span>
                                    <strong style="color: #065f46;">{{ $campaign->user->name }}</strong>
                                    <span style="color: #059669; font-size: 0.875rem;">• {{ $question->answered_at->diffForHumans() }}</span>
                                </div>
                                <p style="color: #065f46; margin: 0;">{{ $question->answer }}</p>
                            </div>
                        @else
                            @if(auth()->check() && (auth()->user()->isAdmin() || $campaign->user_id === auth()->id()))
                                <form action="{{ route('questions.answer', [$campaign, $question]) }}" method="POST" style="padding: 1rem; background: #dbeafe; border-radius: 0.5rem;">
                                    @csrf
                                    <div class="form-group" style="margin-bottom: 0.75rem;">
                                        <label class="form-label" style="font-weight: 600; color: #1e40af; font-size: 0.875rem;">Answer this question</label>
                                        <textarea name="answer" class="form-control" rows="3" placeholder="Write your answer here..." required maxlength="2000"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="font-size: 0.875rem;">Post Answer</button>
                                </form>
                            @else
                                <div style="padding: 0.75rem; background: #fef3c7; border-radius: 0.5rem; text-align: center;">
                                    <span style="color: #92400e; font-size: 0.875rem;">⏳ Awaiting response from campaign owner</span>
                                </div>
                            @endif
                        @endif
                    </div>
                @empty
                    <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No questions yet. Be the first to ask!</p>
                @endforelse
            </div>

            <!-- Tab: Donors -->
            <div id="donors-tab" class="tab-content">
                @forelse($campaign->donations()->completed()->latest()->take(20)->get() as $donation)
                    <div style="padding: 1rem; background: var(--gray-50); border-radius: 0.5rem; margin-bottom: 0.75rem; display: flex; justify-content: space-between;">
                        <div>
                            <strong>{{ $donation->getDonorDisplayName() }}</strong>
                            @if($donation->message)
                                <p style="color: var(--gray-600); font-size: 0.875rem; margin-top: 0.25rem;">{{ $donation->message }}</p>
                            @endif
                        </div>
                        <div style="text-align: right;">
                            @if(!$donation->is_anonymous || auth()->id() === $campaign->user_id)
                                <strong style="color: var(--primary);">${{ number_format($donation->amount, 2) }}</strong>
                            @endif
                            <p style="color: var(--gray-500); font-size: 0.875rem;">{{ $donation->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No donations yet. Be the first to contribute!</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="campaign-sidebar">
        <div class="donation-card">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                ${{ number_format($campaign->current_amount, 0) }}
            </div>
            <p style="color: var(--gray-600); margin-bottom: 1.5rem;">
                raised of ${{ number_format($campaign->goal_amount, 0) }} goal
            </p>

            <div class="progress mb-3">
                <div class="progress-bar" style="width: {{ $campaign->progressPercentage() }}%"></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; font-size: 0.875rem;">
                <div>
                    <strong style="display: block; font-size: 1.25rem; color: var(--gray-900);">{{ $campaign->total_donors }}</strong>
                    <span style="color: var(--gray-600);">Donors</span>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.25rem; color: var(--gray-900);">{{ $campaign->daysRemaining() }}</strong>
                    <span style="color: var(--gray-600);">Days Left</span>
                </div>
            </div>

            @auth
                @if($campaign->canAcceptDonations())
                    <a href="{{ route('donations.create', $campaign) }}" class="btn btn-primary" style="width: 100%; font-size: 1.125rem; padding: 1rem;">Donate Now</a>
                @else
                    <button class="btn btn-secondary" disabled style="width: 100%; font-size: 1.125rem; padding: 1rem; cursor: not-allowed;">
                        {{ $campaign->isArchived() ? 'Campaign Archived' : 'Campaign Ended' }}
                    </button>
                @endif

                <form action="{{ route('campaigns.follow', $campaign) }}" method="POST" style="margin-top: 0.75rem;">
                    @csrf
                    @if(auth()->user()->followedCampaigns->contains($campaign->id))
                        <button type="submit" class="btn btn-outline" style="width: 100%;">✓ Following</button>
                    @else
                        <button type="submit" class="btn btn-outline" style="width: 100%;">❤️ Follow Campaign</button>
                    @endif
                </form>

                <button class="btn btn-secondary" style="width: 100%; margin-top: 0.75rem;">📤 Share</button>

                @if(auth()->user()->savedCampaigns->contains($campaign->id))
                    <form method="POST"
                            action="{{ route('campaigns.unsave', $campaign) }}"
                            class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100">
                            💔 Remove from Saved
                        </button>
                    </form>
                @else
                    <form method="POST"
                            action="{{ route('campaigns.save', $campaign) }}"
                            class="mt-2">
                        @csrf
                        <button class="btn btn-outline-primary w-100">
                            ❤️ Save Campaign
                        </button>
                    </form>
                @endif
            @endauth

    


                {{-- Fraud Report Section --}}
                <hr style="margin: 1.5rem 0;">

                @auth
                <form action="{{ route('fraud.report') }}" method="POST">
                    @csrf

                    <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                    <div style="background: #fef2f2; padding: 1rem; border-radius: 0.5rem; border: 1px solid #fecaca;">
                        <h4 style="color: #b91c1c; margin-bottom: 0.5rem;">🚨 Report Campaign</h4>

                        <textarea
                            name="reason"
                            class="form-control mb-2"
                            rows="3"
                            placeholder="Explain why this campaign seems suspicious"
                            required></textarea>

                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                            Submit Report
                        </button>
                    </div>
                </form>
                @endauth

                @guest
                <p style="font-size: 0.875rem; color: #92400e; text-align: center;">
                    <a href="{{ route('login') }}">Login</a> to report this campaign
                </p>
                @endguest


                @if((auth()->user()->isAdmin() || $campaign->user_id === auth()->id()) && $campaign->total_donors > 0)
                <a href="{{ route('campaign-donation-history.pdf', $campaign) }}" class="btn btn-outline" style="width: 100%; margin-top: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.25rem;">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                    </svg>
                    Download Donation History
                </a>
                @endif
            @guest
                @if($campaign->canAcceptDonations())
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; font-size: 1.125rem; padding: 1rem;">Donate Now</a>
                @else
                    <button class="btn btn-secondary" disabled style="width: 100%; font-size: 1.125rem; padding: 1rem; cursor: not-allowed;">
                        {{ $campaign->isArchived() ? 'Campaign Archived' : 'Campaign Ended' }}
                    </button>
                @endif

            @endauth
        </div>

        <!-- Campaign Creator Info -->
        <div class="donation-card">
            <h3 style="font-weight: 600; margin-bottom: 1rem;">Campaign Creator</h3>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700;">
                    {{ substr($campaign->user->name, 0, 1) }}
                </div>
                <div>
                    <strong style="display: block; margin-bottom: 0.25rem;">{{ $campaign->user->name }}</strong>
                    @if($campaign->user->verification_status === 'verified')
                        <span class="badge badge-verified" style="font-size: 0.75rem;">✓ Verified</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- QR Code for Sharing -->
        <div class="donation-card">
            <h3 style="font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 0h6v6H0V0zm1 1v4h4V1H1zm0 9h6v6H0v-6zm1 1v4h4v-4H1zm9-10h6v6h-6V0zm1 1v4h4V1h-4z"/>
                    <path d="M2 2h2v2H2V2zm0 9h2v2H2v-2zm9-9h2v2h-2V2zM5 0v2H3V0h2zM3 5V3H0v2h3zm9 0V3h-2v2h2zM0 8h2v2H0V8zm5 0h2v2H5V8zm5 0h2v2h-2V8z"/>
                </svg>
                Share Campaign
            </h3>
            <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">Scan this QR code to share the campaign</p>

            <div style="background: white; padding: 1rem; border-radius: 0.5rem; border: 2px solid var(--gray-200); text-align: center; margin-bottom: 1rem;">
                {!! App\Helpers\QrCodeHelper::generateCampaignQr($campaign, 200) !!}
            </div>

            <a href="{{ route('campaigns.qr-code', $campaign) }}" class="btn btn-outline" style="width: 100%;" download>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.25rem;">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                </svg>
                Download QR Code
            </a>
        </div>

        <!-- Similar Campaigns -->
        @if($similarCampaigns->count() > 0)
        <div class="donation-card">
            <h3 style="font-weight: 600; margin-bottom: 1rem;">Similar Campaigns</h3>
            @foreach($similarCampaigns as $similar)
                <a href="{{ route('campaigns.show', $similar) }}" style="display: block; padding: 1rem; background: var(--gray-50); border-radius: 0.5rem; margin-bottom: 0.75rem; text-decoration: none; color: inherit; transition: background 0.3s;" onmouseover="this.style.background='var(--gray-100)'" onmouseout="this.style.background='var(--gray-50)'">
                    <strong style="display: block; margin-bottom: 0.25rem; color: var(--gray-900);">{{ Str::limit($similar->title, 40) }}</strong>
                    <div class="progress" style="height: 0.5rem; margin-bottom: 0.5rem;">
                        <div class="progress-bar" style="width: {{ $similar->progressPercentage() }}%"></div>
                    </div>
                    <p style="font-size: 0.875rem; color: var(--gray-600);">${{ number_format($similar->current_amount, 0) }} raised</p>
                </a>
            @endforeach
        </div>
        @endif
    </div>
</div>

<script>
    function switchTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        document.getElementById(tab + '-tab').classList.add('active');
        event.target.classList.add('active');
    }
//volunteer Signup
</script>
<hr style="margin: 3rem 0;">

<div id="volunteer-signup" style="
    background: #f8fafc;
    padding: 2rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
">

    <h3 style="margin-bottom: 1rem;">🤝 Volunteer for this Campaign</h3>

    @auth
        <form action="{{ route('volunteer.signup') }}" method="POST">
            @csrf

            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ auth()->user()->name }}" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ auth()->user()->email }}" required>
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Message (optional)</label>
                <textarea name="message" class="form-control"
                          placeholder="Why do you want to volunteer?"></textarea>
            </div>

            <button type="submit" class="btn btn-success">
                Apply as Volunteer
            </button>
        </form>
    @endauth

    @guest
        <p style="color: #92400e;">
            Please <a href="{{ route('login') }}">log in</a> to apply as a volunteer.
        </p>
    @endguest
</div>

@endsection