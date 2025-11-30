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
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 100px;
    }

    .filter-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .filter-section:last-child {
        border-bottom: none;
    }

    .filter-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--gray-900);
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
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
                    <div class="card campaign-card">
                        @if($campaign->is_urgent)
                            <span class="badge badge-urgent" style="position: absolute; top: 1rem; right: 1rem; z-index: 10;">🔥 Urgent</span>
                        @endif
                        @if($campaign->is_verified)
                            <span class="badge badge-verified" style="position: absolute; top: 1rem; left: 1rem; z-index: 10;">✓ Verified</span>
                        @endif
                        
                        <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://via.placeholder.com/400x200?text=Campaign' }}" alt="{{ $campaign->title }}" class="card-img">
                        
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge badge-info" style="font-size: 0.75rem;">{{ $campaign->category->name }}</span>
                                <span class="badge badge-secondary" style="font-size: 0.75rem; margin-left: 0.5rem;">📍 {{ Str::limit($campaign->location, 20) }}</span>
                            </div>

                            <h3 class="card-title">{{ Str::limit($campaign->title, 60) }}</h3>
                            <p class="card-text">{{ Str::limit($campaign->description, 120) }}</p>
                            
                            <div class="progress mb-2">
                                <div class="progress-bar" style="width: {{ $campaign->progressPercentage() }}%"></div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.875rem; color: var(--gray-600);">
                                <div>
                                    <strong style="color: var(--gray-900); font-size: 1rem;">${{ number_format($campaign->current_amount, 0) }}</strong> raised
                                    <br><span style="font-size: 0.75rem;">of ${{ number_format($campaign->goal_amount, 0) }}</span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: var(--gray-900); font-size: 1rem;">{{ $campaign->daysRemaining() }}</strong> days
                                    <br><span style="font-size: 0.75rem;">{{ $campaign->total_donors }} donors</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary" style="width: 100%;">View Campaign</a>
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