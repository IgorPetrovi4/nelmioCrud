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
            'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MjgyMDkxMDgsImV4cCI6MTcyODIxMjcwOCwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6Im1haWwxQGV4YW1wbGUuY29tIn0.FT4izq4a4_dWBqDSxWo3tGCSu2U33RT45kosyStOrS0aZYNufwK2TRD2KryBo2UTmoD84Vq2WcKG6IpPEK_g_8WBXyVrQpTs8CKYMlBr5FIo5wjGceEUOvScVss5zJMte85AwMlL1c74vYL0lUSVcDYnsLYD1qMMbhTu0YKoDDn3z8L3M3FRb8-fvmv9K2UPCVzDPqXbfrDdi-tploClDeHPnSfFUnwld2ALpuPNlddv8AyJNF8zUMgbQD6DKmjyLGsM5nEQhRUB-8fuaFbo0bnc9qjLDm15gYA-a2cS4wZw2ow9lVGJjXhKeOiE4fb3bDWWH5TFZgjY9JzQW7RsJA',
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