@extends('emails.layouts.basic')

@section('content')
    <tr сlass="hello">
        <td style="font: 23px Arial,sans-serif; font-weight: 700; -webkit-text-size-adjust: none; padding-bottom: 75px;">
            @lang('email_texts.hello'), {{ $name ?? '' }} !
        </td>
    </tr>
    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @lang('email_texts.reset_password_text_1')
        </td>
    </tr>
    <tr class="link">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none;">
            <a href="{{$resetUrl ?? ''}}" style="color: #4AB248; text-decoration: none;">
                link_________________
            </a>
        </td>
    </tr>
@endsection



