const balanceElement = document.querySelector('#balance');

async function refreshBalance() {
    if (!balanceElement) {
        return;
    }

    try {
        const response = await fetch('api/balance.php');

        if (!response.ok) {
            return;
        }

        const data = await response.json();
        balanceElement.textContent = data.balance;
    } catch (error) {
        return;
    }
}

if (balanceElement) {
    refreshBalance();
    setInterval(refreshBalance, 10000);
}
