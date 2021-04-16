@extends('emails.layouts.basic')

@section('content')
    <tr сlass="hello">
        <td style="font: 23px Arial,sans-serif; font-weight: 700; -webkit-text-size-adjust: none; padding-bottom: 75px;">
            @lang('email_texts.hello'), {{ $userName }}!
        </td>
    </tr>
    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @lang('email_texts.token_purchased_3') <b> {{ $buyerName }} </b> <br>
            @lang('email_texts.token_purchased_4') <b>{{ $tokenId }}</b> @lang('email_texts.token_purchased_5')
        </td>
    </tr>
    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @lang('email_texts.have_a_good_day')
        </td>
    </tr>
@endsection
