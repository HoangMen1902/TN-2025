
    class PhoneVerification {
        constructor() {
            this.phoneInput = document.getElementById('phoneInput');
            this.otpInput = document.getElementById('otpInput');
            this.smsBtn = document.getElementById('smsMethodBtn');
            this.zaloBtn = document.getElementById('zaloMethodBtn');
            this.confirmBtn = document.getElementById('confirmBtn');
            this.cancelBtn = document.getElementById('cancelBtn');
            this.changePhoneBtn = document.getElementById('changePhoneBtn');
            this.modal = document.getElementById('phoneChangeModal');

            this.verificationMethod = 'sms';

            this.initEvents();
        }

        initEvents() {
            this.changePhoneBtn.addEventListener('click', () => this.showModal());
            this.cancelBtn.addEventListener('click', () => this.hideModal());

            this.smsBtn.addEventListener('click', () => this.selectMethod('sms'));
            this.zaloBtn.addEventListener('click', () => this.selectMethod('zalo'));

            this.otpInput.addEventListener('input', () => this.toggleConfirmButton());

            this.confirmBtn.addEventListener('click', () => {
                if (this.confirmBtn.disabled) return;
                this.hideModal();
            });
        }

      showModal() {
            this.modal.classList.remove('pointer-events-none');
            this.modal.classList.remove('opacity-100');
            this.modal.classList.add('opacity-0');

            requestAnimationFrame(() => {
                this.modal.classList.remove('opacity-0');
                this.modal.classList.add('opacity-100');
            });
        }

        hideModal() {
            this.modal.classList.remove('opacity-100');
            this.modal.classList.add('opacity-0');

            setTimeout(() => {
                this.modal.classList.add('pointer-events-none');
                this.phoneInput.value = '';
                this.otpInput.value = '';
                this.confirmBtn.disabled = true;
                this.confirmBtn.classList.add('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.remove('bg-blue-500', 'text-white', 'cursor-pointer');
            }, 500);
        }

        selectMethod(method) {
            this.verificationMethod = method;

            if (method === 'sms') {
                this.smsBtn.classList.add('border-blue-500', 'bg-blue-50');
                this.zaloBtn.classList.remove('border-blue-500', 'bg-blue-50');
            } else {
                this.zaloBtn.classList.add('border-blue-500', 'bg-blue-50');
                this.smsBtn.classList.remove('border-blue-500', 'bg-blue-50');
            }
        }

        toggleConfirmButton() {
            if (this.otpInput.value.length === 6) {
                this.confirmBtn.disabled = false;
                this.confirmBtn.classList.remove('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.add('bg-blue-500', 'text-white', 'cursor-pointer');
            } else {
                this.confirmBtn.disabled = true;
                this.confirmBtn.classList.add('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.remove('bg-blue-500', 'text-white', 'cursor-pointer');
            }
        }
    }


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
            this.modal.classList.remove('pointer-events-none');
            this.modal.classList.remove('opacity-100');
            this.modal.classList.add('opacity-0');
            requestAnimationFrame(() => {
                this.modal.classList.remove('opacity-0');
                this.modal.classList.add('opacity-100');
            });
        }

        hideModal() {
            this.modal.classList.remove('opacity-100');
            this.modal.classList.add('opacity-0');

            setTimeout(() => {
                this.modal.classList.add('pointer-events-none');

                this.phoneInput.value = '';
                this.otpInput.value = '';

                this.confirmBtn.disabled = true;
                this.confirmBtn.classList.add('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.remove('bg-blue-500', 'text-white', 'cursor-pointer');
            }, 500);
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
            if (this.otpInput.value.length === 6) {
                this.confirmBtn.disabled = false;
                this.confirmBtn.classList.remove('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.add('bg-blue-500', 'text-white', 'cursor-pointer');
            } else {
                this.confirmBtn.disabled = true;
                this.confirmBtn.classList.add('bg-gray-200', 'text-gray-700', 'cursor-not-allowed');
                this.confirmBtn.classList.remove('bg-blue-500', 'text-white', 'cursor-pointer');
            }
        }
    }
    document.getElementById('changeMailBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const modal = document.getElementById('emailChangeModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
    });
    
    document.getElementById('emailCancelBtn').addEventListener('click', function() {
        const modal = document.getElementById('emailChangeModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.classList.remove('bg-black');
    });
    
    document.getElementById('changePhoneBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const modal = document.getElementById('phoneChangeModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
    });
    
    document.getElementById('cancelBtn').addEventListener('click', function() {
        const modal = document.getElementById('phoneChangeModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.classList.remove('bg-black');
    });
   document.addEventListener('DOMContentLoaded', () => {
        new EmailVerification(); 
      
    });
     document.addEventListener('DOMContentLoaded', () => {
       
        new PhoneVerification();
    });