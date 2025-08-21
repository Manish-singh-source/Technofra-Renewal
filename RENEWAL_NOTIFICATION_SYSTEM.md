# Laravel Renewal Notification System

This document explains the complete implementation of the renewal notification system that shows alerts when services are about to expire or have expired.

## 🔔 **Features Implemented**

### 1. Dynamic Notification Bell
- **Location**: Top navigation bar (header)
- **Function**: Shows count of renewal alerts with color-coded urgency
- **Visual Indicators**: 
  - Red badge + pulsing animation for critical alerts (expired/expiring today)
  - Yellow badge for warning alerts (expiring soon)
  - No badge when all services are up to date

### 2. Smart Notification Dropdown
- **Service Information**: Shows service name, client, and urgency
- **Color-Coded Icons**: Different colors for different urgency levels
- **Direct Actions**: Click to go to send-mail page for that service
- **Real-time Updates**: Refreshes every 5 minutes automatically

### 3. Intelligent Alert Categorization
- **Expired Services**: Red alerts for services that have already expired
- **Expiring Today**: Red alerts for services expiring today
- **Expiring Tomorrow**: Yellow alerts for services expiring tomorrow
- **Expiring This Week**: Yellow alerts for services expiring within 7 days
- **Expiring This Month**: Blue alerts for services expiring within 30 days

## 🔧 **Backend Implementation**

### 1. NotificationService (`app/Services/NotificationService.php`)

**Core Methods:**

#### getRenewalNotifications()
```php
public static function getRenewalNotifications()
{
    $notifications = [];
    $today = Carbon::today();
    
    // Get services expiring within 30 days
    $services = Service::with(['client', 'vendor'])
        ->where('end_date', '<=', $today->copy()->addDays(30))
        ->orderBy('end_date', 'asc')
        ->get();

    // Process each service and create notification objects
    foreach ($services as $service) {
        $daysLeft = $today->diffInDays($service->end_date, false);
        
        // Create notification based on urgency
        if ($daysLeft < 0) {
            // Expired services - HIGH urgency
        } elseif ($daysLeft == 0) {
            // Expiring today - HIGH urgency
        } elseif ($daysLeft == 1) {
            // Expiring tomorrow - HIGH urgency
        } elseif ($daysLeft <= 7) {
            // Expiring this week - MEDIUM urgency
        } elseif ($daysLeft <= 30) {
            // Expiring this month - LOW urgency
        }
    }
    
    return $notifications;
}
```

#### getNotificationCounts()
```php
public static function getNotificationCounts()
{
    $notifications = self::getRenewalNotifications();
    
    return [
        'total' => count($notifications),
        'high' => count(high urgency notifications),
        'medium' => count(medium urgency notifications),
        'low' => count(low urgency notifications),
        'expired' => count(expired services),
        'expiring_today' => count(services expiring today),
        'expiring_week' => count(services expiring this week)
    ];
}
```

#### getUrgentNotifications($limit = 10)
```php
public static function getUrgentNotifications($limit = 10)
{
    $notifications = self::getRenewalNotifications();
    
    // Sort by urgency (high first) and then by days left
    usort($notifications, function ($a, $b) {
        $urgencyOrder = ['high' => 0, 'medium' => 1, 'low' => 2];
        
        if ($urgencyOrder[$a['urgency']] !== $urgencyOrder[$b['urgency']]) {
            return $urgencyOrder[$a['urgency']] - $urgencyOrder[$b['urgency']];
        }
        
        return $a['days_left'] - $b['days_left'];
    });

    return array_slice($notifications, 0, $limit);
}
```

### 2. NotificationComposer (`app/Http/View/Composers/NotificationComposer.php`)

**View Composer for sharing notification data:**

```php
public function compose(View $view)
{
    // Get renewal notifications
    $renewalNotifications = NotificationService::getUrgentNotifications(10);
    $notificationCounts = NotificationService::getNotificationCounts();
    $hasCriticalNotifications = NotificationService::hasCriticalNotifications();

    $view->with([
        'renewalNotifications' => $renewalNotifications,
        'notificationCounts' => $notificationCounts,
        'hasCriticalNotifications' => $hasCriticalNotifications
    ]);
}
```

### 3. NotificationController (`app/Http/Controllers/NotificationController.php`)

**API Endpoints for AJAX requests:**

- `GET /notifications/renewal` - Get all renewal notifications
- `GET /notifications/counts` - Get notification counts only
- `GET /notifications/urgent` - Get urgent notifications only
- `GET /notifications/summary` - Get notification summary for dashboard
- `POST /notifications/mark-read` - Mark notification as read (future feature)

## 🎨 **Frontend Implementation**

### 1. Master Layout Integration (`resources/views/layout/master.blade.php`)

