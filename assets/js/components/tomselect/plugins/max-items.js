function maxItemPlugin() {
    let span;
    const clientRect = document.body.getBoundingClientRect();

    const itemCount = () => {
        let numberHiddenItem = 0;

        if (this.settings.maxItemsCount > 1 && clientRect.width <= 1366) {
            hideItems(this.items, 1);
            numberHiddenItem = this.items.length - 1;
        } else {
            hideItems(this.items);
            numberHiddenItem = this.items.length - this.settings.maxItemsCount;
        }

        if (numberHiddenItem > 0) {
            span.className = 'items-count';
            span.innerText = `+${numberHiddenItem} élément${numberHiddenItem > 1 ? 's' : ''}`
        } else {
            span.className = '!hidden';
        }
    }

    const hideItems = (items, nbrShow = this.settings.maxItemsCount) => {
        items.forEach((id) => {
            this.control.querySelector(`[data-value="${id}"]`).classList.remove('!hidden');
        })

        const cloneItems = [...items];

        cloneItems.splice(0, nbrShow);
        cloneItems.forEach((id) => {
            this.control.querySelector(`[data-value="${id}"]`).classList.add('!hidden');
        })
    }

    this.on('initialize', () => {
        span = document.createElement('span');
        this.control.appendChild(span);
        itemCount()
    });

    this.on('item_remove', itemCount);
    this.on('item_add', itemCount);
}

export default maxItemPlugin;
