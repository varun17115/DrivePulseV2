<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 40px;
            background: #fff;
            color: #1a202c;
            height: 100%;
            box-sizing: border-box;
        }
        .cert-border {
            border: 8px double #1e3a8a;
            padding: 30px;
            height: 85%;
            text-align: center;
            position: relative;
            background: #ffffff;
        }
        .header-title {
            font-size: 34px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 5px;
        }
        .sub-header {
            font-size: 14px;
            color: #4b5563;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        .presented-to {
            font-size: 16px;
            font-style: italic;
            color: #6b7280;
            margin-bottom: 15px;
        }
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
            text-decoration: underline;
            margin-bottom: 20px;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        .cert-body {
            font-size: 16px;
            line-height: 1.8;
            color: #374151;
            max-width: 750px;
            margin: 0 auto 30px auto;
        }
        .cert-footer {
            width: 100%;
            margin-top: 40px;
        }
        .footer-col {
            display: inline-block;
            width: 30%;
            text-align: center;
            vertical-align: bottom;
        }
        .sig-line {
            border-top: 1px solid #9ca3af;
            width: 80%;
            margin: 0 auto 5px auto;
        }
        .sig-label {
            font-size: 12px;
            color: #4b5563;
            text-transform: uppercase;
        }
        .cert-meta {
            position: absolute;
            bottom: 15px;
            left: 30px;
            right: 30px;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="cert-border">
        <div class="header-title">Certificate of Completion</div>
        <div class="sub-header">DrivePulse Driving Academy & Motor Training Institute</div>

        <div class="presented-to">This is proudly awarded to</div>
        <div class="student-name">{{ $student->user->name }}</div>

        <div class="cert-body">
            for successfully completing the comprehensive professional driving course in
            <strong>{{ $certificate->course_name }}</strong> with a distinguished evaluation grade of
            <strong>"{{ $certificate->grade }}"</strong>, having demonstrated high proficiency in vehicle handling,
            road safety regulations, and defensive driving techniques.
        </div>

        <table style="width: 100%; margin-top: 30px;">
            <tr>
                <td style="width: 33%; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 5px;">{{ $certificate->issue_date->format('d M, Y') }}</div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Date of Issue</div>
                </td>
                <td style="width: 33%; text-align: center;">
                    <div style="font-family: monospace; font-size: 14px; font-weight: bold; color: #1e3a8a; margin-bottom: 5px;">{{ $certificate->certificate_number }}</div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Certificate Serial No.</div>
                </td>
                <td style="width: 33%; text-align: center;">
                    <div style="font-style: italic; font-weight: bold; margin-bottom: 5px;">Authorized Signatory</div>
                    <div class="sig-line"></div>
                    <div class="sig-label">Chief Driving Instructor</div>
                </td>
            </tr>
        </table>

        <div class="cert-meta">
            Verification Hash: {{ $certificate->qr_code_hash }} | Verify online at drivepulse.local/verify-certificate/{{ $certificate->qr_code_hash }}
        </div>
    </div>
</body>
</html>
