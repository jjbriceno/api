{{-- <tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr> --}}

<tr>
    <td class="header" style="padding:10px 0">
        <a href="{{ $url }}" style="display: inline-block;">
            {{ $slot }}
            <br>
            <img src="{{ asset('images/promusica-email.png') }}" class="img-header img-fluid" alt="logo {{ $slot }}"
                width="100">
        </a>
    </td>
</tr>
