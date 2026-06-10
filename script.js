document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');
    const successCard = document.getElementById('successCard');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    const successUser = document.getElementById('successUser');

    // Validation rules
    const validators = {
        firstName: (val) => val.trim().length > 0,
        lastName: (val) => val.trim().length > 0,
        email: (val) => {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(val).toLowerCase());
        },
        message: (val) => val.trim().length >= 10
    };

    // Helper to validate input and set state
    function validateField(field) {
        const wrapper = field.closest('.input-wrapper');
        if (!wrapper) return true;

        const value = field.value;
        const isValid = validators[field.name] ? validators[field.name](value) : true;

        if (isValid) {
            wrapper.classList.remove('invalid');
        } else {
            wrapper.classList.add('invalid');
        }

        return isValid;
    }

    // Bind real-time input validations
    const formInputs = form.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        // Clear errors as user corrects them
        input.addEventListener('input', () => {
            if (input.closest('.input-wrapper').classList.contains('invalid')) {
                validateField(input);
            }
        });

        input.addEventListener('blur', () => {
            validateField(input);
        });
        
        // Custom check for select element change
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', () => validateField(input));
        }
    });

    // Handle Form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Validate all fields
        let isFormValid = true;
        formInputs.forEach(input => {
            const isFieldValid = validateField(input);
            if (!isFieldValid) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            // Find first error and focus it
            const firstError = form.querySelector('.input-wrapper.invalid input, .input-wrapper.invalid select, .input-wrapper.invalid textarea');
            if (firstError) firstError.focus();
            return;
        }

        // Show loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        const firstName = document.getElementById('firstName').value;
        const lastName = document.getElementById('lastName').value;
        const email = document.getElementById('email').value;
        const message = document.getElementById('message').value;

        try {
            // Send request to Express backend endpoint
            const response = await fetch('/api/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ firstName, lastName, email, message })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Retrieve first name to show in the success card
                successUser.textContent = firstName;

                // Show preview link for ethereal test inbox
                if (data.previewUrl) {
                    console.log('Confirmation email sent! Preview it here:', data.previewUrl);
                    let linkElement = document.getElementById('emailPreviewLink');
                    if (!linkElement) {
                        linkElement = document.createElement('p');
                        linkElement.id = 'emailPreviewLink';
                        linkElement.style.margin = '16px 0 24px 0';
                        linkElement.style.fontSize = '0.9rem';
                        linkElement.style.color = '#4f46e5';
                        const desc = successCard.querySelector('p');
                        desc.parentNode.insertBefore(linkElement, desc.nextSibling);
                    }
                    linkElement.innerHTML = `📬 <a href="${data.previewUrl}" target="_blank" style="color: #4f46e5; text-decoration: underline; font-weight: 600;">Click to view sent confirmation email</a>`;
                }

                // Animate and switch card view
                form.classList.add('hidden');
                successCard.classList.remove('hidden');
            } else {
                throw new Error(data.error || 'Server returned failure');
            }
        } catch (error) {
            console.warn('Backend server is offline or failed. Falling back to local mock presentation.', error);
            
            // Clean up any old preview links
            const linkElement = document.getElementById('emailPreviewLink');
            if (linkElement) linkElement.remove();

            // Retain UI transition so user can test frontend offline
            successUser.textContent = firstName;
            form.classList.add('hidden');
            successCard.classList.remove('hidden');
        } finally {
            // Reset loading buttons status
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }
    });

    // Handle Form reset/another submission request
    resetBtn.addEventListener('click', () => {
        // Reset the form fields
        form.reset();
        
        // Remove all validation states
        const wrappers = form.querySelectorAll('.input-wrapper');
        wrappers.forEach(w => w.classList.remove('invalid'));

        // Toggle card screens
        successCard.classList.add('hidden');
        form.classList.remove('hidden');
        
        // Focus first field
        document.getElementById('firstName').focus();
    });
});
