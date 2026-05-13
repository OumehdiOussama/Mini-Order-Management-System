<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('subject')</title>
    <style>
        /* Base Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f0f2f5; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #424770; }

        /* Mobile Adjustments */
        @media screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 10px !important; }
            .content { padding: 32px 20px !important; }
        }

        /* Email Styles */
        .container { max-width: 600px; margin: 0 auto; padding: 40px 0; }
        .card { background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 7px 14px 0 rgba(60,66,87, 0.08), 0 3px 6px 0 rgba(0,0,0, 0.12); border-top: 4px solid #6366f1; }
        .content { padding: 48px; }
        .footer { padding: 32px 20px; text-align: center; font-size: 14px; color: #718096; line-height: 1.5; }
        
        .header-logo { padding: 0 0 32px; text-align: left; }
        .title { color: #32325d; font-size: 24px; font-weight: 700; margin: 0 0 20px; line-height: 1.2; }
        .text { color: #525f7f; font-size: 16px; line-height: 1.6; margin: 0 0 24px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #6366f1; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px rgba(50,50,93,.11), 0 1px 3px rgba(0,0,0,.08); transition: all 0.15s ease; }
        
        .divider { border-top: 1px solid #e6ebf1; margin: 32px 0; }
        .label { font-size: 12px; font-weight: 600; color: #8898aa; text-transform: uppercase; letter-spacing: 0.025em; margin-bottom: 8px; }
        .value { color: #32325d; font-size: 15px; font-weight: 500; }
        
        .badge { display: inline-block; padding: 4px 12px; border-radius: 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }
    </style>
</head>
<body>
    <center>
        <div class="container">
            {{-- Logo Header --}}
            <div class="header-logo">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;">
                    <tr>
                        <td style="vertical-align: middle;">
                            <!-- Professional Logo with Fallback -->
                            <table border="0" cellpadding="0" cellspacing="0" style="background-color: #6366f1; border-radius: 12px; width: 48px; height: 48px; border-collapse: separate;">
                                <tr>
                                    <td align="center" valign="middle">
                                        <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('favicon2.svg'))) }}" alt="OMS" width="48" height="48" style="display: block; border: 0; border-radius: 12px;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="vertical-align: middle; padding-left: 12px;">
                            <span style="font-size: 24px; font-weight: 900; color: #32325d; letter-spacing: -0.04em; line-height: 1; display: block; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">OMS</span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Main Content Card --}}
            <div class="card">
                <div class="content">
                    @yield('content')
                </div>
                
                @hasSection('accent')
                    <div style="background-color: #f8fafc; padding: 32px 48px; border-top: 1px solid #e6ebf1;">
                        @yield('accent')
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p style="margin: 0 0 12px;">OMS &bull; 123 Tech Plaza, SF</p>
                <p style="margin: 0;">
                    <a href="#" style="color: #6366f1; text-decoration: none;">Dashboard</a> &nbsp;&bull;&nbsp; 
                    <a href="#" style="color: #6366f1; text-decoration: none;">Support</a> &nbsp;&bull;&nbsp; 
                    <a href="#" style="color: #6366f1; text-decoration: none;">Privacy</a>
                </p>
            </div>
        </div>
    </center>
</body>
</html>
