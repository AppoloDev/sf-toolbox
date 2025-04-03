import TomSelect from "tom-select";
import maxItemPlugin from "./plugins/max-items";

class Autocomplete extends HTMLElement {
    connectedCallback() {
        const select = this.querySelector('select') || this.querySelector('input');

        const conf = JSON.parse(this.getAttribute('options'));

        const addText = this.getAttribute('addText') ?? 'Add';
        const noMatchText = this.getAttribute('noMatchText') ?? 'No matches found';

        if (select) {
            let options = {...{
                    plugins: ['dropdown_input'],
                    maxOptions: null,
                    maxItems: conf.maxItems ?? null,
                    create: this.hasAttribute('create'),
                    dropdownParent: 'body',
                    render: {
                        option_create: function( data, escape ){
                            return `<div class="create">${addText} : <strong>${escape(data.input)}</strong>&hellip;</div>`;
                        },
                        no_results: function (data, escape) {
                            return `<div class="no-results">${noMatchText}</div>`;
                        },
                    }
                }, ...conf};

            if (conf.options) {
                if (Array.isArray(conf.options)) {
                    options.options = options.options.map(e => ({
                        text: e,
                        value: e
                    }));
                } else if (typeof conf.options === 'object') {
                    options.options = Object.entries(options.options).map(([label, value]) => ({
                        text: label,
                        value: value
                    }));
                } else {
                    console.error('Can\'t handle options', conf.options);
                    return;
                }
            }

            if (conf.maxItems === null || conf.maxItems > 1) {
                options.plugins.push('remove_button');
                options.plugins.push('checkbox_options');
            }

            if (conf.maxItemsCount) {
                options.plugins.push('maxItemPlugin');
                if(conf.maxItemsCount) {
                    options.maxItemsCount = conf.maxItemsCount ?? null;
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
