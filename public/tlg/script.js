const tg = window.Telegram.WebApp;
tg.expand();  // Expand the app to full screen

document.getElementById('currency-form').addEventListener('submit', function(event) {
    event.preventDefault();
    fetchConversionData();
});

// Function to fetch conversion data
function fetchConversionData() {
    // Get selected values
    const fromCurrency = document.getElementById('from-currency').value;
    const toCurrency = document.getElementById('to-currency').value;
    const amount = document.getElementById('amount').value;

    // API request with a valid token
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
            // When we get the result from the API, insert the values into the result block
            displayConvertedAmount(data, toCurrency);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerText = 'Sorry, there was an error converting the currency. Please try again later.';
        });
}

// Function to display only the converted amount
function displayConvertedAmount(data, toCurrency) {
    const convertedAmount = (typeof data.convertedAmount !== 'undefined')
        ? (toCurrency === 'BTC' ? data.convertedAmount : data.convertedAmount.toFixed(2))
        : '0.00';

    // Update the result block
    const resultDiv = document.querySelector('.result-box');
    resultDiv.innerHTML = `
        <div class="result-item">${convertedAmount} ${toCurrency}</div>
    `;
}

// Check the theme (light or dark) in Telegram
if (tg.colorScheme === "dark") {
    document.body.classList.add('dark');
} else {
    document.body.classList.remove('dark');
}

// Add event listener for the swap button
document.getElementById('swap-currencies').addEventListener('click', function() {
    const fromCurrencySelect = document.getElementById('from-currency');
    const toCurrencySelect = document.getElementById('to-currency');

    // Save current values
    const fromCurrency = fromCurrencySelect.value;
    const toCurrency = toCurrencySelect.value;

    // Swap values
    fromCurrencySelect.value = toCurrency;
    toCurrencySelect.value = fromCurrency;

    // Recalculate the converted amount
    fetchConversionData();
});

// Fetch conversion data on page load
window.addEventListener('load', fetchConversionData);