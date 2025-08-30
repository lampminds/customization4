<?php

use Lampminds\Customization\Models\Page;

/**
 * Generates a Facebook share link for the given URL.
 *
 * @param string $url The URL to be shared on Facebook.
 *
 * @return string The complete Facebook share link.
 */
function social_link_facebook(string $url): string
{
    return 'https://www.facebook.com/sharer.php?u=' . urlencode($url);
}

/**
 * Generates a Twitter share link with a specified URL and optional text.
 *
 * @param string $url The URL to be included in the Twitter share link.
 * @param string $text Optional. The text to include with the shared link. Defaults to "Check this out".
 * @return string The generated Twitter share link.
 */
function social_link_twitter($url, string $text = ''): string
{
    if ($text === '') {
        $text = __('Check this out');
    }
    return 'https://twitter.com/intent/tweet?text=' . urlencode($text) . '&url=' . urlencode($url);
}

/**
 * Generates a WhatsApp share link with a specified URL.
 *
 * @param string $url The URL to be included in the WhatsApp share link.
 * @return string The generated WhatsApp share link.
 */
function social_link_whatsapp(string $url): string
{
    return 'https://api.whatsapp.com/send?text=' . urlencode($url);
}

/**
 * Generates an email share link with a specified URL and optional subject.
 *
 * @param string $url The URL to be included in the email body.
 * @param string $subject Optional. The subject of the email. Defaults to "Check this out".
 * @return string The generated email share link.
 */
function social_link_email(string $url, string $subject = ''): string
{
    if ($subject === '') {
        $subject = __('Check this out');
    }
    return 'mailto:?subject=' . $subject . '&body=' . urlencode($url);
}
