<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Loop Number Request</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8f9fa;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <tr>
                    <td align="center" style="padding-bottom: 20px;">
                        <div style="width: 60px; height: 60px; background-color: #0f766e; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <span style="font-size: 32px; color: white;">+</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="color: #0f766e; font-size: 24px; font-weight: bold; padding-bottom: 10px;">
                        New Loop Number Request
                    </td>
                </tr>
                <tr>
                    <td align="center" style="color: #444444; font-size: 16px; padding-bottom: 20px;">
                        A new loop number request has been submitted by <strong>{{ $requestor }}</strong> and requires your review.
                    </td>
                </tr>
                <tr>
                    <td align="center" style="color: #666666; font-size: 14px; padding-bottom: 30px;">
                        Please log in to the system to review and take the appropriate action.
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <a href="{{url('/admin/loop-number-requests/'.$data->id . '/edit')}}" style="background-color: #0f766e; color: white; padding: 12px 24px; text-decoration: none; border-radius: 999px; font-size: 16px;">
                            Review Now
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
