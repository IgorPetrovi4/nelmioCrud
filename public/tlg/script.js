// script.js

const tg = window.Telegram.WebApp;
tg.expand();  // Расширяем приложение на весь экран

document.getElementById('salary-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const userId = document.getElementById('user-id').value;
    const percentage = document.getElementById('percentage').value;
    const currency = document.getElementById('currency').value;

    fetch(`https://endpointtools.com/api/salary/user/${userId}/calculate?percentage=${percentage}&currency=${currency}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgyMjAyNDIsImV4cCI6MTcyODIyMzg0Miwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6Im1haWwxQGV4YW1wbGUuY29tIn0.3QYYg5lbEGudwa_zVokXKLQXJhLPXrEUHTpukaXtUxraypu5EgpOVoxsZPqgMOapvuQOUsPcG_fyj8Ifr2vT0S-rM8UG-OqMJjQxt3XSSp5JjkMIrJa71eCQhkF9cNwUep-BRuXXcPGHAN_ilS-egkoFrVWnuMjggdCBJML_9dMiH1BmjYCsjI20Kleui5SmsdL2DXux_yZb8f1WFi-le_3KEhtoZ9mW23L5Z2ZN1vDtLOwrc74KBnVe2Zxfb9OGTxxsYuXznYj8lwaHU6bU9Uj_vSH_B_XLSO1DA2A11CF5e8-1SwMLjxaexWSBmbTYwe3ygxm1xz1-bwrjLlD-3Q',
            'accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            displayResult(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerText = 'Error calculating salary.';
        });
});

// Отображаем результат в красивом виде
function displayResult(data) {
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = `
        <div class="result-item">User: ${data.user.email}</div>
        <div class="result-item">Currency: ${data.currency}</div>
        <div class="result-item">Total Salary: ${data.user.totalSalary} ${data.currency}</div>
        <div class="result-item">Exchange Rate: ${data.exchangeRate}</div>
        <div class="result-item">Average Salary: ${data.averageSalary} ${data.currency}</div>
        <div class="result-item">Salary Increase: ${data.salaryIncrease} ${data.currency}</div>
    `;
}

// Проверка темы (светлая или тёмная) в Telegram
if (tg.colorScheme === "dark") {
    document.body.classList.add('dark');
} else {
    document.body.classList.remove('dark');
}