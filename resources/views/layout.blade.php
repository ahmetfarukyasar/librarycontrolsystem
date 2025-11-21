<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kütüphane Otomasyonu')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --accent-color: #8b5cf6;
            --gradient-start: #6366f1;
            --gradient-end: #8b5cf6;
            --background-color: #f5f3ff;
            --text-color: #1e1b4b;
            --nav-height: 75px;
            --card-shadow: 0 8px 32px rgba(99, 102, 241, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--background-color), #fff);
            color: var(--text-color);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .navbar {
            height: var(--nav-height);
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(99, 102, 241, 0.1);
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.75rem;
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-brand:hover {
            transform: translateY(-2px) scale(1.05);
            filter: brightness(1.2);
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .nav-link:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
            z-index: -1;
        }

        .nav-link:hover:before {
            width: 300px;
            height: 300px;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .notifications-wrapper {
            position: relative !important;
        }

        .notifications-container {
            position: absolute;
            top: calc(100% + 15px) !important;
            right: 0;
            width: 350px;
            max-height: 450px;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .notifications-container.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .notification-item {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .notification-item:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: scale(0.98);
        }

        .notification-item.unread {
            background-color: rgba(99, 102, 241, 0.1);
            font-weight: 500;
        }

        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast-notification {
            padding: 1rem 2rem;
            border-radius: 16px;
            margin-bottom: 15px;
            color: white;
            font-weight: 500;
            opacity: 0;
            transform: translateX(100%) scale(0.9);
            animation: slideIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards,
                       fadeOut 0.5s ease 3s forwards;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(20px) scale(0.9);
            }
        }
        
        .toast-success { background: linear-gradient(135deg, #34d399, #10b981); }
        .toast-error { background: linear-gradient(135deg, #fb7185, #e11d48); }
        .toast-info { background: linear-gradient(135deg, #60a5fa, #3b82f6); }

        main {
            min-height: calc(100vh - var(--nav-height) - 80px);
        }

        footer {
            background: white;
            border-top: 1px solid rgba(99, 102, 241, 0.1);
            padding: 1.5rem 0;
        }

        .header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 4rem 3rem;
            border-radius: 24px;
            margin-bottom: 3rem;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .header:before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
            transform: rotate(-45deg);
            animation: headerGlow 15s linear infinite;
        }

        @keyframes headerGlow {
            0% { transform: rotate(-45deg) translateX(-30%); }
            100% { transform: rotate(-45deg) translateX(30%); }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }
            
            .notifications-container {
                width: 100%;
                position: fixed;
                top: var(--nav-height) !important;
                right: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>  
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">Kütüphane</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                </ul>
                <ul class="navbar-nav">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="/adminpanel">Yönetim Paneli</a>
                            </li>
                        @endif
                        
                    @endauth
                    <li class="nav-item">
                            <a class="nav-link" href="/books">Kitaplar</a>
                        </li>
                    @auth
                        <li class="nav-item notifications-wrapper dropdown">
                            <a class="nav-link position-relative" href="{{ route('notifications.markAllAsRead') }}" id="notificationBtn" role="button">
                                <i class="fas fa-bell"></i>
                                @if(auth()->user()->notifications()->where('read', false)->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->notifications()->where('read', false)->count() }}
                                    </span>
                                @endif
                            </a>
                            <div class="notifications-container">
                                @if(auth()->user()->notifications()->count() > 0)
                                    @foreach(auth()->user()->notifications()->orderBy('created_at', 'desc')->get() as $notification)
                                    <div class="notification-item {{ !$notification->read ? 'unread' : '' }}" data-notification-id="{{ $notification->id }}">
                                        {{ $notification->message }}
                                    </div>
                                    @endforeach
                                @else
                                    <div class="notification-item text-muted">
                                        Bildiriminiz bulunmamaktadır.
                                    </div>
                                @endif
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile">Profilim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Çıkış</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Giriş</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Kayıt</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <div class="toast-container">
            @if(session('success'))
                <div class="toast-notification toast-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="toast-notification toast-error">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="toast-notification toast-info">
                    {{ session('info') }}
                </div>
            @endif
        </div>
        
        @yield('content')
    </main>

    <footer class="bg-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Kütüphane Otomasyonu. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationsContainer = notificationBtn.nextElementSibling;

            // CSRF Token'ı ayarla
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if(notificationBtn && notificationsContainer) {
                // Bildirim tıklama olayını ekle
                notificationsContainer.addEventListener('click', function(e) {
                    const notificationItem = e.target.closest('.notification-item');
                    if (notificationItem && notificationItem.dataset.notificationId) {
                        fetch(`/notifications/${notificationItem.dataset.notificationId}/mark-as-read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                notificationItem.classList.remove('unread');
                                // Bildirim sayısını güncelle
                                const badge = notificationBtn.querySelector('.badge');
                                if (badge) {
                                    const currentCount = parseInt(badge.textContent);
                                    if (currentCount > 1) {
                                        badge.textContent = currentCount - 1;
                                    } else {
                                        badge.remove();
                                    }
                                }
                            }
                        });
                    }
                });

                notificationBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Okunmamış bildirimleri okundu olarak işaretle
                    fetch('/notifications/mark-all-as-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tüm unread class'larını kaldır
                            document.querySelectorAll('.notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                            });
                            
                            // Bildirim rozetini kaldır
                            const badge = notificationBtn.querySelector('.badge');
                            if (badge) {
                                badge.remove();
                            }
                        }
                    });

                    const isVisible = notificationsContainer.classList.contains('show');
                    
                    // Tüm açık dropdownları kapat
                    document.querySelectorAll('.notifications-container.show').forEach(container => {
                        if(container !== notificationsContainer) {
                            container.classList.remove('show');
                        }
                    });
                    
                    notificationsContainer.classList.toggle('show');
                });

                // Dışarı tıklandığında kapat
                document.addEventListener('click', function(e) {
                    if (!notificationsContainer.contains(e.target) && !notificationBtn.contains(e.target)) {
                        notificationsContainer.classList.remove('show');
                    }
                });
            }

            // Toast bildirimleri için otomatik silme
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.addEventListener('animationend', function(e) {
                        if (e.animationName === 'fadeOut') {
                            toast.remove();
                        }
                    });
                }, 100);
            });
        });
    </script>
    @yield('js')
</body>
</html>