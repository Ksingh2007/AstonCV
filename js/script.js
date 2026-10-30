// This runs as soon as the page loads
// Hides the status message after 3 seconds
window.onload = function() {
    // Handle the PHP "Success/Duplicate" message
    const statusMsg = document.getElementById('status-msg');
    if (statusMsg) {
        setTimeout(() => {
            statusMsg.style.display = 'none';
        }, 3000);
    }
};

function validateSignup() {
    const pass1 = document.getElementById('pass1').value;
    const pass2 = document.getElementById('pass2').value;
    const email = document.getElementById('email').value;
    const errorMsg = document.getElementById('error-message');
    // Regular Expression: 
    // ^(?=.*[a-z]) -> at least one lowercase
    // (?=.*[A-Z])  -> at least one uppercase
    // (?=.*\d)     -> at least one number
    // (?=.*[!@#$%^&*]) -> at least one special char
    // [A-Za-z\d!@#$%^&*]{8,} -> total length at least 8
    // (?=.*[^A-Za-z0-9]) -> This now counts dots, spaces, etc. as special chars
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

    if (!validateEmail(email)) {
        showTemporaryError("Please enter a valid email address.");
        return false;
    }

    if (!passwordRegex.test(pass1)) {
        showTemporaryError("Password must be 8+ chars with Uppercase, Lowercase, Number, and Special Char (!@#$%^&*)");
        return false;
    }

    if (pass1 !== pass2) {
        showTemporaryError("Passwords do not match!");
        return false;
    }

    return true;
}

// function to show JS errors on a timer too
function showTemporaryError(text) {
    const errorMsg = document.getElementById('error-message');
    errorMsg.innerText = text;
    errorMsg.style.display = 'block';
    
    setTimeout(() => {
        errorMsg.style.display = 'none';
    }, 3000);
}


// Toggle Password Visibility
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}

// Validate Email Format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}


// Confirmation Prompt for Profile Update
function confirmUpdate() {
    return confirm("Are you sure you want to save these changes to your professional profile?");
}

// Contact Me Page
// This ensures the script waits for the page to load
document.addEventListener("DOMContentLoaded", function() {
    
    const contactForm = document.getElementById('contactForm');

    // Only run if we are actually on the contact page (if the form exists)
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop the page from refreshing

            // Grab the values from the inputs
            const name = document.getElementById('contactName').value;
            const userEmail = document.getElementById('contactEmail').value;
            const subject = document.getElementById('contactSubject').value;
            const problem = document.getElementById('contactProblem').value;
            
            const adminEmail = "test@test.test";

            // Format the email body
            const emailBody = `Sender Name: ${name}\n` +
                              `Sender Email: ${userEmail}\n\n` +
                              `Message:\n${problem}`;

            // Construct the mailto link
            const mailtoLink = "mailto:" + adminEmail + 
                               "?subject=" + encodeURIComponent(subject) + 
                               "&body=" + encodeURIComponent(emailBody);

            // Open the email client
            window.location.href = mailtoLink;
        });
    }
});
