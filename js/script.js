// Function to check if the username is valid
function checkUsername(val) {
    var check = /^[_a-z]+$/g; // Regex to allow only lowercase latin letters and underscores

    // Using jQuery to update the DOM based on the regex test result
    if (!check.test(val)) {
        $('#checktext').html('Only lower case latin letters and \'_\' are allowed!');
        $('#checktext').css('color', 'red');
    } else {
        $('#checktext').html('');
    }
}

// Function to check if the username is already taken using AJAX
function checkUser(val) {
    $.ajax({
        url: '../model/duplicateUsers.php', // URL to check for duplicate usernames
        method: 'POST', // HTTP method
        data: { 'username': val }, // Data to be sent to the server
        async: false // Synchronous AJAX request
    }).done(function (data) {
        var check = JSON.parse(data); // Parse the JSON response
        if (check.success == true) {
            $('#checkuser').html('This username is already taken!');
            $('#checkuser').css('color', 'red');
            $('#uname').val(''); // Clear the input field if username is taken
        } else {
            $('#checkuser').html('Username Available!');
            $('#checkuser').css('color', 'lightgreen');
        }
    });
}

// Function to check if the email address is valid
function checkUsermail(val) {
    var check = /^([a-zA-Z0-9]\.?)+[^\.]@([a-zA-Z0-9]\.?)+[^\.]$/g; // Regex to validate email format
    if (!check.test(val)) {
        $('#checkmail').html('Please enter a valid email address!');
        $('#checkmail').css('color', 'red');
        $('#email').val(''); // Clear the input field if email is invalid
    } else {
        $('#checkmail').html('');
    }
}

// Function to check if the password is strong enough
function checkUserpass(val) {
    var check = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[_!@#\$%\^&\*])(?=.{8,})/g; // Regex to validate password strength
    if (!check.test(val)) {
        $('#checkpass').html('Password must contain 8 characters and at least 1 lowercase letter, 1 uppercase letter, 1 number & 1 special character!');
        $('#checkpass').css('color', 'red');
        $('#email').val(''); // Clear the input field if password is weak
    } else {
        $('#checkpass').html('');
    }
}
