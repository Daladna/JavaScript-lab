// Клиентская проверка форм (класс js-validate). Использует атрибуты полей:
// required, minlength, maxlength, min, max, type=email, data-match, data-future.
// Серверная проверка выполняется в любом случае.
document.querySelectorAll('form.js-validate').forEach(function (form) {
    form.setAttribute('novalidate', 'novalidate');

    function getMessage(f) {
        var v = f.value.trim();
        if (f.validity.valueMissing) {
            return f.type === 'radio' || f.tagName === 'SELECT' ? 'Выберите значение' : 'Поле обязательно для заполнения';
        }
        if (f.type === 'email' && v !== '' && f.validity.typeMismatch) {
            return 'Введите корректный email';
        }
        if (f.minLength > 0 && v !== '' && f.value.length < f.minLength) {
            return 'Минимальная длина — ' + f.minLength + ' симв.';
        }
        if (f.maxLength > 0 && f.value.length > f.maxLength) {
            return 'Максимальная длина — ' + f.maxLength + ' симв.';
        }
        if (f.type === 'number' && v !== '') {
            if (f.validity.badInput) return 'Введите число';
            if (f.validity.rangeUnderflow) return 'Значение не может быть меньше ' + f.min;
            if (f.validity.rangeOverflow) return 'Значение не может быть больше ' + f.max;
        }
        if (f.dataset.match && f.value !== form.elements[f.dataset.match].value) {
            return 'Пароли не совпадают';
        }
        if (f.hasAttribute('data-future') && v !== '' && new Date(v) < new Date()) {
            return 'Дата не может быть в прошлом';
        }
        return '';
    }

    function showError(f, msg) {
        var span = form.querySelector('[data-error-for="' + f.name + '"]');
        if (span) span.textContent = msg;
        f.classList.toggle('invalid', msg !== '');
    }

    form.addEventListener('submit', function (ev) {
        var ok = true;
        form.querySelectorAll('input, select, textarea').forEach(function (f) {
            if (f.type === 'hidden' || !f.name) return;
            var msg = getMessage(f);
            showError(f, msg);
            if (msg) ok = false;
        });
        if (!ok) ev.preventDefault();
    });
});
