@extends('layouts.app')

@section('title', 'DonorLink - Empowering Change Through Transparent Giving')

@section('content')
<style>
    * {
        scroll-behavior: smooth;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.95) 0%, rgba(71, 85, 105, 0.95) 50%, rgba(100, 116, 139, 0.95) 100%),
                    url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=1600') center/cover;
        min-height: 95vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        top: -200px;
        right: -200px;
        animation: pulse 4s ease-in-out infinite;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        bottom: -150px;
        left: -150px;
        animation: pulse 5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        animation: fadeInUp 1s ease-out;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        text-shadow: 3px 5px 10px rgba(0,0,0,0.3);
        color: white;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        margin-bottom: 2.5rem;
        opacity: 0.98;
        line-height: 1.7;
        text-shadow: 1px 2px 4px rgba(0,0,0,0.2);
    }

    .hero-buttons {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .hero-btn {
        padding: 1.25rem 3.5rem;
        font-size: 1.25rem;
        font-weight: 700;
        border-radius: 60px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        position: relative;
        overflow: hidden;
    }

    .hero-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .hero-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .hero-btn:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stats Section */
    .stats-section {
        background: linear-gradient(135deg, #334155 0%, #475569 50%, #64748b 100%);
        padding: 5rem 0;
        position: relative;
        margin-top: -80px;
        z-index: 3;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .stat-box {
        text-align: center;
        color: white;
        padding: 2.5rem;
        animation: fadeInUp 1s ease-out;
        position: relative;
    }

    .stat-box::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 2px;
        background: rgba(255,255,255,0.2);
    }

    .stat-box:last-child::after {
        display: none;
    }

    .stat-number {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
        color: white;
    }

    .stat-label {
        font-size: 1.125rem;
        opacity: 0.95;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* How It Works */
    .how-it-works {
        padding: 7rem 0;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .section-header {
        text-align: center;
        max-width: 900px;
        margin: 0 auto 5rem;
        position: relative;
        z-index: 2;
    }

    .section-title {
        font-size: 3.5rem;
        font-weight: 900;
        color: #334155;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .section-subtitle {
        font-size: 1.375rem;
        color: #64748b;
        line-height: 1.8;
    }

    .process-step {
        position: relative;
        padding: 2.5rem;
        text-align: center;
    }

    .step-number {
        width: 100px;
        height: 100px;
        background: #334155;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 900;
        margin: 0 auto 2rem;
        box-shadow: 0 15px 40px rgba(51, 65, 85, 0.3);
        position: relative;
        z-index: 2;
        transition: all 0.4s;
    }

    .process-step:hover .step-number {
        transform: scale(1.15) rotate(10deg);
        box-shadow: 0 20px 50px rgba(51, 65, 85, 0.5);
    }

    .step-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 1rem;
        color: #334155;
    }

    .step-desc {
        color: #64748b;
        line-height: 1.9;
        font-size: 1.05rem;
    }

    /* Success Stories Section */
    .success-stories {
        padding: 7rem 0;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .story-card {
        background: white;
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(51, 65, 85, 0.1);
        transition: all 0.5s;
        height: 100%;
    }

    .story-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 60px rgba(51, 65, 85, 0.2);
    }

    .story-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        position: relative;
    }

    .story-content {
        padding: 2.5rem;
    }

    .story-tag {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        background: #e2e8f0;
        color: #334155;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .story-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        color: #334155;
        line-height: 1.4;
    }

    .story-desc {
        color: #64748b;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .story-impact {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        padding: 1.5rem;
        border-radius: 1rem;
        border-left: 4px solid #334155;
    }

    .impact-stat {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .impact-stat:last-child {
        margin-bottom: 0;
    }

    .impact-label {
        color: #64748b;
        font-weight: 600;
    }

    .impact-value {
        color: #334155;
        font-weight: 800;
        font-size: 1.125rem;
    }

    /* Campaigns Section */
    .campaigns-section {
        padding: 7rem 0;
        background: white;
    }

    .campaign-card {
        background: white;
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(51, 65, 85, 0.15);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        border: 3px solid transparent;
    }

    .campaign-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 30px 70px rgba(51, 65, 85, 0.3);
        border-color: #334155;
    }

    .campaign-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        position: relative;
        transition: transform 0.5s;
    }

    .campaign-card:hover .campaign-image {
        transform: scale(1.1);
    }

    .campaign-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 800;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .campaign-content {
        padding: 2.5rem;
    }

    .campaign-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        color: #334155;
        line-height: 1.4;
    }

    .progress-bar-custom {
        height: 12px;
        background: #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin: 1.5rem 0;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #334155 0%, #475569 100%);
        border-radius: 12px;
        transition: width 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 2px 8px rgba(51, 65, 85, 0.5);
    }

    .campaign-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        color: #64748b;
    }

    /* Testimonials */
    .testimonials-section {
        padding: 7rem 0;
        background: linear-gradient(135deg, #334155 0%, #475569 50%, #64748b 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .testimonials-section::before {
        content: '';
        position: absolute;
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
        top: -400px;
        right: -300px;
        animation: pulse 6s ease-in-out infinite;
    }

    .testimonial-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        padding: 3rem;
        border-radius: 2rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        height: 100%;
        transition: all 0.4s;
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }

    .testimonial-card:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    }

    .testimonial-quote {
        font-size: 1.25rem;
        line-height: 1.9;
        margin-bottom: 2rem;
        font-style: italic;
        font-weight: 500;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .author-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: white;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .author-info h4 {
        font-weight: 800;
        margin-bottom: 0.25rem;
        font-size: 1.125rem;
    }

    .author-info p {
        opacity: 0.9;
        font-size: 0.9rem;
    }

    /* CTA Section */
    .cta-section {
        padding: 7rem 0;
        background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before,
    .cta-section::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    }

    .cta-section::before {
        width: 600px;
        height: 600px;
        top: -300px;
        left: -200px;
    }

    .cta-section::after {
        width: 500px;
        height: 500px;
        bottom: -250px;
        right: -150px;
    }

    .cta-content {
        position: relative;
        z-index: 2;
    }

    .cta-title {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        color: white;
    }

    .cta-subtitle {
        font-size: 1.375rem;
        margin-bottom: 3rem;
        opacity: 0.95;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.8;
    }

    /* Trust Badges */
    .trust-section {
        padding: 5rem 0;
        background: white;
        text-align: center;
    }

    .trust-badge {
        display: inline-flex;
        align-items: center;
        gap: 1.5rem;
        padding: 2rem 3rem;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 1.5rem;
        margin: 0 1rem 1rem;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(51, 65, 85, 0.1);
    }

    .trust-badge:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(51, 65, 85, 0.2);
    }

    .trust-icon {
        font-size: 2.5rem;
    }

    .trust-text {
        text-align: left;
    }

    .trust-text strong {
        display: block;
        font-size: 1.25rem;
        color: #334155;
        margin-bottom: 0.25rem;
        font-weight: 800;
    }

    .trust-text span {
        font-size: 0.875rem;
        color: #64748b;
    }
    .grid {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
    }

    .grid-cols-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .process-step {
        text-align: center;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 2rem;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .process-step:hover {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);
        transform: translateY(-5px);
    }

    .step-number {
        font-size: 3rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 1rem;
    }

    .step-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .step-desc {
        color: #666;
        line-height: 1.6;
    }    

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title { font-size: 2.5rem; }
        .hero-subtitle { font-size: 1.125rem; }
        .section-title { font-size: 2.25rem; }
        .stat-number { font-size: 2.5rem; }
        .cta-title { font-size: 2.25rem; }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content" style="color: white;">
            <h1 class="hero-title">Transform Lives.<br>Create Impact.</h1>
            <p class="hero-subtitle">
                Join the movement of transparent giving. Connect with verified campaigns, track your impact in real-time, 
                and be part of something bigger. Your generosity changes everything.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('campaigns.index') }}" class="btn btn-primary hero-btn" style="background: white; color: #334155;">
                    🌟 Discover Campaigns
                </a>
                <a href="{{ route('register') }}" class="btn hero-btn" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 3px solid white; color: white;">
                    🚀 Start Your Campaign
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="grid grid-cols-4">
            <div class="stat-box">
                <div class="stat-number" data-target="{{ $stats['total_raised'] }}">$0</div>
                <div class="stat-label">Total Raised</div>
            </div>
            <div class="stat-box">
                <div class="stat-number" data-target="{{ $stats['active_campaigns'] }}">0</div>
                <div class="stat-label">Active Campaigns</div>
            </div>
            <div class="stat-box">
                <div class="stat-number" data-target="{{ $stats['total_donors'] }}">0</div>
                <div class="stat-label">Generous Donors</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">100%</div>
                <div class="stat-label">Transparent</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Workeeers -->
