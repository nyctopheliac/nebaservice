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