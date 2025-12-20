<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Planet Fashion - Authentication')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
</head>

<body class="min-h-full bg-gray-50 dark:bg-gray-900 transition-colors">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 sm:p-8">

            <div class="flex justify-center mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-9 h-9" viewBox="0 0 40 40" fill="none">
                        <path d="M8 20C8 20 12 12 20 12C28 12 32 20 32 20C32 20 28 28 20 28C12 28 8 20 8 20Z" fill="#6366f1"/>
                        <path d="M12 20C12 20 14 16 18 16C22 16 24 20 24 20C24 20 22 24 18 24C14 24 12 20 12 20Z" fill="#818cf8"/>
                    </svg>
                    <span class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                        Planet Fashion
                    </span>
                </div>
            </div>

            {{ $slot }}

        </div>
    </div>

    <script>
        function togglePassword(id, iconId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = "🙈";
            } else {
                input.type = "password";
                icon.innerHTML = "👁️";
            }
        }
    </script>
</body>
</html>
