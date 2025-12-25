@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">🤝 My Volunteer Dashboard</h2>

    @if($volunteerSignups->isEmpty())
        <div class="alert alert-info">
            You have not applied as a volunteer yet.
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Applied On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($volunteerSignups as $signup)
                            <tr>
                                <td>
                                    {{ $signup->campaign->title ?? 'Campaign Removed' }}
                                </td>

                                <td>
                                    @if($signup->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($signup->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $signup->message ?? '—' }}
                                </td>

                                <td>
                                    {{ $signup->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    @if($signup->campaign)
                                        <a href="{{ route('campaigns.show', $signup->campaign->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View Campaign
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
