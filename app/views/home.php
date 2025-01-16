<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tailwind.css">
    <title>HomeUser</title>
</head>
<body class="bg-gray-200">
    <header>
    <nav class="p-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex text-3xl font-bold text-white bg-slate-300 rounded-sm">
                <div class="bg-red-500  px-1 rounded-md">You</div>
                <div class="">demy</div>
            </div>

            <!-- Menu Burger (visible sur mobile) -->
            <button id="burger-btn" class="md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Menu Desktop -->
            <div class="hidden md:flex space-x-4 text-2xl font-bold">
                <div class="hover:text-gray-700 cursor-pointer">Sign In</div>
                <div class="text-red-500 border border-red-500 px-2 rounded-md hover:bg-red-50 cursor-pointer">Sign Up</div>
            </div>

            <!-- Menu Mobile -->
            <div id="mobile-menu" class="hidden fixed top-16 left-0 right-0 bg-white p-4 shadow-sm md:hidden">
                <div class="flex flex-col space-y-4 text-xl  text-center">
                    <div class="hover:text-gray-700 cursor-pointer hover:bg-red-50 ">Sign In</div>
                    <div class="text-red-500 px-2 rounded-md hover:bg-red-50 cursor-pointer">Sign Up</div>
                </div>
            </div>
        </div>
    </nav>
    </header>
    <script src="/js/main.js"></script>
</body>
</html>