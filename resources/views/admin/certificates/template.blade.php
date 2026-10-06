<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0px;
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #ffffff;
            margin: 0px;
            padding: 25px;
            color: #1e293b;
            box-sizing: border-box;
        }
        .cert-border {
            border: 8px solid #0f172a;
            padding: 5px;
            height: 94%;
            position: relative;
        }
        .cert-inner-border {
            border: 2px solid #cbd5e1;
            padding: 30px;
            height: 91%;
            text-align: center;
            background-color: #fafbfc;
        }
        .cert-header {
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 26px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        .school-tagline {
            font-size: 11px;
            color: #64748b;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .cert-title {
            font-size: 32px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 25px 0 10px 0;
        }
        .cert-subtitle {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .recipient-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a8a;
            border-bottom: 2px solid #2563eb;
            display: inline-block;
            padding: 0 40px 6px 40px;
            margin: 20px 0 10px 0;
            font-family: 'Times New Roman', Times, serif;
        }
        .cert-body {
            font-size: 14px;
            color: #334155;
            line-height: 1.6;
            margin: 10px auto;
            max-width: 650px;
        }
        .course-highlight {
            font-weight: bold;
            color: #0f172a;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: bottom;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #475569;
            width: 180px;
            margin: 0 auto;
            padding-top: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #334155;
        }
        .signature-title {
            font-size: 10px;
            color: #64748b;
        }
        .meta-info {
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }
        .qr-section {
            text-align: center;
        }
        .qr-section img {
            width: 80px;
            height: 80px;
        }
        .grade-badge {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            font-weight: bold;
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="cert-border">
        <div class="cert-inner-border">
            <div class="cert-header">
                <div class="school-name">DrivePulse Driving Academy</div>
                <div class="school-tagline">Government Certified Driver Training & Road Safety Institution</div>
            </div>

            <div class="cert-title">Certificate of Completion</div>
            <div class="cert-subtitle">This is proudly presented to</div>

            <div class="recipient-name">{{ $certificate->student->user->name ?? 'Student Name' }}</div>

            <div class="cert-body">
                for successfully completing the rigorous theoretical and practical curriculum for
                <br>
                <span class="course-highlight">{{ $certificate->course_name }}</span>
                demonstrating high proficiency in vehicle handling, traffic regulations, and defensive driving techniques.
            </div>

            <table class="footer-table">
                <tr>
                    <td style="width: 30%;">
                        <div class="signature-line">Chief Instructor</div>
                        <div class="signature-title">DrivePulse Academy</div>
                    </td>
                    <td style="width: 40%;">
                        <div class="qr-section">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($verifyUrl) }}" alt="QR Code">
                            <div class="meta-info" style="margin-top: 5px;">
                                <strong>Cert No:</strong> {{ $certificate->certificate_number }}<br>
                                <strong>Grade:</strong> <span class="grade-badge">{{ $certificate->grade }}</span> | <strong>Issued:</strong> {{ $certificate->issue_date->format('d M Y') }}
                            </div>
                        </div>
                    </td>
                    <td style="width: 30%;">
                        <div class="signature-line">Director of Operations</div>
                        <div class="signature-title">DrivePulse Academy</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
