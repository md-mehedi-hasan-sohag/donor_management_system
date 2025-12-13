<?php
/*
===================================================================================
FILE: resources/views/campaigns/index.blade.php
===================================================================================
*/
?>
@extends('layouts.app')

@section('title', 'Browse Campaigns - DonorLink')

@section('content')
<style>
    .filter-sidebar {
        background: var(--card-bg);
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 100px;
        border: 1px solid var(--border-color);
    }

    .filter-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .filter-section:last-child {
        border-bottom: none;
    }

    .filter-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        color: var(--text-primary);
    }

    /* Container */
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* Grid Layout */
    .grid {
        display: grid;
        gap: 2rem;
    }

    .grid-cols-3 {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 2rem !important;
    }

    /* Campaign Cards */
    .campaign-card {
        background: var(--card-bg);
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .campaign-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        border-color: #334155;
    }

    .card-img {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
    }

    .campaign-card:hover .card-img {
        opacity: 0.95;
    }

    .card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--text-primary);
        line-height: 1.4;
        min-height: 2.8rem;
    }

    .card-text {
        color: var(--text-secondary);
        margin-bottom: 1rem;
        line-height: 1.5;
        font-size: 0.9rem;
        flex-grow: 1;
    }

    .progress {
        height: 8px;
        background: var(--border-color);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: #334155;
        border-radius: 10px;
        transition: width 1s ease;
    }

    .badge-urgent {
        background: rgba(239, 68, 68, 0.95) !important;
        color: white !important;
        font-size: 0.75rem !important;
        padding: 0.5rem 1rem !important;
        border-radius: 50px !important;
        font-weight: 700 !important;
    }

    .badge-verified {
        background: rgba(16, 185, 129, 0.95) !important;
        color: white !important;
        font-size: 0.75rem !important;
        padding: 0.5rem 1rem !important;
        border-radius: 50px !important;
        font-weight: 700 !important;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .grid-cols-3 {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr !important;
        }

        .grid-cols-3 {
            grid-template-columns: 1fr !important;
        }

        .filter-sidebar {
            position: static;
            margin-bottom: 2rem;
        }
    }
</style>

<div style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); padding: 3rem 0; color: white;">
    <div class="container">
        <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">Discover Campaigns</h1>
        <p style="font-size: 1.125rem; opacity: 0.9;">Find and support causes that matter to you</p>
    </div>
</div>

<div class="container mt-4">
    <div class="grid" style="grid-template-columns: 280px 1fr;">
        <!-- Filters Sidebar -->
        <div class="filter-sidebar">
            <form method="GET" action="{{ route('campaigns.index') }}">
                <div class="filter-section">
                    <div class="filter-title">Search</div>
                    <input type="text" name="search" class="form-control" placeholder="Search campaigns..." value="{{ request('search') }}">
                </div>

                <div class="filter-section">
                    <div class="filter-title">Category</div>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-section">
                    <div class="filter-title">Location</div>
                    <input type="text" name="location" class="form-control" placeholder="Enter location..." value="{{ request('location') }}">
                </div>

                <div class="filter-section">
                    <div class="filter-title">Filters</div>
                    <label class="checkbox-label">
                        <input type="checkbox" name="urgent" value="1" {{ request('urgent') ? 'checked' : '' }}>
                        <span>🔥 Urgent only</span>
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }}>
                        <span>✓ Verified only</span>
                    </label>
                </div>

                <div class="filter-section">
                    <div class="filter-title">Sort By</div>
                    <select name="sort" class="form-control">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>Ending Soon</option>
                        <option value="most_funded" {{ request('sort') == 'most_funded' ? 'selected' : '' }}>Most Funded</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Apply Filters</button>
                <a href="{{ route('campaigns.index') }}" class="btn btn-outline mt-2" style="width: 100%; text-align: center;">Clear Filters</a>
            </form>
        </div>

        <!-- Campaigns Grid -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <p style="color: var(--gray-600);">Showing {{ $campaigns->count() }} of {{ $campaigns->total() }} campaigns</p>
            </div>

            @if($campaigns->count() > 0)
                <div class="grid grid-cols-3">
                    @foreach($campaigns as $campaign)
                    <div class="campaign-card">
                        <div style="position: relative; overflow: hidden;">
                            @if($campaign->is_verified)
                                <span class="badge badge-verified" style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">✓ Verified</span>
                            @endif
                            @if($campaign->is_urgent)
                                <span class="badge badge-urgent" style="position: absolute; top: 1rem; left: 1rem; z-index: 10;">🔥 Urgent</span>
                            @endif
                            <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=250&fit=crop' }}"
                                 alt="{{ $campaign->title }}" class="card-img">
                        </div>

                        <div class="card-body">
                            <div>
                                <span class="badge badge-info" style="margin-bottom: 0.75rem; display: inline-block; font-size: 0.75rem;">{{ $campaign->category->name }}</span>
                                <h3 class="card-title">{{ Str::limit($campaign->title, 50) }}</h3>
                                <p class="card-text">{{ Str::limit($campaign->description, 80) }}</p>
                            </div>

                            <div>
                                <div class="progress" style="margin-bottom: 1rem;">
                                    <div class="progress-bar" style="width: {{ $campaign->progressPercentage() }}%"></div>
                                </div>

                                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                                    <div>
                                        <strong style="font-size: 1rem; color: var(--text-primary); display: block;">${{ number_format($campaign->current_amount, 0) }}</strong>
                                        <span style="font-size: 0.8rem;">of ${{ number_format($campaign->goal_amount, 0) }}</span>
                                    </div>
                                    <div style="text-align: right;">
                                        <strong style="font-size: 1rem; color: var(--text-primary); display: block;">{{ $campaign->daysRemaining() }}</strong>
                                        <span style="font-size: 0.8rem;">days left</span>
                                    </div>
                                </div>

                                <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; background: #334155; text-align: center; display: block; text-decoration: none; border-radius: 0.5rem;">
                                    View Campaign →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4" style="display: flex; justify-content: center;">
                    {{ $campaigns->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 1rem;">
                    <p style="font-size: 1.25rem; color: var(--gray-600);">No campaigns found matching your criteria.</p>
                    <a href="{{ route('campaigns.index') }}" class="btn btn-primary mt-3">Clear Filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection