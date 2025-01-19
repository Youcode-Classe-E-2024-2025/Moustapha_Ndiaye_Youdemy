// Register validation
const form = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
const emailError = document.getElementById('emailError');
const passwordInput = document.getElementById('password');
const passwordError = document.getElementById('passwordError');

// Floating label effect
const inputs = [emailInput, passwordInput];
inputs.forEach(input => {
    input.addEventListener('input', () => {
        const label = input.nextElementSibling; // The label is the next sibling
        if (input.value.trim() !== "") {
            label.classList.add('floating');
        } else {
            label.classList.remove('floating');
        }
    });
});



// Real-time validation for Email
emailInput.addEventListener('input', () => {
    if (emailInput.validity.valid) {
        emailError.classList.add('hidden');
    } else {
        emailError.classList.remove('hidden');
    }
});

// Real-time validation for Password
passwordInput.addEventListener('input', () => {
    if (passwordInput.validity.valid) {
        passwordError.classList.add('hidden');
    } else {
        passwordError.classList.remove('hidden');
    }
});

// Prevent form submission if any field is invalid
form.addEventListener('submit', (event) => {
    let isFormValid = true;

    if (!nameInput.validity.valid) {
        nameError.classList.remove('hidden');
        isFormValid = false;
    }

    if (!emailInput.validity.valid) {
        emailError.classList.remove('hidden');
        isFormValid = false;
    }

    if (!passwordInput.validity.valid) {
        passwordError.classList.remove('hidden');
        isFormValid = false;
    }

    if (confirmPasswordInput.value !== passwordInput.value) {
        confirmPasswordError.classList.remove('hidden');
        isFormValid = false;
    }

    if (!roleInput.validity.valid) {
        roleError.classList.remove('hidden');
        isFormValid = false;
    }

    if (!isFormValid) {
        event.preventDefault(); // Prevent form submission
    } else {
        // Reset form after successful submission
        form.reset();
        inputs.forEach(input => {
            const label = input.nextElementSibling;
            label.classList.remove('floating'); // Reset floating labels
        });
    }
});



document.querySelectorAll('.form-input').forEach(input => {
input.classList.add(
'peer', 'block', 'w-full', 'rounded', 'border', 'bg-white', 'px-3', 'py-2', 'leading-[2.15]', 'transition-all', 'duration-200', 'ease-linear', 'placeholder-transparent', 'focus:placeholder-transparent', 'focus:outline-none', 'focus:ring-2', 'focus:ring-red-500'
);
});

document.querySelectorAll('.form-label').forEach(label => {
label.classList.add(
    'absolute', 'left-3', 'top-0', 'mb-0', 'max-w-[90%]', 'origin-[0_0]', 'truncate', 'pt-[0.37rem]', 'leading-[2.15]', 'text-neutral-500', 'transition-all', 'duration-200', 'ease-out', 'peer-focus:transform', 'peer-focus:-translate-y-4', 'peer-focus:scale-75', 'peer-focus:text-red-500', 'peer-focus:bg-white'
);
});






