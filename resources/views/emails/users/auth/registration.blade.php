@extends('emails.layouts.basic')

@section('content')
    <tr сlass="hello">
        <td style="font: 23px Arial,sans-serif; font-weight: 700; -webkit-text-size-adjust: none; padding-bottom: 75px;">
            @lang('email_texts.hello'), {{ $username }} !
        </td>
    </tr>

    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @lang('email_texts.register_text')
        </td>
    </tr>

    <tr class="link">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none;">
            <a href="{{ $confirmationUrl }}" style="color: #4AB248; text-decoration: none;">
                link_________________
            </a>
        </td>
    </tr>
@endsection
@section('underline')
    <tr class="underline">
        <td>
            <table border="0" cellpadding="0" cellspacing="0"
                   style="max-width: 1000px; width: 100%; margin: 0; padding: 0; background: #ffffff; border-radius: 5px; padding: 10px 74px; margin: 0 auto;">
                <tr>
                    <td style="font: 15px Arial,sans-serif; font-weight: 500; -webkit-text-size-adjust: none;">
                        <p>If the link did not open, copy it to the clipboard, paste it into the address bar of the
                            browser, press <a href="" style="color: #4AB248; text-decoration: none;">Enter.</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
