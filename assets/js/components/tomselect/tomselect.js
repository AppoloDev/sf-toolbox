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
                const hasOptgroups = typeof conf.options === 'object' &&
                    !Array.isArray(conf.options) &&
                    Object.values(conf.options).some(v => typeof v === 'object');

                if (hasOptgroups) {
                    options.optgroupField = 'group';
                    options.optgroupLabelField = 'label';
                    options.optgroupValueField = 'value';
                    options.lockOptgroupOrder = true;

                    const optgroups = [];
                    const optionsFormatted = [];

                    Object.entries(conf.options).forEach(([group, items]) => {
                        optgroups.push({
                            value: group,
                            label: group
                        });

                        if (typeof items === 'object') {
                            Object.entries(items).forEach(([label, value]) => {
                                optionsFormatted.push({
                                    text: label,
                                    value: value,
                                    group: group
                                });
                            });
                        }
                    });

                    options.optgroups = optgroups;
                    options.options = optionsFormatted;
                } else if (Array.isArray(conf.options)) {
                    options.options = conf.options.map(e => ({
                        text: e,
                        value: e
                    }));
                } else {
                    options.options = Object.entries(conf.options).map(([label, value]) => ({
                        text: label,
                        value: value
                    }));
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
        if (this.instance) {
            this.instance.destroy();
        }
    }
}

window.customElements.define('tom-select', Autocomplete);
