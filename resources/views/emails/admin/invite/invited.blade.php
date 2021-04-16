@extends('emails.layouts.basic')

@section('content')
    <tr сlass="hello">
        <td style="font: 23px Arial,sans-serif; font-weight: 700; -webkit-text-size-adjust: none; padding-bottom: 75px;">
            @lang('email_texts.super_admin_invite_text')
        </td>
    </tr>

    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            <b> @lang('email_texts.email_is'): </b> {{ $email }}
        </td>
    </tr>

    <tr>
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            <b> @lang('email_texts.password_is'): </b> {{ $password }}
        </td>
    </tr>
    <br>
    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @lang('email_texts.have_a_good_day')
        </td>
    </tr>
@endsection