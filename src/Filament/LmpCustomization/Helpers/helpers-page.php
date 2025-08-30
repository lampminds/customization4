<?php

use Lampminds\Customization\Models\Page;

function getMetaTitle(string $slug): string
{
    if ($page = Page::where('slug', $slug)->first()) {
        $ret = $page->meta_title;
    }

    return $ret ?? getParameterValue('meta_title', config('app.name'));
}

function getMetaDescription(string $slug): string
{
    if ($page = Page::where('slug', $slug)->first()) {
        $ret = $page->meta_description;
    }

    return $ret ?? getParameterValue('meta_description', 'Default description for the application.');
}

function getMetaKeywords(string $slug): string
{
    if ($page = Page::where('slug', $slug)->first()) {
        $ret = $page->meta_keywords;
    }

    return $ret ?? getParameterValue('meta_keywords', 'default, keywords, for, application');
}