<!-- <section class="how-it-works">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Simple, transparent, and effective fundraising in three easy steps</p>
        </div>

        <div class="grid grid-cols-3">
            <div class="process-step">
                <div class="step-number">1</div>
                <h3 class="step-title">Discover Campaigns</h3>
                <p class="step-desc">
                    Browse verified campaigns across education, healthcare, disaster relief, and more. 
                    Use our smart filters to find causes that resonate with your values.
                </p>
            </div>

            <div class="process-step">
                <div class="step-number">2</div>
                <h3 class="step-title">Make Your Impact</h3>
                <p class="step-desc">
                    Choose your amount, set up one-time or recurring donations, and support with 
                    confidence. Every donation is tracked and receipted automatically.
                </p>
            </div>

            <div class="process-step">
                <div class="step-number">3</div>
                <h3 class="step-title">Track Progress</h3>
                <p class="step-desc">
                    Receive real-time updates, view expenditure reports, and see the tangible impact 
                    of your generosity. Stay connected with your supported campaigns.
                </p>
            </div>
        </div>
    </div>
</section> -->

<section class="how-it-works">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Simple, transparent, and effective fundraising in three easy steps</p>
        </div>

        <div class="grid grid-cols-3">
            <div class="process-step">
                <div class="step-number">1</div>
                <h3 class="step-title">Discover Campaigns</h3>
                <p class="step-desc">
                    Browse verified campaigns across education, healthcare, disaster relief, and more. 
                    Use our smart filters to find causes that resonate with your values.
                </p>
            </div>

            <div class="process-step">
                <div class="step-number">2</div>
                <h3 class="step-title">Make Your Impact</h3>
                <p class="step-desc">
                    Choose your amount, set up one-time or recurring donations, and support with 
                    confidence. Every donation is tracked and receipted automatically.
                </p>
            </div>

            <div class="process-step">
                <div class="step-number">3</div>
                <h3 class="step-title">Track Progress</h3>
                <p class="step-desc">
                    Receive real-time updates, view expenditure reports, and see the tangible impact 
                    of your generosity. Stay connected with your supported campaigns.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="success-stories">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Real Stories, Real Impact</h2>
            <p class="section-subtitle">See how DonorLink campaigns are changing lives across the globe</p>
        </div>

        <div class="grid grid-cols-3">
            <div class="story-card">
                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&h=250&fit=crop" alt="Education" class="story-image">
                <div class="story-content">
                    <span class="story-tag">🎓 Education</span>
                    <h3 class="story-title">500 Students Now Have Books</h3>
                    <p class="story-desc">
                        Rural villages in Bangladesh received complete educational supplies, transforming learning 
                        conditions for an entire generation of students.
                    </p>
                    <div class="story-impact">
                        <div class="impact-stat">
                            <span class="impact-label">Students Helped:</span>
                            <span class="impact-value">500+</span>
                        </div>
                        <div class="impact-stat">
                            <span class="impact-label">Books Distributed:</span>
                            <span class="impact-value">2,500</span>
                        </div>
                        <div class="impact-stat">
                            <span class="impact-label">Donors:</span>
                            <span class="impact-value">156</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="story-card">
                <img src="https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?w=400&h=250&fit=crop" alt="Healthcare" class="story-image">
                <div class="story-content">
                    <span class="story-tag">❤️ Healthcare</span>
                    <h3 class="story-title">Life-Saving Surgery Funded</h3>
                    <p class="story-desc">
                        Emergency medical treatment for a young patient was fully funded in just 12 days, 
                        giving a family hope and a second chance at life.
                    </p>
                    <div class="story-impact">
                        <div class="impact-stat">
                            <span class="impact-label">Amount Raised:</span>
                            <span class="impact-value">$15,000</span>
                        </div>
                        <div class="impact-stat">
                            <span class="impact-label">Days to Fund:</span>
                            <span class="impact-value">12</span>
                        </div>
                        <div class="impact-stat">
                            <span class="impact-label">Donors:</span>
                            <span class="impact-value">89</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="story-card">
                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=250&fit=crop" alt="Community" class="story-image">
                <div class="story-content">
                    <span class="story-tag">🏘️ Community</span>
                    <h3 class="story-title">Clean Water for 1,000 Families</h3>
                    <p class="story-desc">
                        Water filtration systems installed in remote villages now provide clean, safe drinking water 
                        to an entire community, preventing waterborne diseases.
                    </p>
                    <div class="story-impact">
                        <div class="impact-stat">
                            <span class="impact-label">Families Served:</span>
                            <span class="impact-value">1,000+</span>
                        </div>
                        <div class="impact-stat">
                            <span class="impact-label">Wells Installed:</span>
                            <span class="impact-value">5</span>
                        </div>
                        <div class="impact-stat">
                            <span class="impact-label">Donors:</span>
                            <span class="impact-value">234</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Campaigns -->
