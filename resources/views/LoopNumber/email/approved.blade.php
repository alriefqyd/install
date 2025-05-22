<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Loop Number Approved</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8f9fa;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <tr>
                    <td align="center" style="padding-bottom: 20px;">
                        <div style="width: 60px; height: 60px; background-color: #0f766e; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <span style="font-size: 32px; color: white;">✓</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 18px; color: #333333; padding-bottom: 20px;">
                        Dear {{$requestor
}},
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color: #444444; padding-bottom: 20px;">
                        Your loop number request has been <strong>approved</strong>. Please use the following Loop Number:
                    </td>
                </tr>
                <tr>
                    <td align="center" style="font-size: 32px; font-weight: bold; color: #0f766e; padding-bottom: 30px;">
                        @foreach($data->loop_number as $k=>$v)
                            {{$v['loop_number']}} <br>
                        @endforeach

                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color: #444444; padding-bottom: 30px;">
                        Please be informed that you must complete the required information on the Instrument Index <a href="{{url('update-instrument-index/'.$sessionId)}}">here </a> once you have successfully commissioned the instruments.
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color: #444444; padding-bottom: 40px;">
                        Thank you.
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 14px; color: #666666;">
                        Best regards,<br><br>
                        <strong>Install (Instrument Index For All)</strong><br>
                        DP.08 Engineering &amp; Project Services
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
