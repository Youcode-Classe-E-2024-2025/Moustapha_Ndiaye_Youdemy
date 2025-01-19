<?php
 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tailwind.css">
    <title>Login</title>
</head>
<body class="bg-gray-200 min-h-screen flex items-center justify-center p-6">
    
<section class="p-4 md:p-8 lg:p-12 min-h-screen flex items-center justify-center">
    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-lg overflow-hidden max-w-4xl w-full space-x-10">
         <!-- Image -->
         <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000">
                <img 
                    src="/images/Illustration2.png" 
                    alt="cover" 
                    class="h-full "
                >
            </div>
        <!-- Form Section -->
        <div class="md:w-1/2 p-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-800">Login</h2>
                <p class="mt-2 text-gray-600">Login to access your account</p>
            </div>


           <!-- Register Form -->
<form id="loginForm" action="registerView" method="POST">

    <!-- Email -->
    <div class="relative mb-6">
        <input
            type="email"
            name="email"
            id="email"
            class="form-input peer"
            placeholder=" "
            required />
        <label for="email" class="form-label">Email</label>
        <span id="emailError" class="text-sm text-red-500 hidden">Please enter a valid email address.</span>
    </div>

    <!-- Password -->
    <div class="relative mb-6">
        <input
            name="passWord"
            type="password"
            id="password"
            class="form-input peer"
            placeholder=" "
            required
            minlength="8" />
        <label for="password" class="form-label">Password</label>
        <span id="passwordError" class="text-sm text-red-500 hidden">Password must be at least 8 characters long.</span>
    </div>

    <!-- Submit Button -->
    <div class="text-center lg:text-left">
        <button
            type="submit"
            class="inline-block w-full rounded bg-red-500 px-7 pb-2 pt-3 text-sm font-medium uppercase leading-normal text-white"
            data-twe-ripple-init
            data-twe-ripple-color="light">
            Register
        </button>
    </div>
</form>

            <!-- Additional Links -->
            <div class="mt-6 space-y-2">
            <p class="text-center text-sm">
            Don't have an account?
            <a href="register" class="font-medium text-red-500 hover:underline">Sign up</a>
            </p>
                <p class="text-center text-sm">
                    <a href="/" class="font-medium text-red-500 hover:text-red-600 transition-colors hover:underline">
                        Return Home
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>
     

<script src="../js/login.js"></script>

</body>
</html>