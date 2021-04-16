<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width">
    <title>

    </title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
</head>

<body>


<table border="0" cellpadding="0" cellspacing="0"
       style="max-width: 1280px; width: 100%; margin: 0; padding: 0; background: #fafafa; letter-spacing: 0.6px;">
    <tr>
        <td>
            <img src="{{ asset('assets/mail/background.jpg') }}" alt="" width="1280px" style="display: block; max-width: 100%;"/>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 0;"></td>
    </tr>
    <tr>
        <td style="display: block;">
            <table border="0" cellpadding="0" cellspacing="0"
                   style="max-width: 1000px; width: 100%; margin: 0; padding: 0; background: #ffffff; border-radius: 5px; padding: 65px 74px; padding-bottom: 20px; margin: 0 auto;">
                @yield('content')
            </table>
        </td>
    </tr>

    @yield('underline')

    <tr>
        <td style="padding: 20px 0px;"></td>
    </tr>
    @include('emails.layouts.footer')
</table>

</body>
</html>
