<h2>Thank you for your donation, {{ $user->name }}!</h2>

<p>
    We’ve received your donation successfully. Below are the details:
</p>

<hr>

<p><strong>Campaign:</strong> {{ $donation->campaign->title }}</p>
<p><strong>Amount:</strong> ${{ number_format($donation->amount, 2) }}</p>
<p><strong>Date:</strong> {{ $donation->created_at->format('F d, Y') }}</p>

<hr>

<p>
    Your support helps make a real difference. ❤️  
</p>

<p>— DonorLink Team</p>
