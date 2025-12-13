<?php

namespace App\Helpers;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeHelper
{
    /**
     * Generate QR code as SVG string
     */
    public static function generateSvg(string $data, int $size = 300): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($data);
    }

    /**
     * Generate QR code as data URI (base64 encoded SVG)
     */
    public static function generateDataUri(string $data, int $size = 300): string
    {
        $svg = self::generateSvg($data, $size);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate QR code for campaign
     */
    public static function generateCampaignQr($campaign, int $size = 300): string
    {
        $url = route('campaigns.show', $campaign);
        return self::generateSvg($url, $size);
    }

    /**
     * Generate QR code data URI for campaign
     */
    public static function generateCampaignQrDataUri($campaign, int $size = 300): string
    {
        $url = route('campaigns.show', $campaign);
        return self::generateDataUri($url, $size);
    }
}
