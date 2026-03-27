<!DOCTYPE html>
<html lang="en" 
    x-data="{ 
        theme: localStorage.getItem('theme') || 'light',
        sidebarOpen: false 
    }" 
    :class="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SuperAdmin | CodifyPOS Pusat</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/login.png') }}">

    @livewireStyles
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { 
                extend: { 
                    fontFamily: { 'sans': ['"Space Grotesk"', 'sans-serif'] },
                    colors: { 'admin-accent': '#000000' }
                } 
            }
        }
    </script>
</head>
<body class="bg-[#fcfcfc] dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased overflow-hidden">
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('components.admin.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            @include('components.admin.header')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 lg:p-10">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>