@extends('emails.layouts.basic')

@section('content')
    <tr>
        <td style="padding: 15px 30px; text-align: center;">
            <p style="margin-top: 30px; font-family: 'Roboto', sans-serif;font-size:24px; font-weight: 700;color: #070a14;">
                @lang('new_device.email.title')
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding: 30px 15px 60px 15px;;font-family: 'Roboto', sans-serif;  line-height: 1.5;  " valign="top">
            <div style="margin-top: 45px; font-size: 15px;">
                <p style="font-size: 18px; font-weight: 700; color: #404040; ">
                    @lang('email_texts.hello'), {{ $username }} !
                </p>
                <div style="color: #070a14;">
                    @if(!is_null($device_data))
                        <p style="margin: 0;">
                            @lang('new_device.email.description')
                        </p>
                        <p style="margin: 0;">
                            @lang('new_device.email.browser') <span>{{$device_data['browser']}}</span>
                        </p>
                        <p style="margin: 0;">
                            @lang('new_device.email.ip_address') <span>{{$device_data['ip']}}</span>
                        </p>
                        <p style="margin: 0;">
                            @lang('new_device.email.country') <span>{{$device_data['country']}}</span>
                        </p>
                    @endif
                    @if(!is_null($code))
                            <p style="margin: 5px 0px;font-size: 18px;font-weight: bold;color: green;">
                                @lang('new_device.email.code') <span>{{$code}}</span>
                            </p
                    @endif
                    <p style="margin: 0;">
                        @lang('new_device.email.time') <span>{{$device->created_at}}</span>
                    </p>
                </div>
                <div style="margin: 30px 0 0 0; padding-top: 30px; border-top: 1px solid #d5d5d5">
                    <p style="margin: 0;">
                        @lang('new_device.email.not_you')
                    </p>
                    <p style="margin: 0;">
                        @lang('email_texts.thank_you')
                        <br/>
                        <span style="font-weight: 700; color: #070a14">
                                    @lang('email_texts.team')
                                </span>
                    </p>
                </div>

            </div>
        </td>
    </tr>
@endsection
