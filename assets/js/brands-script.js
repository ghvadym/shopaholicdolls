const ajax = wopajax.ajaxurl;

document.addEventListener('change', function (event) {
    const target = event.target;

    if (target.getAttribute('name') === 'alphabet-letter') {
        const brandsResult = document.querySelector('.brands__result');
        const letter = target.getAttribute('data-value');

        if (!brandsResult.classList.contains('d-none')) {
            brandsResult.classList.add('_spinner');
        } else {
            filterResults.classList.add('_spinner');
        }

        fetch(ajax, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: 'letter=' + encodeURIComponent(letter) + '&action=brands_filter',
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    console.log(data.message);
                }

                if (data.html) {
                    brandsResult.innerHTML = data.html;
                } else {
                    // If no brands are returned, display a message
                    brandsResult.innerHTML = "<div class='brands__result-no-brands'>There are no brands yet</div>";
                }

                brandsResult.classList.remove('_spinner');
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }
});

function addResetButton() {
    let brandsAlphabet = document.querySelector('.brands__alphabet');
    let resetFilterElement = document.createElement('div');

    resetFilterElement.className = 'brands__letter';
    resetFilterElement.id = 'reset-filter';

    resetFilterElement.textContent = '#';

    brandsAlphabet.appendChild(resetFilterElement);
}

addResetButton();

const resetFilterButton = document.getElementById('reset-filter');
if (resetFilterButton) {
    resetFilterButton.addEventListener('click', function (event) {
        event.preventDefault();

        const brandsResult = document.querySelector('.brands__result');
        const filterResults = document.querySelector('.filter-results');

        if (!brandsResult.classList.contains('d-none')) {
            brandsResult.classList.add('_spinner');
        } else {
            filterResults.classList.add('_spinner');
        }

        fetch(ajax, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: 'action=reset_brands_filter',
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    console.log(data.message);
                }

                if (data.html) {
                    brandsResult.innerHTML = data.html;
                }

                brandsResult.classList.remove('_spinner');
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    });
}