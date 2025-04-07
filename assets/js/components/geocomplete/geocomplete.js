import { Loader } from "@googlemaps/js-api-loader"

class GeoComplete extends HTMLElement {

    connectedCallback() {
        const target = this.querySelector(this.getAttribute('target'));
        const source = this.querySelector(this.getAttribute('source'));
        const dropdown = this.querySelector('ul');

        if(target.value) {
            try {
                source.value = JSON.parse(target.value).formattedAddress ?? '';
            } catch (e) {
                source.value = target.value;
            }
        }

        const options = JSON.parse(this.getAttribute('request-options') ?? '{}');

        if (source && target && dropdown) {

            const loader = new Loader({
                apiKey: this.getAttribute('api-key'),
                version: "weekly",
            });


            (async () => {
                const { AutocompleteSessionToken, AutocompleteSuggestion } = await loader.importLibrary("places");

                source.addEventListener('keydown', async () => {
                    let request = {
                        ...options,
                        input: source.value,
                    };

                    const token = new AutocompleteSessionToken();

                    request.sessionToken = token;

                    const { suggestions } = await AutocompleteSuggestion.fetchAutocompleteSuggestions(request);

                    dropdown.innerHTML = '';

                    for (let suggestion of suggestions) {
                        const placePrediction = suggestion.placePrediction;

                        const listItem = document.createElement('li');
                        listItem.appendChild(document.createTextNode(placePrediction.text.toString()));
                        listItem.addEventListener('click', async () => {
                            const place = placePrediction.toPlace();
                            await place.fetchFields({ fields: ['displayName', 'formattedAddress', 'location', 'addressComponents'] });
                            const json = place.toJSON();

                            target.value = JSON.stringify(json);
                            source.value = json.formattedAddress;
                            dropdown.innerHTML = '';
                        })

                        dropdown.appendChild(listItem);
                    }
                });
            })();
        }
    }
}

window.customElements.define('geo-complete', GeoComplete);
