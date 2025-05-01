// Password validation before sending
function validateForm() {
    let email = document.forms["loginForm"]["email"].value;
    let password = document.forms["loginForm"]["password"].value;

    if (email.length < 5 || !email.includes("@")) {
        alert("Bitte geben Sie eine gültige E-Mail-Adresse ein.");
        return false;
    }

    if (password.length < 9 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
        alert("Passwort muss mindestens 9 Zeichen, einen Großbuchstaben, einen Kleinbuchstaben und eine Zahl enthalten.");
        return false;
    }

    return true;
}
