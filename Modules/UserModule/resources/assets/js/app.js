 class EmailVerification {
        constructor() {
            this.emailInput = document.getElementById('emailInput');
            this.otpInput = document.getElementById('emailOtpInput');
            this.emailMethodBtn = document.getElementById('emailMethodBtn');
            this.phoneMethodBtn = document.getElementById('phoneMethodBtn');
            this.confirmBtn = document.getElementById('emailConfirmBtn');
            this.cancelBtn = document.getElementById('emailCancelBtn');
            this.changeEmailBtn = document.getElementById('changeMailBtn');
            this.modal = document.getElementById('emailChangeModal');

            this.verificationMethod = 'email';

            this.initEvents();
        }

        initEvents() {
            this.changeEmailBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showModal();
            });

            this.cancelBtn.addEventListener('click', () => this.hideModal());

            this.emailMethodBtn.addEventListener('click', () => this.selectMethod('email'));
            this.phoneMethodBtn.addEventListener('click', () => this.selectMethod('phone'));

            this.otpInput.addEventListener('input', () => this.toggleConfirmButton());

            this.confirmBtn.addEventListener('click', () => {
                if (this.confirmBtn.disabled) return;
                this.hideModal();
            });
        }

        showModal() {
            this.modal.classList.remove('pointer-events-none', 'opacity-0');
            this.modal.classList.add('opacity-100');
        }

        hideModal() {
            this.modal.classList.remove('opacity-100');
            this.modal.classList.add('opacity-0');

            setTimeout(() => {
                this.modal.classList.add('pointer-events-none');
                this.otpInput.value = '';

                this.confirmBtn.disabled = true;
                this.confirmBtn.classList.add('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.remove('bg-blue-500', 'text-white', 'cursor-pointer');
            }, 300); 
        }

        selectMethod(method) {
            this.verificationMethod = method;

            if (method === 'email') {
                this.emailMethodBtn.classList.add('border-blue-500', 'bg-blue-50');
                this.phoneMethodBtn.classList.remove('border-blue-500', 'bg-blue-50');
            } else {
                this.phoneMethodBtn.classList.add('border-blue-500', 'bg-blue-50');
                this.emailMethodBtn.classList.remove('border-blue-500', 'bg-blue-50');
            }
        }

        toggleConfirmButton() {
            const isValid = this.otpInput.value.trim().length === 6;

            this.confirmBtn.disabled = !isValid;

            if (isValid) {
                this.confirmBtn.classList.remove('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.add('bg-blue-500', 'text-white', 'cursor-pointer');
            } else {
                this.confirmBtn.classList.add('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.remove('bg-blue-500', 'text-white', 'cursor-pointer');
            }
        }
    }

