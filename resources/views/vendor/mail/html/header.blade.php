@props(['url'])
<tr>
<td class="header" style="background-color: #212529; padding: 0; text-align: center;">
<a href="{{ $url }}" style="display: block; padding: 20px 0; text-decoration: none;">
@php
    $logo = \App\Models\SiteSetting::get('site_logo');
@endphp
@if ($logo)
<img src="{{ url(\Illuminate\Support\Facades\Storage::url($logo)) }}" class="logo" alt="{{ config('app.name') }}" style="height: 36px; max-height: 36px; width: auto;">
@else
<span style="color: #ffffff; font-size: 22px; font-weight: bold; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">{{ config('app.name') }}</span>
@endif
</a>
</td>
</tr>
