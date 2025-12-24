<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian RS</title>
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <i data-feather="activity" class="text-blue-600 mr-2"></i>
                        <span class="font-bold text-xl text-gray-900">RS Sehat Selalu</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('pendaftaran.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pendaftaran.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Pendaftaran
                    </a>
                    <a href="{{ route('queue.show') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('queue.show') ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                        Antrian
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Rumah Sakit Sehat Selalu. Built with Laravel 12 & Tailwind v3.
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>
</html>
