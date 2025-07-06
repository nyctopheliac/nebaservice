const registerButton = document.getElementById('registerButton');
const loginButton = document.getElementById('loginButton');
const loginForm = document.getElementById('login');
const registerForm = document.getElementById('register');


registerButton.addEventListener('click', function() {
    loginForm.style.display = "none";
    registerForm.style.display = "block";
})

loginButton.addEventListener('click', function() {
    loginForm.style.display = "block";
    registerForm.style.display = "none";
})

const pathToForm = document.getElementById('pathToForm');

/*
document.addEventListener("DOMContentLoaded", function() {
    const isLoggedIn = logic to check if user is logged in ;
    const loginButton = document.getElementById("loginButton");
    const profileDropdown = document.getElementById("profileDropdown");
    if (isLoggedIn) {
        loginButton.style.display = "none";
        profileDropdown.style.display = "block";
    } else {
        loginButton.style.display = "block";
        profileDropdown.style.display = "none";
    }
});*/
