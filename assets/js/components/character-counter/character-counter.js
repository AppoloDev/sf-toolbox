import './_character-counter.scss';

class CharacterCounter extends HTMLElement {
    connectedCallback() {
        this.inputElement = this.querySelector('input') || null;
        this.elementMaxLength = parseInt(this.getAttribute('max-length'));

        if (this.inputElement && this.elementMaxLength > 0) {
            this.createElements();
            this.setCounterValue(this.inputElement.value.length);

            this.inputElement.addEventListener('keyup', (evt) => {
                this.setCounterValue(evt.currentTarget.value.length);

                if (evt.currentTarget.value.length > this.elementMaxLength) {
                    this.counterElement.classList.add('over');
                } else if (this.counterElement.classList.contains('over') && evt.currentTarget.value.length <= this.elementMaxLength) {
                    this.counterElement.classList.remove('over');
                }
            });
        }
    }

    createElements() {
        this.wrapperElement = document.createElement('div');
        this.wrapperElement.classList.add('character-counter');

        this.inputElement.parentNode.insertBefore(this.wrapperElement, this.inputElement);
        this.wrapperElement.appendChild(this.inputElement);

        this.counterElement = document.createElement('div');
        this.counterElement.classList.add('counter');

        this.wrapperElement.appendChild(this.counterElement);
    }

    setCounterValue(defaultValue = 0) {
        this.counterElement.innerHTML = `
            <span class="counter-current-value">${defaultValue}</span> / <span>${this.elementMaxLength}</span>
        `
    }
}

window.customElements.define('character-counter', CharacterCounter);
