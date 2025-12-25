@extends('layouts.app')

@section('title', 'Saved Campaigns')

@section('content')
<div class="container">
    <h2 class="mb-4">❤️ My Saved Campaigns</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($campaigns->count())
        <div class="row">
            @foreach($campaigns as $campaign)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img 
                            src="{{ asset('storage/'.$campaign->image_path) }}" 
                            class="card-img-top"
                            style="height:200px; object-fit:cover;"
                        >

                        <div class="card-body d-flex flex-column">
                            <h5>{{ $campaign->title }}</h5>
                            <p class="text-muted">
                                {{ Str::limit($campaign->description, 80) }}
                            </p>

                            <a href="{{ route('campaigns.show', $campaign) }}"
                               class="btn btn-primary mt-auto">
                                View Campaign
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $campaigns->links() }}
    @else
        <p>You haven’t saved any campaigns yet.</p>
    @endif
</div>
@endsection
