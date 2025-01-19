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
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
       <!-- handle error -->
     <?php if (!empty($errors)): ?>
            <div class="bg-red-100" role="alert">
                <ul class="list-disc list-inside">
                    <?php foreach($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>       
    <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
            <p class="mt-2 text-gray-600">Sign in to your account</p>
        </div>

                  <!-- Login Form -->
            <form action="loginView" method="POST">
              <!--Sign in section-->
              

              <div class="relative mb-6">
                <!-- Email input -->
                <input
                  name="email"
                  type="text"
                  id="login-form"
                  class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15] transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                  placeholder="Email" />
                <label
                  for="email"
                  class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                  Email
                </label>
              </div>

              <div class="relative mb-6">
                <!-- Password input -->
                <input
                  name="passWord"
                  type="password"
                  id="password"
                  class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15] transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                  placeholder="Password" />
                <label
                  for="password"
                  class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                  Password
                </label>
              </div>

              <div class="mb-6 flex items-center justify-between">
                <!-- Forgot password link -->
                <a href="#!" class="font-medium text-red-500 hover:underline">Forgot password?</a>
              </div>

              <!-- Login button -->
              <div class="text-center lg:text-left">
                <button
                  type="submit"
                  class="inline-block w-full bg-red-500 rounded px-7 pb-2 pt-3 text-sm font-medium uppercase leading-normal text-white">
                  Login
                </button>
              </div>
            </form>

        <p class="text-center text-sm">
            Don't have an account?
            <a href="register" class="font-medium text-red-500 hover:underline">Sign up</a>
        </p>
        <p class="text-center text-sm">
           
            <a href="/" class="font-medium text-red-500 hover:underline">Home</a>
        </p>
    </div>
     <!-- Register Form -->
    <script src="assets/js/main.js"></script>
</body>
</html>