<section class="campaigns-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Campaigns</h2>
            <p class="section-subtitle">Support urgent causes making a real difference today</p>
        </div>

        <div class="grid grid-cols-3">
            @foreach($campaigns as $campaign)
            <div class="campaign-card">
                <div style="position: relative;">
                    @if($campaign->is_verified)
                        <span class="campaign-badge" style="background: rgba(16, 185, 129, 0.9); color: white;">
                            ✓ Verified
                        </span>
                    @endif
                    @if($campaign->is_urgent)
                        <span class="campaign-badge" style="background: rgba(239, 68, 68, 0.9); color: white; top: 1rem; left: 1rem;">
                            🔥 Urgent
                        </span>
                    @endif
                    <img src="{{ $campaign->image_path ? asset('storage/' . $campaign->image_path) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=400&h=250&fit=crop' }}" 
                         alt="{{ $campaign->title }}" class="campaign-image">
                </div>

                <div class="campaign-content">
                    <span class="badge badge-info" style="margin-bottom: 1rem;">{{ $campaign->category->name }}</span>
                    <h3 class="campaign-title">{{ Str::limit($campaign->title, 60) }}</h3>
                    <p style="color: #64748b; margin-bottom: 1.5rem; line-height: 1.6;">
                        {{ Str::limit($campaign->description, 100) }}
                    </p>

                    <div class="campaign-progress">
                        <div class="progress-bar-custom">
                            <div class="progress-fill" style="width: {{ $campaign->progressPercentage() }}%"></div>
                        </div>
                        <div class="campaign-stats">
                            <div>
                                <strong style="font-size: 1.125rem; color: #334155;">${{ number_format($campaign->current_amount, 0) }}</strong>
                                <span>raised of ${{ number_format($campaign->goal_amount, 0) }}</span>
                            </div>
                            <div>
                                <strong style="font-size: 1.125rem; color: #334155;">{{ $campaign->daysRemaining() }}</strong>
                                <span>days left</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary" style="width: 100%; margin-top: 1rem; background: #334155;">
                        View Campaign →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('campaigns.index') }}" class="btn btn-outline" style="padding: 1rem 3rem; font-size: 1.125rem; border: 3px solid #334155; color: #334155;">
                View All Campaigns →
            </a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header" style="color: white;">
            <h2 class="section-title" style="color: white;">What Our Community Says</h2>
            <p class="section-subtitle" style="color: rgba(255,255,255,0.9);">
                Real stories from donors and recipients making a difference
            </p>
        </div>

        <div class="grid grid-cols-3">
            <div class="testimonial-card">
                <p class="testimonial-quote">
                    "DonorLink made it incredibly easy to support causes I care about. The transparency 
                    and regular updates give me confidence that my donations are making a real impact."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">MJ</div>
                    <div class="author-info">
                        <h4>Michael Johnson</h4>
                        <p>Regular Donor</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <p class="testimonial-quote">
                    "As a recipient, DonorLink helped us raise funds for our education program. The 
                    verification process built trust, and we exceeded our goal in just 45 days!"
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">SE</div>
                    <div class="author-info">
                        <h4>Sarah Edwards</h4>
                        <p>Hope Foundation</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <p class="testimonial-quote">
                    "The platform is intuitive, secure, and truly focused on impact. I love the badge 
                    system and being able to track my giving history. It's charitable giving reimagined."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">DC</div>
                    <div class="author-info">
                        <h4>David Chen</h4>
                        <p>Monthly Supporter</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="trust-section">
    <div class="container">
        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; color: #334155;">
            Trusted by Thousands
        </h3>
        <div>
            <div class="trust-badge">
                <div class="trust-icon">🔒</div>
                <div class="trust-text">
                    <strong>Bank-Level Security</strong>
                    <span>SSL Encrypted & PCI Compliant</span>
                </div>
            </div>

            <div class="trust-badge">
                <div class="trust-icon">✓</div>
                <div class="trust-text">
                    <strong>100% Verified</strong>
                    <span>All campaigns undergo KYC</span>
                </div>
            </div>

            <div class="trust-badge">
                <div class="trust-icon">📊</div>
                <div class="trust-text">
                    <strong>Full Transparency</strong>
                    <span>Track every donation</span>
                </div>
            </div>

            <div class="trust-badge">
                <div class="trust-icon">💳</div>
                <div class="trust-text">
                    <strong>Low Fees</strong>
                    <span>Only 2.5% platform fee</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container cta-content">
        <h2 class="cta-title">Ready to Make a Difference?</h2>
        <p class="cta-subtitle">
            Join our community of changemakers. Whether you're looking to donate or raise funds 
            for a cause, DonorLink makes it simple, secure, and impactful.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 1.25rem 3rem; font-size: 1.125rem; background: white; color: #334155;">
                Get Started Free
            </a>
            <a href="{{ route('campaigns.index') }}" class="btn" style="padding: 1.25rem 3rem; font-size: 1.125rem; background: transparent; border: 3px solid white; color: white;">
                Browse Campaigns
            </a>
        </div>
    </div>
</section>

<script>
// Animate numbers on scroll
function animateNumbers() {
    const stats = document.querySelectorAll('.stat-number');
    
    stats.forEach(stat => {
        const target = parseInt(stat.getAttribute('data-target'));
        if (target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = ' + Math.round(target).toLocaleString();
                    clearInterval(timer);
                } else {
                    stat.textContent = ' + Math.round(current).toLocaleString();
                }
            }, 30);
        }
    });
}

// Trigger animation when stats section is visible
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateNumbers();
            observer.disconnect();
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) observer.observe(statsSection);
});
</script>
@endsection