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
            'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgyMzQzNjgsImV4cCI6MTcyODIzNzk2OCwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6Im1haWwxQGV4YW1wbGUuY29tIn0.KkkNIaQwzFfJEITAcWZq3ajrOqSbfIqnz82mFI1HZCOrUBYIKIW30SUqxJWjVlZiHPd1UPWklWlOGg94ley7W0yod6H36wa3C_aIWbs8ltQGDVMd0LWg331pktGMF_wHBIbWioxlKdW9ZYJSONcVd8dc_TR7p4B_1LVMgkCrxW1pbANGG1tsdSyo1hh4UT2k_HBhp3enfHZ4ckXaxow0iGFsCar6z8s2Q9Eg8Qan7JO36tt7JG4npUDwNiDRkjnz0i0LYzos-vNS06BaDwulWhh0wpp8DZ1cLzhdm7bW-W8n72PUsOR0Ikei-40W56Y6yT8a-XwrNWTrh48J6NSzrQ',
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
