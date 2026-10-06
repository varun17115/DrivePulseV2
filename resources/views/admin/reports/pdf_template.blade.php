<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #1e3a8a;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 11px;
        }
        .meta {
            margin-bottom: 15px;
            font-size: 11px;
            color: #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            text-align: left;
            padding: 8px 6px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 7px 6px;
            border: 1px solid #e5e7eb;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .badge-pass, .badge-active, .badge-completed, .badge-present {
            color: #059669;
            font-weight: bold;
        }
        .badge-fail, .badge-inactive, .badge-absent, .badge-cancelled {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DrivePulse Driving Academy</h1>
        <p>{{ $title }} | Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="meta">
        <strong>Report Scope:</strong> {{ $subtitle ?? 'All Records' }} &nbsp;|&nbsp;
        <strong>Total Records:</strong> {{ count($data) }}
    </div>

    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" style="text-align: center; color: #9ca3af; padding: 20px;">
                        No records found for this report.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        DrivePulse Management System &bull; Confidential
    </div>
</body>
</html>
