document.addEventListener("DOMContentLoaded", function() {
    const themeToggleCheckbox = document.getElementById("theme-toggle-checkbox");
    
    // Установить начальную тему на основе куки
    const theme = getCookie("theme");
    if (theme === "dark") {
        document.body.classList.add("dark");
        themeToggleCheckbox.checked = true;
    }

    // Обработка изменения состояния переключателя темы
    themeToggleCheckbox.addEventListener("change", function() {
        if (this.checked) {
            document.body.classList.add("dark");
            setCookie("theme", "dark", 365);
        } else {
            document.body.classList.remove("dark");
            setCookie("theme", "light", 365);
        }
    });

    // Функция установки куки
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + "=" + value + ";expires=" + expires.toUTCString() + ";path=/";
    }

    // Функция получения значения куки
    function getCookie(name) {
        const nameEQ = name + "=";
        const cookies = document.cookie.split(';');
        for(let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i];
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1, cookie.length);
            }
            if (cookie.indexOf(nameEQ) === 0) {
                return cookie.substring(nameEQ.length, cookie.length);
            }
        }
        return null;
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const bodyElement = document.body;
    const cardElement = document.querySelector('.card');
    const toggleThemeButton = document.getElementById('toggleThemeButton');

    // Функция для установки куки
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    // Функция для получения значения куки
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Проверка куки и установка начальной темы
    const currentTheme = getCookie('theme') || 'light';
    if (currentTheme === 'dark') {
        bodyElement.classList.add('dark');
        cardElement.classList.add('dark');
    }

    // Обработчик переключения темы
    toggleThemeButton.addEventListener('click', function () {
        const isDark = bodyElement.classList.toggle('dark');
        cardElement.classList.toggle('dark', isDark);
        const newTheme = isDark ? 'dark' : 'light';
        setCookie('theme', newTheme, 365);
    });
});
