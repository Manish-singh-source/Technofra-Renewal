<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Renewal Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .service-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .service-details h3 {
            margin-top: 0;
            color: #007bff;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #6c757d;
        }
        .message-content {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .urgent {
            color: #dc3545;
            font-weight: bold;
        }
        .warning {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">Technofra Renewal Master</div>
            <p>Service Renewal Notification</p>
        </div>

        <!-- Service Details -->
        <div class="service-details">
            <h3>Service Information</h3>
            
            <div class="detail-row">
                <span class="detail-label">Service ID:</span>
                <span class="detail-value">#{{ $service->id }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Service Name:</span>
                <span class="detail-value">{{ $service->service_name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Client Name:</span>
                <span class="detail-value">{{ $service->client->cname ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Vendor:</span>
                <span class="detail-value">{{ $service->vendor->name ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Start Date:</span>
                <span class="detail-value">{{ $service->start_date->format('d M Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Expiry Date:</span>
                <span class="detail-value 
                    @php
                        $daysLeft = \Carbon\Carbon::today()->diffInDays($service->end_date, false);
                        echo $daysLeft <= 1 ? 'urgent' : ($daysLeft <= 7 ? 'warning' : '');
                    @endphp
                ">
                    {{ $service->end_date->format('d M Y') }}
                    @php
                        if ($daysLeft < 0) {
                            echo ' (Expired ' . abs($daysLeft) . ' days ago)';
                        } elseif ($daysLeft == 0) {
                            echo ' (Expires Today)';
                        } elseif ($daysLeft == 1) {
                            echo ' (Expires Tomorrow)';
                        } else {
                            echo ' (' . $daysLeft . ' days remaining)';
                        }
                    @endphp
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Amount:</span>
                <span class="detail-value">₹{{ number_format($service->amount, 2) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">{{ ucfirst($service->status) }}</span>
            </div>
        </div>

        <!-- Custom Message -->
        <div class="message-content">
            <h3>Message:</h3>
            {!! $emailMessage !!}
        </div>

        <!-- Call to Action -->
        <div style="text-align: center; margin: 30px 0;">
            <p>Please contact us to renew your service or if you have any questions.</p>
            <a href="mailto:support@technofra.com" class="btn">Contact Support</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Technofra Renewal Master</strong></p>
            <p>This is an automated renewal reminder email.</p>
            <p>If you have any questions, please contact our support team.</p>
            <p style="margin-top: 20px;">
                <small>© {{ date('Y') }} Technofra. All rights reserved.</small>
            </p>
        </div>
    </div>
</body>
</html>
