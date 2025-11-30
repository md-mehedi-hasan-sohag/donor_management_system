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
        <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
            @if($campaign->is_verified)
                <span class="badge badge-verified">✓ Verified Campaign</span>
            @endif
            @if($campaign->is_urgent)
                <span class="badge badge-urgent">🔥 Urgent</span>
            @endif
            <span class="badge badge-info">{{ $campaign->category->name }}</span>
        </div>
        <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">{{ $campaign->title }}</h1>
        <p style="font-size: 1.125rem; opacity: 0.9;">By {{ $campaign->user->name }} • 📍 {{ $campaign->location }}</p>
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
                            <button class="btn btn-primary mt-2">🙋 Sign Up as Volunteer</button>
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
                <a href="{{ route('donations.create', $campaign) }}" class="btn btn-primary" style="width: 100%; font-size: 1.125rem; padding: 1rem;">Donate Now</a>
                
                <form action="{{ route('campaigns.follow', $campaign) }}" method="POST" style="margin-top: 0.75rem;">
                    @csrf
                    @if(auth()->user()->followedCampaigns->contains($campaign->id))
                        <button type="submit" class="btn btn-outline" style="width: 100%;">✓ Following</button>
                    @else
                        <button type="submit" class="btn btn-outline" style="width: 100%;">❤️ Follow Campaign</button>
                    @endif
                </form>

                <button class="btn btn-secondary" style="width: 100%; margin-top: 0.75rem;">📤 Share</button>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; font-size: 1.125rem; padding: 1rem;">Donate Now</a>
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
</script>
@endsection