@extends('emails.layouts.basic')

@section('content')
    <tr>
        <td style="padding: 15px 30px; text-align: center;">
            <p style="margin-top: 30px; font-family: 'Roboto', sans-serif;font-size:24px; font-weight: 700;color: #070a14;">
                @lang('email_subjects.contact_us')</p>
        </td>
    </tr>
    <tr>
        <td style="padding: 30px 15px 60px 15px;;font-family: 'Roboto', sans-serif;  line-height: 1.5;  " valign="top">
            From: {{$email}}<br> Subject: {{$subject}}<br> Text: {{$text}}<br>
        </td>
    </tr>
@endsection
