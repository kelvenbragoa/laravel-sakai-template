<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C-GATE</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: justify;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!--<tr>
<td align="center" style="padding: 20px 0 0 0;">
<img src="https://res.cloudinary.com/dnj59cxlq/image/upload/v1721225544/cdm/cdm-logo.jpg" alt="Company Logo" width="50" height="50" style="display: block;" />
</td>
</tr>-->
        <tr>
            <td style="padding: 0 20px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding: 30px 0; text-align: center;">
                            <h1 style="color: #333333; font-size: 24px; margin: 0;">C-GATE</h1>
                            {{-- <p style="color: #444444; font-size: 18px; line-height: 1.5; margin: 10px 0 0;">User Attempt to Login From Active Directory</p> --}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding: 20px 20px 0px 0;">
                                        <h1 style="color: #333333; font-size: 24px; margin-bottom: 20px;">Dear {{$user->user_full_name}},</h1>
                                        @if ($type_email === 'create_user')
                                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">Your account on the <span style="font-weight: bold;">C-GATE</span>, system has been successfully created. Below are your access credentials:</p>
                                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">Username: <span style="font-weight: bold;">{{$user->user_name}}</span> <br>
                                            Password: <span style="font-weight: bold;">{{$password}}</span>.
                                            </p>
                                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">For security reasons, you will be required to change your password upon your first login.</p>
                                        @else
                                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">Your account on the <span style="font-weight: bold;">C-GATE</span>, system has been updated by the administrator. Below are your access credentials:</p>
                                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">Username: <span style="font-weight: bold;">{{$user->user_name}}</span> <br>
                                            Password: <span style="font-weight: bold;">{{$password}}</span>.
                                            </p>
                                            <p style="color: #666666; font-size: 16px; line-height: 1.5;">For your security, you must change your password at your next login.</p>
                                        @endif
                                        <p style="color: #666666; font-size: 16px; line-height: 1.5;">Access the system using the following link: <a href="https://cgate.cornelder.co.mz">https://cgate.cornelder.co.mz</a>.</p>

                                    </td>
                                </tr>
                                <tr>
                                  <td style="padding: 0 0 0;">
                                      <table border="0" cellpadding="0" cellspacing="0" >
                                          <tr>
                                              <td>
                                                  <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://cgate.cornelder.co.mz" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="10%" strokecolor="#1E225C" fillcolor="#1E225C"><w:anchorlock></w:anchorlock><center style="color:#ffffff;font-family:sans-serif;font-size:14px;font-weight:bold;">Access C-GATE</center></v:roundrect><![endif]-->
                                                  <!--[if !mso]><!-->
                                                  <a href="https://cgate.cornelder.co.mz" style="background-color: #1E225C; border: 1px solid #1E225C; border-radius: 4px; color: #ffffff; display: inline-block; font-family: sans-serif; font-size: 14px; font-weight: bold; line-height: 40px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;">Access C-GATE</a>
                                                  <!--<![endif]-->
                                              </td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>
                              
                              
                                {{-- <tr>
                                    <td>
                                        <!-- File Download Button-->
                                        <p style="color: #666666; font-size: 16px; margin-top: 20px;">Download our user guide:</p>
                                        <table border="0" cellpadding="0" cellspacing="0" style="margin: 10px 0;">
                                            <tr>
                                                <td>
                                                    <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://example.com/profile" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="10%" strokecolor="#1E225C" fillcolor="#ffffff"><w:anchorlock></w:anchorlock><center style="color:#1E225C;font-family:sans-serif;font-size:14px;font-weight:bold;">Download Guide</center></v:roundrect><![endif]-->
                                                    <!--[if !mso]><!-->
                                                    <a href="https://example.com/profile" style="background-color: #ffffff; border: 2px solid #1E225C; border-radius: 4px; color: #1E225C; display: inline-block; font-family: sans-serif; font-size: 14px; font-weight: bold; line-height: 40px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;">Download Guide</a>
                                                    <!--<![endif]-->
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr> --}}
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; text-align: center; background-color: #e8f0fe; color: #333333;">
                            <p style="margin: 0; font-size: 14px;">If you have any doubt about this email please, contact an administrator!</p>
                            {{-- <p style="margin: 0; font-size: 14px;">Se você tiver alguma dúvida sobre este email por favor, contacte um administrador!</p> --}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 20px 0 ; text-align: center; font-size: 12px; color: #444444;">
                {{-- <p>C-GATE</p> --}}
                <p>Cornelder de Moçambique, S.A.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 20px 20px; text-align: center;"><img src="https://res.cloudinary.com/dnj59cxlq/image/upload/v1721225544/cdm/cdm-logo.jpg" alt="Twitter" width="40" height="40" style="border: 0;">
                <!--<a href="https://facebook.com/example" style="text-decoration: none; margin: 0 5px;"><img src="https://example.com/facebook-icon.png" alt="Facebook" width="24" height="24" style="border: 0;" /></a>
<a href="https://instagram.com/example" style="text-decoration: none; margin: 0 5px;"><img src="https://example.com/instagram-icon.png" alt="Instagram" width="24" height="24" style="border: 0;" /></a>
<a href="https://linkedin.com/company/example" style="text-decoration: none; margin: 0 5px;"><img src="https://example.com/linkedin-icon.png" alt="LinkedIn" width="24" height="24" style="border: 0;" /></a>--></td>
        </tr>
    </table>
</body>

</html>