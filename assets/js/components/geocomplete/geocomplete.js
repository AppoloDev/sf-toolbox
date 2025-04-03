import { Loader } from "@googlemaps/js-api-loader"

class GeoComplete extends HTMLElement {

    connectedCallback() {
        const target = this.querySelector(this.getAttribute('target'));

        if (target) {

            const loader = new Loader({
                apiKey: this.getAttribute('api-key'),
                version: "weekly",
            });


            (async () => {
                await loader.importLibrary("places");

                const autocomplete = new google.maps.places.PlaceAutocompleteElement();

                this.appendChild(autocomplete);


                autocomplete.addEventListener('gmp-select', async ({ placePrediction }) => {
                    const place = placePrediction.toPlace();
                    await place.fetchFields({ fields: ['displayName', 'formattedAddress', 'location', 'addressComponents'] });
                    const json = place.toJSON();

                    target.value = json;
                });
            })();
        }
    }
}

window.customElements.define('geo-complete', GeoComplete);
