import TomSelect from "tom-select";
import "./_tomselect.scss"
import maxItemPlugin from "./plugins/max-items";

class Autocomplete extends HTMLElement {
    connectedCallback() {
        const select = this.querySelector('select') || this.querySelector('input');

        if (select) {
            let options = {
                plugins: ['dropdown_input'],
                maxOptions: null,
                create: this.hasAttribute('create'),
                dropdownParent: 'body',
                maxItemsCount: parseInt(this.getAttribute('maxItems')) ?? 2,
                render: {
                    no_results: function (data, escape) {
                        return '<div class="no-results">Aucun résultat trouvé</div>';
                    },
                }
            };

            if (this.hasAttribute('options')) {
                options.options = JSON.parse(this.getAttribute('options')).map(e => ({
                    text: e,
                    value: e
                }));
            }

            if (select.hasAttribute('multiple')) {
                options.plugins.push('remove_button');
                options.plugins.push('checkbox_options');
            }


            if (this.hasAttribute('maxItems')) {
                options.plugins.push('maxItemPlugin');
                if (parseInt(this.getAttribute('maxItems')) === 1) {
                    options.maxItems = 1;
                }
            }

            TomSelect.define('maxItemPlugin', maxItemPlugin)
            this.instance = new TomSelect(select, options);
        }
    }

    disconnectedCallback() {
    }
}

window.customElements.define('tom-select', Autocomplete);
