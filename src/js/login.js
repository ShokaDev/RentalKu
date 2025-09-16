// Kalau kamu sudah ada login.js di zip, cukup tambah ini
function switchToRegister() {
    document.querySelector('.form-box.login').classList.add('hidden');
    document.querySelector('.form-box.register').classList.remove('hidden');
}
function switchToLogin() {
    document.querySelector('.form-box.register').classList.add('hidden');
    document.querySelector('.form-box.login').classList.remove('hidden');
}
