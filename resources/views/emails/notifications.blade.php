<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Renewal Notifications</title>
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
        .service-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #dc3545;
        }
        .service-item.overdue {
            border-left-color: #dc3545;
        }
        .service-item.expiring-soon {
            border-left-color: #ffc107;
        }
        .service-name {
            font-weight: bold;
            font-size: 16px;
            color: #007bff;
            margin-bottom: 8px;
        }
        .service-details {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        .expiry-info {
            font-weight: bold;
            margin-top: 8px;
        }
        .overdue .expiry-info {
            color: #dc3545;
        }
        .expiring-soon .expiry-info {
            color: #ffc107;
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
            margin: 10px 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .summary {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin: 20px 0 10px 0;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">Technofra Renewal Master</div>
            <p>Daily Service Renewal Notifications</p>
        </div>

        <!-- Summary -->
        <div class="summary">
            <h3>Action Required</h3>
            <p>You have {{ count($criticalServices) + count($criticalVendorServices) }} service(s) that require immediate attention.</p>
        </div>

        <!-- Client Services -->
        @if($criticalServices && $criticalServices->count() > 0)
        <div class="section-title">Client Services</div>
        @foreach($criticalServices as $service)
            @php
                $daysLeft = \Carbon\Carbon::today()->diffInDays($service->end_date, false);
                $isOverdue = $daysLeft < 0;
                $isExpiringSoon = $daysLeft >= 0 && $daysLeft <= 5;
            @endphp
            <div class="service-item {{ $isOverdue ? 'overdue' : 'expiring-soon' }}">
                <div class="service-name">{{ $service->service_name }}</div>
                <div class="service-details">
                    <strong>Client:</strong> {{ $service->client->cname ?? 'N/A' }}<br>
                    <strong>Vendor:</strong> {{ $service->vendor->name ?? 'N/A' }}<br>
                    <strong>Amount:</strong> ₹{{ number_format($service->amount, 2) }}
                </div>
                <div class="expiry-info">
                    @if($isOverdue)
                        Expired {{ abs($daysLeft) }} days ago ({{ $service->end_date->format('d M Y') }})
                    @else
                        Expires in {{ $daysLeft }} days ({{ $service->end_date->format('d M Y') }})
                    @endif
                </div>
            </div>
        @endforeach
        @endif

        <!-- Vendor Services -->
        @if($criticalVendorServices && $criticalVendorServices->count() > 0)
        <div class="section-title">Vendor Services</div>
        @foreach($criticalVendorServices as $service)
            @php
                $daysLeft = \Carbon\Carbon::today()->diffInDays($service->end_date, false);
                $isOverdue = $daysLeft < 0;
                $isExpiringSoon = $daysLeft >= 0 && $daysLeft <= 5;
            @endphp
            <div class="service-item {{ $isOverdue ? 'overdue' : 'expiring-soon' }}">
                <div class="service-name">{{ $service->service_name }}</div>
                <div class="service-details">
                    <strong>Vendor:</strong> {{ $service->vendor->name ?? 'N/A' }}<br>
                    <strong>Plan Type:</strong> {{ $service->plan_type ?? 'N/A' }}
                </div>
                <div class="expiry-info">
                    @if($isOverdue)
                        Expired {{ abs($daysLeft) }} days ago ({{ $service->end_date->format('d M Y') }})
                    @else
                        Expires in {{ $daysLeft }} days ({{ $service->end_date->format('d M Y') }})
                    @endif
                </div>
            </div>
        @endforeach
        @endif

        <!-- Call to Action -->
        <div style="text-align: center; margin: 30px 0;">
            <p>Please review these services and take necessary action to avoid service interruptions.</p>
            <a href="{{ url('/dashboard') }}" class="btn" style="color: #ffffff;">View Dashboard</a>
            <a href="{{ url('/vendor-services') }}" class="btn" style="color: #ffffff;">View Vendor Services</a>
            <a href="mailto:support@technofra.com" class="btn" style="color: #ffffff;">Contact Support</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Technofra Renewal Master</strong></p>
            <p>This is an automated daily notification email.</p>
            <p>If you have any questions, please contact our support team.</p>
            <p style="margin-top: 20px;">
                <small>© {{ date('Y') }} Technofra. All rights reserved.</small>
            </p>
        </div>
    </div>
</body>
</html>