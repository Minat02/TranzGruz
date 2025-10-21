// Функции для управления модальным окном
function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}

// Функции для переключения между формами
function showRegistrationForm() {
    document.getElementById('loginForm').classList.remove('active');
    document.getElementById('registrationForm').classList.add('active');
}

function showLoginForm() {
    document.getElementById('registrationForm').classList.remove('active');
    document.getElementById('loginForm').classList.add('active');
}

// Закрытие модального окна при клике вне его
window.onclick = function (event) {
    const modal = document.getElementById('myModal');
    if (event.target === modal) {
        closeModal();
    }
}

function smoothScrollTo(targetId) {
    const targetElement = document.getElementById(targetId);
    if (targetElement) {
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Использование
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        smoothScrollTo(targetId);
    });
});