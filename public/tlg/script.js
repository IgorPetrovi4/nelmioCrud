const tg = window.Telegram.WebApp;
tg.expand();  // Расширяем приложение на весь экран

document.getElementById('currency-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const fromCurrency = document.getElementById('from-currency').value;
    const toCurrency = document.getElementById('to-currency').value;
    const amount = document.getElementById('amount').value;

    fetch(`https://endpointtools.com/api/currency/convert?fromCurrency=${fromCurrency}&toCurrency=${toCurrency}&amount=${amount}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgyMzI1MjcsImV4cCI6MTcyODIzNjEyNywicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6Im1haWwxQGV4YW1wbGUuY29tIn0.rVSGeJHpdDV-KGNa1h4p-anTIIXHD3_TQ3JGAlrYGCs9wi-ubcy6UTRNV6G3rCa5OVRBBlz5oleu0ZJ9bw3zSZj66OIFpqetzV7ru0-OPPKytYr53lilYu9VF76VCZYPOCUPKxTnndtrYneKWnox2IRjx8z5h5RJ8R0sljo116ThFn5yisz2cHrmmx7wusIY1-V6Tg06qXPqtqh_Rqcd5EPorFYVHp2wuQWFvKZ4HL3wPboBcVX_3COGtSPumDH4-igKjKOW35qy6I_yKOxaGHDJspx46v4cBnBHMa5Ju2Ahnj3jgiL5dExD4tti_e2KE2MwM6NrESrxGLPJqw8HyQ',
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
            displayResult(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerText = 'Error converting currency.';
        });
});

// Отображаем результат конвертации
function displayResult(data) {
    // Проверка и извлечение данных из ответа API
    const fromCurrency = data.fromCurrency || 'N/A';
    const toCurrency = data.toCurrency || 'N/A';
    const amount = data.amount || 'N/A';

    // Предварительная проверка на наличие convertedAmount
    const convertedAmount = typeof data.convertedAmount !== 'undefined' ? data.convertedAmount : '0.00';
    const exchangeRate = data.exchangeRate || 'N/A';

    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = `
        <div class="result-item">From Currency: ${fromCurrency}</div>
        <div class="result-item">To Currency: ${toCurrency}</div>
        <div class="result-item">Amount: ${amount}</div>
        <div class="result-item">Converted Amount: ${convertedAmount} ${toCurrency}</div>
        <div class="result-item">Exchange Rate: ${exchangeRate}</div>
    `;
}

// Проверка темы (светлая или тёмная) в Telegram
if (tg.colorScheme === "dark") {
    document.body.classList.add('dark');
} else {
    document.body.classList.remove('dark');
}