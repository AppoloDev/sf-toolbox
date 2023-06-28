import Swal from 'sweetalert2/src/sweetalert2';

class SweetAlert extends HTMLElement {
	connectedCallback() {
		this.confirmationLink = this.querySelector('a');
		this.confirmationLink.addEventListener('click', (e) => {
			e.preventDefault();
			this.displaySwal();
		});
	}

	async displaySwal() {
		Swal.fire({
			title: this.getAttribute('title'),
			text: this.getAttribute('text'),
			showCancelButton: true,
			cancelButtonText: 'Annuler',
			confirmButtonText: 'Je confirme',
			customClass: {
				container: `sweetalert sweetalert__container sweetalert__container__${this.getAttribute('color')}`,
				popup: 'sweetalert__popup',
				title: `sweetalert__title text__${this.getAttribute('color')}`,
				htmlContainer: `sweetalert__content`,
				actions: `sweetalert__actions`,
				loader: 'hidden',
				confirmButton: `btn btn-solid btn-${this.getAttribute('color')}`,
				cancelButton: 'btn btn-solid btn-white',
			},
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = this.confirmationLink.href;
			}
		});
	}
}

window.customElements.define('sweet-alert', SweetAlert);
