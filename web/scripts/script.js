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