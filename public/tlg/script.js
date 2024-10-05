// script.js

const tg = window.Telegram.WebApp;
tg.expand();  // Расширяем приложение на весь экран

document.getElementById('salary-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const userId = document.getElementById('user-id').value;
    const percentage = document.getElementById('percentage').value;
    const currency = document.getElementById('currency').value;

    fetch(`https://2395-5-59-171-221.ngrok-free.app/api/salary/user/${userId}/calculate?percentage=${percentage}&currency=${currency}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgxMzI2NjIsImV4cCI6MTcyODEzNjI2Miwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6Im1haWwxQGV4YW1wbGUuY29tIn0.UfBsJlTxsmM_wT-HpAyKpLAn2b1tBqjp5ru7ldhrIVimUmUixTx8OXMuPVMj_ZNlE0foK8D8JMudMUb2EDoIGMs_MKgaMM95Y79Z21goHQ8OXNFj1jl3TP5qPa8BDpcJNHVr4sRzTMm-Nl9VTQt-_o8kbKTgNRTPC-1SSbDfEDIcX_f00eJ5xAt5_S-Zir30T-4VBouVTqYx4KBKnwgn2LuluduATn1YPKJr27ekZxMzE1nnCXyewEaLAOB6b4F0DnahmapzX4oQUO9X6aN-hvmUAEIv30LPkc3AyhsG_unqYHHo5sAp7FMyQXS52S36_OYssmLWQglGHVsAbzIMrg',
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