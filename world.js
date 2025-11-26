document.addEventListener('DOMContentLoaded', function () {
    const lookupButton = document.getElementById('lookup');
    const lookupCitiesButton = document.getElementById('lookup-cities');
    const countryInput = document.getElementById('country');
    const resultDiv = document.getElementById('result');

    // Function to perform AJAX requests
    function fetchData(query) {
        console.log('Fetching URL:', query);
        const xhr = new XMLHttpRequest();
        xhr.open('GET', query, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                if (xhr.status === 200) {
                    resultDiv.innerHTML = xhr.responseText;
                } else {
                    resultDiv.innerHTML = 'An error occurred while fetching the data. Status: ' + xhr.status + ' - ' + xhr.responseText;
                }
            }
        };
        xhr.onerror = function () {
            console.error('AJAX error occurred');
            console.error('URL attempted:', query);
            resultDiv.innerHTML = 'Network error occurred. Make sure PHP server is running and world.php exists in the same directory.';
        };
        xhr.send();
    }

    // Event listener for the "Lookup" button
    lookupButton.addEventListener('click', function () {
        const country = countryInput.value.trim();
        if (!country) {
            resultDiv.innerHTML = 'Please enter a country name.';
            return;
        }
        const url = `http://localhost/info2180-lab5/world.php?country=${encodeURIComponent(country)}`;
        fetchData(url);
    });

    // Event listener for the "Lookup Cities" button
    lookupCitiesButton.addEventListener('click', function () {
        const country = countryInput.value.trim();
        if (!country) {
            resultDiv.innerHTML = 'Please enter a country name.';
            return;
        }
        const url = `http://localhost/info2180-lab5/world.php?country=${encodeURIComponent(country)}&lookup=cities`;
        fetchData(url);
    });
});


