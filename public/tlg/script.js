const tg = window.Telegram.WebApp;
tg.expand();  // Расширяем приложение на весь экран

document.getElementById('currency-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Получаем выбранные значения
    const fromCurrency = document.getElementById('from-currency').value;
    const toCurrency = document.getElementById('to-currency').value;
    const amount = document.getElementById('amount').value;

    // Запрос к API с использованием валидного токена
    fetch(`https://endpointtools.com/api/currency/convert?fromCurrency=${fromCurrency}&toCurrency=${toCurrency}&amount=${amount}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgyMzU0NzQsImV4cCI6MTcyODIzOTA3NCwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6Im1haWwxQGV4YW1wbGUuY29tIn0.LOsKrkxNJC1AlpyQaxhqWeaoSxXuczXjzZkbm19jrIrRNWx9lRgEZgs2slUtpBC8V_szkxwCu-C3nKoyX6UwDg5Gbdg8y7k0480hUzYWxHo7HN-06DqJT-dd-R9kSzSPRZlR5tiy4kNmtyfMHxE4NYNfIKRf8x30OTzlugwd0Czu4g_lXmOtHKpE6sH-8gl4w19tuAy1LXv0Q48_5C1DOjhbKnJkAhF6B0tbx5Xb3YjMuF7kOLOxTDEdeiJkGI8X1emSiR3hJUPPaueQq9PV2YD3GzgLo5p225yoL-FDo463c1NyZ4AoBsLXP6cdzOMI3SoOKF5_91yCF0lZIbqh2A',
            'accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Когда получаем результат от API, подставляем значения в блок с результатом
            displayConvertedAmount(data, toCurrency);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerText = 'Error converting currency.';
        });
});

// Функция для отображения только конвертированной суммы
function displayConvertedAmount(data, toCurrency) {
    const convertedAmount = typeof data.convertedAmount !== 'undefined'
        ? data.convertedAmount.toFixed(2)  // Округляем результат до 2 знаков
        : '0.00';

    // Обновляем блок с результатом
    const resultDiv = document.querySelector('.result-box');
    resultDiv.innerHTML = `
        <div class="result-item">Converted Amount: ${convertedAmount} ${toCurrency}</div>
    `;
}

// Проверка темы (светлая или тёмная) в Telegram
if (tg.colorScheme === "dark") {
    document.body.classList.add('dark');
} else {
    document.body.classList.remove('dark');
}
