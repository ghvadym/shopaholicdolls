document.addEventListener('DOMContentLoaded', function () {
    const checkBox = document.querySelector(".contact-form__terms");
    const labelTerms = document.querySelector(".contact-form__label-terms");

    checkBox.addEventListener('change', function () {
        if (checkBox.checked) {
            labelTerms.classList.add('checked');
        } else {
            labelTerms.classList.remove('checked');
        }
    });

});

function mapInit() {
    document.querySelectorAll('.contact-us__map').forEach(function(mapElement) {
        const markerElement = mapElement.querySelector('.contact-us__map-marker');


        let lat = parseFloat(markerElement.getAttribute('data-lat'));
        let lng = parseFloat(markerElement.getAttribute('data-lng'));

        const googleMap = new google.maps.Map(mapElement, {
            zoom: 16,
            center: new google.maps.LatLng(lat, lng),
            disableDefaultUI: true,
        });

        const marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: googleMap,
        });
    });
}

window.mapInit = mapInit;