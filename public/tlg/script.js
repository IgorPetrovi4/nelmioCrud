const tg = window.Telegram.WebApp;
tg.expand();

document.getElementById('currency-form').addEventListener('submit', function(event) {
    event.preventDefault();
    fetchConversionData();
});

function fetchConversionData() {
    const fromCurrency = document.getElementById('from-currency').value;
    const toCurrency = document.getElementById('to-currency').value;
    const amount = document.getElementById('amount').value;

    fetch(`https://endpointtools.com/api/currency/convert?fromCurrency=${fromCurrency}&toCurrency=${toCurrency}&amount=${amount}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${apiToken}`,
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
            displayConvertedAmount(data, toCurrency);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerText = 'Sorry, there was an error converting the currency. Please try again later.';
        });
}

function displayConvertedAmount(data, toCurrency) {
    const convertedAmount = (typeof data.convertedAmount !== 'undefined')
        ? (toCurrency === 'BTC' ? data.convertedAmount : data.convertedAmount.toFixed(2))
        : '0.00';

    const resultDiv = document.querySelector('.result-box');
    resultDiv.innerHTML = `
        <div class="result-item">${convertedAmount} ${toCurrency}</div>
    `;
}

if (tg.colorScheme === "dark") {
    document.body.classList.add('dark');
} else {
    document.body.classList.remove('dark');
}

document.getElementById('swap-currencies').addEventListener('click', function() {
    const fromCurrencySelect = document.getElementById('from-currency');
    const toCurrencySelect = document.getElementById('to-currency');

    const fromCurrency = fromCurrencySelect.value;
    const toCurrency = toCurrencySelect.value;

    fromCurrencySelect.value = toCurrency;
    toCurrencySelect.value = fromCurrency;

    fetchConversionData();
});

window.addEventListener('load', fetchConversionData);