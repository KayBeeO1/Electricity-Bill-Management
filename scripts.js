document.getElementById("loginForm").addEventListener("submit", function(event) {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if (username.trim() === "" || password.trim() === "") {
        alert("Please enter both username and password.");
        event.preventDefault();
    }
});

function getParameterByName(name) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

function handleCredentialResponse(response) {
    const responsePayload = decodeJwtResponse(response.credential);
    console.log('ID: ' + responsePayload.sub);
    console.log('Full Name: ' + responsePayload.name);
    console.log('Given Name: ' + responsePayload.given_name);
    console.log('Family Name: ' + responsePayload.family_name);
    console.log('Image URL: ' + responsePayload.picture);
    console.log('Email: ' + responsePayload.email);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'google_login.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                window.location.href = 'billing_information.php';
            } else {
                console.error('Login failed.');
            }
        }
    };
    xhr.send(JSON.stringify(responsePayload));
}

function decodeJwtResponse(token) {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

window.onload = function() {
    google.accounts.id.initialize({
        client_id: '917448873997-dfjatpntsmmuc51t11p2ms4q7oeelitq.apps.googleusercontent.com',
        callback: handleCredentialResponse
    });
    google.accounts.id.renderButton(
        document.getElementById('google-signin-button'),
        { theme: 'outline', size: 'large' }
    );
    google.accounts.id.prompt();

    var errorMessage = getParameterByName('error');
    if (errorMessage) {
        document.getElementById('errorMessage').innerText = errorMessage;
    }
};
