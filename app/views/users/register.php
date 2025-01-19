<?php

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tailwind.css">
    <title>Register</title>
</head>
<body class="bg-gray-200 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
     <!-- handle error -->
     

        <?php 
            if (isset($_SESSION['success_message'])) {
                echo '<div class="bg-green-100 p-4 rounded-lg text-green-700" role="alert" aria-live="polite">' 
                    . htmlspecialchars($_SESSION['success_message']) . 
                    '</div>';
                unset($_SESSION['success_message']);  
            }
            
            if (isset($_SESSION['error_message'])) {
                echo '<div class="bg-red-100 p-4 rounded-lg text-red-700" role="alert" aria-live="polite">' 
                    . htmlspecialchars($_SESSION['error_message']) . 
                    '</div>';
                unset($_SESSION['error_message']);  
            }
            ?>

        
    <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-800">Create an Account</h2>
            <p class="mt-2 text-gray-600">Register to get started</p>
        </div>

      
        <form action="registerView" method="POST">
            <div class="relative mb-6">
                <input
                    type="text"
                    id="name"
                    name="fullName"
                    pattern="[A-Za-z\s]{2,}"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Name" />
                <label
                    for="name"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Full Name
                </label>
            </div>

            <div class="relative mb-6">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Email" />
                <label
                    for="email"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Email
                </label>
            </div>

            <div class="relative mb-6">
                <input
                    name="passWord"
                    type="password"
                    id="password"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Password" />
                <label
                    for="password"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Password
                </label>
            </div>

            <div class="relative mb-6">
                <input
                    name="confirmPassword"
                    type="password"
                    id="confirmPassword"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Confirm Password" />
                <label
                    for="confirmPassword"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Confirm Password
                </label>
            </div>

            <div class="text-center lg:text-left">
                <button
                    type="submit"
                    class="inline-block w-full rounded bg-red-500 px-7 pb-2 pt-3 text-sm font-medium uppercase leading-normal text-white "
                    data-twe-ripple-init
                    data-twe-ripple-color="light">
                    Register
                </button>
            </div>
        </form>

        <p class="text-center text-sm">
            Already have an account?
            <a href="login" class="font-medium text-red-500 hover:underline">Sign in</a>
        </p>
        <p class="text-center text-sm">
           
            <a href="/" class="font-medium text-red-500 hover:underline">Home</a>
        </p>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>