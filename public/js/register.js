const form = document.getElementById('registrationForm');
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirmPassword');
const roleInput = document.getElementById('role');

const nameError = document.getElementById('nameError');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');
const confirmPasswordError = document.getElementById('confirmPasswordError');
const roleError = document.getElementById('roleError');

const inputs = [nameInput, emailInput, passwordInput, confirmPasswordInput, roleInput];

// Fonction pour valider un champ en temps réel
function validateInput(input, errorElement) {
    if (input === nameInput) {
        if (!input.validity.valid) {
            if (input.validity.valueMissing) {
                errorElement.textContent = "Full Name is required.";
            } else if (input.validity.patternMismatch) {
                errorElement.textContent = "Please enter a valid name (letters and spaces only, minimum 2 characters).";
            }
            errorElement.classList.remove('hidden');
            return false; // Champ invalide
        } else {
            errorElement.classList.add('hidden');
            return true; // Champ valide
        }
    } else if (input === emailInput) {
        if (!input.validity.valid) {
            if (input.validity.valueMissing) {
                errorElement.textContent = "Email is required.";
            } else if (input.validity.typeMismatch) {
                errorElement.textContent = "Please enter a valid email address.";
            }
            errorElement.classList.remove('hidden');
            return false; // Champ invalide
        } else {
            errorElement.classList.add('hidden');
            return true; // Champ valide
        }
    } else if (input === passwordInput) {
        if (!input.validity.valid) {
            if (input.validity.valueMissing) {
                errorElement.textContent = "Password is required.";
            } else if (input.validity.tooShort) {
                errorElement.textContent = "Password must be at least 8 characters long.";
            }
            errorElement.classList.remove('hidden');
            return false; // Champ invalide
        } else {
            errorElement.classList.add('hidden');
            return true; // Champ valide
        }
    } else if (input === confirmPasswordInput) {
        if (input.value !== passwordInput.value) {
            errorElement.textContent = "Passwords do not match.";
            errorElement.classList.remove('hidden');
            return false; // Champ invalide
        } else {
            errorElement.classList.add('hidden');
            return true; // Champ valide
        }
    } else if (input === roleInput) {
        if (!input.validity.valid) {
            errorElement.textContent = "Please select a role.";
            errorElement.classList.remove('hidden');
            return false; // Champ invalide
        } else {
            errorElement.classList.add('hidden');
            return true; // Champ valide
        }
    }
}

// Validation en temps réel
inputs.forEach(input => {
    input.addEventListener('input', () => {
        const errorElement = input.nextElementSibling.nextElementSibling; // Assumes error message follows the label
        validateInput(input, errorElement);
    });
});

// Validation lors de la soumission du formulaire
form.addEventListener('submit', (event) => {
    let isFormValid = true;

    inputs.forEach(input => {
        const errorElement = input.nextElementSibling.nextElementSibling;
        if (!validateInput(input, errorElement)) {
            isFormValid = false; // Si un champ est invalide, le formulaire est invalide
        }
    });

    // Empêcher la soumission du formulaire si la validation échoue
    if (!isFormValid) {
        event.preventDefault(); // Empêche la soumission du formulaire
    }
    // Si le formulaire est valide, il sera soumis normalement
});

// Add Tailwind CSS classes to form inputs and labels
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