function validateForm() {
    var fullname = document.getElementById('name').value.trim();
    var username = document.getElementById('username').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value.trim();
    var cpassword = document.getElementById('cpassword').value.trim();

    // Regular expression for email validation
    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Regular expression for full name validation (assuming first name and last name separated by a space)
    var fullnameRegex = /^[a-zA-Z]{3,}\s[a-zA-Z]{3,}$/;

    if (!fullnameRegex.test(fullname)) {
        alert(' Please enter both first name and last name.');
        return false;
    }

    if (password.length < 4) {
        alert('Password must be equal or than 8 characters.');
        return false;
    }

    if (!emailRegex.test(email)) {
        alert('Invalid email format.');
        return false;
    }

    if (username === '' || email === '' || password === '' || cpassword === '') {
        alert('Please fill in all fields.');
        return false;
    }

    if (password !== cpassword) {
        alert('Passwords do not match.');
        return false;
    }

    return true; 
}
