import { Loader } from "@googlemaps/js-api-loader"

class GeoComplete extends HTMLElement {
    connectedCallback() {
        const source = this.querySelector(this.getAttribute('source'));
        const target = this.querySelector(this.getAttribute('target'));

        if (source && target) {

            const loader = new Loader({
                apiKey: this.getAttribute('api-key'),
                version: "weekly",
            });


            (async () => {
                await loader.importLibrary("places");

                const options = {
                    fields: ["address_components", "formatted_address", "geometry", "name"],
                    componentRestrictions: {country: 'fr'},
                    strictBounds: false,
                };
                const autocomplete = new google.maps.places.Autocomplete(source, options);
                autocomplete.addListener("place_changed", () => {
                    target.value = JSON.stringify(autocomplete.getPlace());
                });
            })();
        }
    }
}

window.customElements.define('geo-complete', GeoComplete);
