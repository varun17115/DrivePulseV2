<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $payment->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; }
        .header { border-bottom: 2px solid #0056b3; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #0056b3; }
        .receipt-title { font-size: 28px; text-transform: uppercase; color: #666; text-align: right; margin-top: -30px; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { width: 50%; vertical-align: top; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .info-box strong { display: block; margin-bottom: 5px; color: #555; }
        .line-items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .line-items th { background: #0056b3; color: white; padding: 10px; text-align: left; }
        .line-items td { padding: 10px; border-bottom: 1px solid #ddd; }
        .totals { width: 100%; border-collapse: collapse; }
        .totals td { padding: 10px; text-align: right; }
        .totals .grand-total { font-size: 18px; font-weight: bold; color: #0056b3; border-top: 2px solid #0056b3; }
        .footer { margin-top: 50px; text-align: center; color: #888; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">DrivePulse</div>
        <div class="receipt-title">Payment Receipt</div>
    </div>

    <table class="details-table">
        <tr>
            <td style="padding-right: 20px;">
                <div class="info-box">
                    <strong>Billed To:</strong>
                    {{ $student->user->name }}<br>
                    ID: {{ $student->admission_number }}<br>
                    Email: {{ $student->user->email }}<br>
                    Phone: {{ $student->user->phone ?? 'N/A' }}
                </div>
            </td>
            <td style="padding-left: 20px;">
                <div class="info-box">
                    <strong>Receipt Details:</strong>
                    Invoice #: {{ $payment->invoice_number }}<br>
                    Date: {{ $payment->paid_at ? $payment->paid_at->format('d M, Y h:i A') : $payment->created_at->format('d M, Y') }}<br>
                    Method: {{ ucfirst($payment->payment_method) }}<br>
                    Ref/Txn ID: {{ $payment->transaction_id ?? 'N/A' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="line-items">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Driving Course Fee Installment / Payment<br><small style="color: #666;">{{ $payment->notes ?? 'Standard fee payment' }}</small></td>
                <td style="text-align: right;">₹{{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td width="70%">Subtotal:</td>
            <td width="30%">₹{{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td width="70%">Tax (0%):</td>
            <td width="30%">₹0.00</td>
        </tr>
        <tr>
            <td class="grand-total">Total Paid:</td>
            <td class="grand-total">₹{{ number_format($payment->amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        Thank you for choosing DrivePulse.<br>
        This is a computer generated receipt and requires no physical signature.
    </div>
</body>
</html>
