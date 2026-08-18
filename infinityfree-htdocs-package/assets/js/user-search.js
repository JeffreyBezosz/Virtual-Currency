const receiverInput = document.querySelector('#receiver_email');
const userResults = document.querySelector('#user_results');

if (receiverInput && userResults) {
    receiverInput.addEventListener('input', async function () {
        const query = receiverInput.value.trim();
        userResults.replaceChildren();

        if (query.length < 2) {
            return;
        }

        try {
            const response = await fetch(
                `api/search_users.php?q=${encodeURIComponent(query)}`
            );

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (receiverInput.value.trim() !== query) {
                return;
            }

            if (data.users.length === 0) {
                const emptyResult = document.createElement('li');
                emptyResult.textContent = 'Geen gebruikers gevonden.';
                userResults.appendChild(emptyResult);
                return;
            }

            data.users.forEach(function (user) {
                const result = document.createElement('li');
                const selectButton = document.createElement('button');

                selectButton.type = 'button';
                selectButton.textContent =
                    `${user.first_name} ${user.last_name} (${user.email})`;

                selectButton.addEventListener('click', function () {
                    receiverInput.value = user.email;
                    userResults.replaceChildren();
                });

                result.appendChild(selectButton);
                userResults.appendChild(result);
            });
        } catch (error) {
            userResults.replaceChildren();
        }
    });
}
