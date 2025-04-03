class VichImage extends HTMLElement {
    connectedCallback() {
        const previewImage = this.querySelector('.image-preview') || undefined;
        const removeBtn = previewImage?.querySelector('a');
        const form = this.querySelector('.form');
        const fileInput = form.querySelector('input[type="file"]');
        const removeCheckbox = form.querySelector('.form-delete input[type="checkbox"]');

        if(removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                previewImage.classList.add('no-image');
                form.classList.remove('hidden');
                fileInput.value = null;
                if (removeCheckbox.checked === false) {
                    removeCheckbox.click();
                }
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                const files = fileInput.files;
                if (FileReader && files && files.length && previewImage) {
                    const fr = new FileReader();
                    fr.onload =  () =>  {
                        previewImage.querySelector('img').src = fr.result;
                        previewImage.classList.remove('no-image');
                        form?.classList.add('hidden');
                    }
                    fr.readAsDataURL(files[0]);
                }
            })
        }
    }
}

window.customElements.define('vich-image', VichImage);
