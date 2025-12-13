<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Campaign Donation History - {{ $campaign->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .campaign-info {
            background-color: #F3F4F6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .campaign-info h2 {
            margin: 0 0 10px 0;
            color: #4F46E5;
            font-size: 18px;
        }
        .campaign-info p {
            margin: 5px 0;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 16.66%;
            padding: 15px;
            text-align: center;
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 20px;
            color: #4F46E5;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #E5E7EB;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background-color: #4F46E5;
            text-align: center;
            color: white;
            font-size: 10px;
            line-height: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #DEF7EC;
            color: #03543F;
        }
        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .badge-info {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
        .no-donations {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Campaign Donation History</h1>
        <p>DonorLink - Making a Difference Together</p>
        <p>Generated on {{ $generatedAt->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <div class="campaign-info">
        <h2>Campaign Details</h2>
        <p><strong>Title:</strong> {{ $campaign->title }}</p>
        <p><strong>Organizer:</strong> {{ $campaign->user->name }}</p>
        <p><strong>Goal Amount:</strong> ${{ number_format($campaign->goal_amount, 2) }}</p>
        <p><strong>Current Amount:</strong> ${{ number_format($campaign->current_amount, 2) }}</p>
        <p><strong>Campaign Status:</strong> {{ ucfirst($campaign->status) }}</p>
        @if($campaign->end_date)
        <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($campaign->end_date)->format('F d, Y') }}</p>
        @endif

        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ min($campaign->progressPercentage(), 100) }}%">
                {{ number_format($campaign->progressPercentage(), 1) }}%
            </div>
        </div>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3>${{ number_format($totalReceived, 2) }}</h3>
            <p>Total Received</p>
        </div>
        <div class="stat-box">
            <h3>{{ $totalDonations }}</h3>
            <p>Total Donations</p>
        </div>
        <div class="stat-box">
            <h3>{{ $uniqueDonors }}</h3>
            <p>Unique Donors</p>
        </div>
        <div class="stat-box">
            <h3>${{ number_format($averageDonation, 2) }}</h3>
            <p>Avg. Donation</p>
        </div>
        <div class="stat-box">
            <h3>{{ $monetaryDonations }}</h3>
            <p>Monetary</p>
        </div>
        <div class="stat-box">
            <h3>{{ $inKindDonations }}</h3>
            <p>In-Kind</p>
        </div>
    </div>

    <h2 style="color: #4F46E5; margin-top: 30px;">Donation Records</h2>

    @if($donations->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Donor</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Transaction ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($donations as $donation)
            <tr>
                <td>{{ $donation->created_at->format('M d, Y') }}</td>
                <td>
                    @if($donation->is_anonymous)
                        Anonymous Donor
                    @else
                        {{ $donation->getDonorDisplayName() }}
                    @endif
                </td>
                <td>
                    @if($donation->donation_type === 'in_kind')
                        <span class="badge badge-info">In-Kind</span>
                    @else
                        @if($donation->is_recurring)
                            <span class="badge badge-info">Recurring</span>
                        @else
                            <span class="badge" style="background-color: #E5E7EB; color: #374151;">One-time</span>
                        @endif
                    @endif
                </td>
                <td>
                    @if($donation->donation_type === 'monetary')
                        ${{ number_format($donation->amount, 2) }}
                        <br>
                        <small style="color: #666;">Net: ${{ number_format($donation->net_amount, 2) }}</small>
                    @else
                        In-Kind
                    @endif
                </td>
                <td>
                    @if($donation->payment_status === 'completed')
                        <span class="badge badge-success">Completed</span>
                    @elseif($donation->payment_status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($donation->payment_status === 'failed')
                        <span class="badge badge-danger">Failed</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($donation->payment_status) }}</span>
                    @endif
                </td>
                <td>{{ $donation->transaction_id ?? '-' }}</td>
            </tr>
            @if($donation->message)
            <tr>
                <td colspan="6" style="background-color: #F3F4F6; font-style: italic; font-size: 9px;">
                    Message: {{ $donation->message }}
                </td>
            </tr>
            @endif
            @if($donation->donation_type === 'in_kind' && $donation->in_kind_items)
            <tr>
                <td colspan="6" style="background-color: #F3F4F6; font-size: 9px;">
                    Items: {{ $donation->in_kind_items }}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-donations">
        <p>No donations received yet.</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you to all donors who supported this campaign</p>
        <p>This is a computer-generated document. No signature is required.</p>
    </div>
</body>
</html>
