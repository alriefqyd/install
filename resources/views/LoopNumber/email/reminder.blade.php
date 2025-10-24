<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reminder: Register Loop Number</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8f9fa;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <tr>
                    <td align="center" style="padding-bottom: 20px;">
                        <div style="width: 60px; height: 60px; background-color: #facc15; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <span style="font-size: 32px; color: white;">⏰</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 18px; color: #333333; padding-bottom: 20px;">
                        Dear {{$requestor}},
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color: #444444; padding-bottom: 20px;">
                        This is a friendly reminder to <strong>register your approved loop number(s)</strong> in the Instrument Index page.
                    </td>
                </tr>
                <tr>
                    <td align="center" style="font-size: 28px; font-weight: bold; color: #0f766e; padding-bottom: 30px;">
                        @foreach($data->loop_number as $k => $v)
                            {{$v['loop_number']}} <br>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color: #444444; padding-bottom: 30px;">
                        Please go to the Instrument Index and complete the registration of the above loop numbers as soon as possible.
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding-bottom: 40px;">
                        <a href="{{ url('/update-instrument-index/'.$data->session_id) }}" style="background-color: #0f766e; color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-size: 16px;">
                            Go to Instrument Index
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color: #444444; padding-bottom: 20px;">
                        Thank you for your prompt attention to this matter.
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
