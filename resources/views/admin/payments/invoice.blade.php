<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $payment->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }
        .header-table, .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            padding: 5px;
            vertical-align: top;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
        }
        .items-table th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
            padding: 10px;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .bg-success {
            background-color: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td class="title">DrivePulse</td>
                <td class="text-right">
                    <strong>Invoice #:</strong> {{ $payment->invoice_number }}<br>
                    <strong>Date:</strong> {{ $payment->paid_at ? $payment->paid_at->format('d M Y') : date('d M Y') }}<br>
                    <strong>Status:</strong> <span class="badge bg-success">{{ strtoupper($payment->status) }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 20px;">
                    <strong>DrivePulse Driving Academy</strong><br>
                    123 Innovation Way, Tech City<br>
                    contact@drivepulse.com
                </td>
                <td class="text-right" style="padding-top: 20px;">
                    <strong>Billed To:</strong><br>
                    {{ $payment->student->user->name ?? 'Student' }}<br>
                    Admission No: {{ $payment->student->admission_number ?? 'N/A' }}<br>
                    {{ $payment->student->user->email ?? '' }}
                </td>
            </tr>
        </table>

        <div style="margin-top: 40px;">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Payment Method</th>
                        <th>Transaction Ref</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Driving Course Tuition / Lesson Fee</td>
                        <td>{{ strtoupper($payment->payment_method) }}</td>
                        <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                        <td class="text-right">₹{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <h3>Total Paid: ₹{{ number_format($payment->amount, 2) }}</h3>
        </div>

        @if($payment->notes)
        <div style="margin-top: 30px; background: #f8fafc; padding: 10px; border-radius: 4px;">
            <strong>Notes:</strong> {{ $payment->notes }}
        </div>
        @endif

        <div style="margin-top: 50px; text-align: center; color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            Thank you for choosing DrivePulse. Drive safely!
        </div>
    </div>
</body>
</html>