**Dynamic Notification Bell:**
```html
<li class="nav-item dropdown dropdown-large">
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown">
        @if(isset($notificationCounts) && $notificationCounts['total'] > 0)
            <span class="alert-count {{ $hasCriticalNotifications ? 'bg-danger' : 'bg-warning' }}">
                {{ $notificationCounts['total'] }}
            </span>
        @endif
        <i class='bx bx-bell {{ $hasCriticalNotifications ? 'text-danger' : '' }}'></i>
    </a>
    
    <!-- Dropdown content with notifications -->
</li>
```

**Notification Dropdown Content:**
```html
<div class="header-notifications-list">
    @if(isset($renewalNotifications) && count($renewalNotifications) > 0)
        @foreach($renewalNotifications as $notification)
            <a class="dropdown-item" href="{{ $notification['action_url'] }}">
                <div class="d-flex align-items-center">
                    <div class="notify {{ $notification['bg_color'] }} rounded-circle">
                        <i class='bx {{ $notification['icon'] }} {{ $notification['color'] }}'></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="msg-name">{{ $notification['title'] }}
                            <span class="msg-time float-end">{{ $notification['time_ago'] }}</span>
                        </h6>
                        <p class="msg-info">{{ $notification['message'] }}</p>
                        <small class="text-muted">Client: {{ $notification['client'] }}</small>
                    </div>
                </div>
            </a>
        @endforeach
    @else
        <!-- No notifications state -->
    @endif
</div>
```

### 2. CSS Styling

**Custom notification styles:**
```css
.alert-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border: 2px solid white;
}

.alert-count.bg-warning {
    background: #ffc107 !important;
    color: #000 !important;
}

/* Pulsing animation for critical notifications */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.nav-link .bx-bell.text-danger {
    animation: pulse 2s infinite;
}
```

### 3. JavaScript Auto-Refresh

**Real-time notification updates:**
```javascript
function refreshNotifications() {
    fetch('/notifications/counts')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update notification count badge
                const alertCount = document.querySelector('.alert-count');
                const bellIcon = document.querySelector('.bx-bell');
                
                if (data.counts.total > 0) {
                    if (alertCount) {
                        alertCount.textContent = data.counts.total;
                        alertCount.className = data.has_critical ? 'alert-count bg-danger' : 'alert-count bg-warning';
                        alertCount.style.display = 'flex';
                    }
                    
                    if (bellIcon && data.has_critical) {
                        bellIcon.classList.add('text-danger');
                    }
                } else {
                    if (alertCount) {
                        alertCount.style.display = 'none';
                    }
                }
            }
        });
}

// Auto-refresh every 5 minutes
setInterval(refreshNotifications, 300000);
```

## 🎯 **Notification Types & Visual Indicators**

### 1. Expired Services
- **Icon**: `bx-error-circle`
- **Color**: Red (`text-danger`)
- **Background**: Light red (`bg-light-danger`)
- **Urgency**: HIGH
- **Message**: "Service expired X days ago"

### 2. Expiring Today
- **Icon**: `bx-time-five`
- **Color**: Red (`text-danger`)
- **Background**: Light red (`bg-light-danger`)
- **Urgency**: HIGH
- **Message**: "Service expires today"

### 3. Expiring Tomorrow
- **Icon**: `bx-alarm`
- **Color**: Yellow (`text-warning`)
- **Background**: Light yellow (`bg-light-warning`)
- **Urgency**: HIGH
- **Message**: "Service expires tomorrow"

### 4. Expiring This Week
- **Icon**: `bx-calendar-exclamation`
- **Color**: Yellow (`text-warning`)
- **Background**: Light yellow (`bg-light-warning`)
- **Urgency**: MEDIUM
- **Message**: "Service expires in X days"

### 5. Expiring This Month
- **Icon**: `bx-calendar`
- **Color**: Blue (`text-info`)
- **Background**: Light blue (`bg-light-info`)
- **Urgency**: LOW
- **Message**: "Service expires in X days"

## 🔄 **System Integration**

### 1. AppServiceProvider Registration
```php
public function boot()
{
    // Share notification data with the master layout
    View::composer('layout.master', NotificationComposer::class);
}
```

### 2. Route Registration
```php
// Notification routes
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/renewal', [NotificationController::class, 'getRenewalNotifications'])->name('renewal');
    Route::get('/counts', [NotificationController::class, 'getNotificationCounts'])->name('counts');
    Route::get('/urgent', [NotificationController::class, 'getUrgentNotifications'])->name('urgent');
    Route::get('/summary', [NotificationController::class, 'getNotificationSummary'])->name('summary');
    Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
});
```

## ✅ **Notification System Complete**

The renewal notification system is now fully implemented with:

- ✅ **Dynamic Bell Icon**: Shows count and urgency with visual indicators
- ✅ **Smart Categorization**: 5 levels of urgency based on expiry timeline
- ✅ **Real-time Updates**: Auto-refresh every 5 minutes
- ✅ **Direct Actions**: Click notifications to send renewal emails
- ✅ **Professional UI**: Color-coded icons and smooth animations
- ✅ **API Endpoints**: RESTful API for notification data
- ✅ **Performance Optimized**: Efficient database queries and caching-ready

Your renewal notification system is ready for production use!
