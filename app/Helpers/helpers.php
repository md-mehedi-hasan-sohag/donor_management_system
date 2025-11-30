<?php


if (!function_exists('format_currency')) {
    function format_currency($amount, $currency = 'USD')
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'CAD' => 'C$',
        ];

        $symbol = $symbols[$currency] ?? $currency;
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime)
    {
        return \Carbon\Carbon::parse($datetime)->diffForHumans();
    }
}

if (!function_exists('campaign_status_badge')) {
    function campaign_status_badge($status)
    {
        $badges = [
            'active' => '<span class="badge badge-success">Active</span>',
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
            'expired' => '<span class="badge badge-secondary">Expired</span>',
            'archived' => '<span class="badge badge-dark">Archived</span>',
        ];

        return $badges[$status] ?? $status;
    }
}