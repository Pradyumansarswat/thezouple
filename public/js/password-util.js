/**
 * Password Utility Library
 * Handles password visibility toggle and real-time validation
 */

const PasswordUtil = {
    requirements: {
        minLength: 8,
        hasUpperCase: /[A-Z]/,
        hasLowerCase: /[a-z]/,
        hasNumber: /[0-9]/,
        hasSpecialChar: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/
    },

    defaultHintText: 'Your password must be more than 8 characters long. It should contain atleast 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character',

    validatePassword: function(password) {
        return {
            minLength: password.length >= this.requirements.minLength,
            hasUpperCase: this.requirements.hasUpperCase.test(password),
            hasLowerCase: this.requirements.hasLowerCase.test(password),
            hasNumber: this.requirements.hasNumber.test(password),
            hasSpecialChar: this.requirements.hasSpecialChar.test(password)
        };
    },

    isPasswordValid: function(password) {
        const validation = this.validatePassword(password);
        return Object.values(validation).every(function(v) { return v === true; });
    },

    getFieldSelector: function(field) {
        if (field.id) {
            return '#' + field.id;
        }
        if (field.name) {
            return 'input[name="' + field.name + '"]';
        }
        return null;
    },

    ensureStyles: function() {
        if (document.getElementById('password-toggle-styles')) {
            return;
        }

        const style = document.createElement('style');
        style.id = 'password-toggle-styles';
        style.innerHTML = `
            .form-group.has-password-toggle,
            .profileForm.has-password-toggle {
                position: relative;
            }
            .has-password-toggle .form-control[type="password"],
            .has-password-toggle .form-control[type="text"] {
                padding-right: 42px !important;
            }
            .login_form .has-password-toggle .password-toggle-btn,
            .loginn .has-password-toggle .password-toggle-btn {
                top: auto;
                bottom: 10px;
                transform: none;
            }
            .password-toggle-btn {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: #666;
                font-size: 16px;
                padding: 4px;
                z-index: 10;
                line-height: 1;
            }
            .password-toggle-btn:hover {
                color: #111;
            }
            .password-validation-msg {
                font-size: 12px;
                margin-top: 8px;
                padding: 8px 12px;
                border-radius: 4px;
                display: none;
            }
            .password-validation-msg.show {
                display: block;
            }
            .password-validation-msg.invalid {
                background-color: #ffe6e6;
                color: #d32f2f;
                border: 1px solid #d32f2f;
            }
            .password-validation-msg.valid {
                background-color: #e6ffe6;
                color: #2e7d32;
                border: 1px solid #2e7d32;
            }
            .password-static-hint {
                font-size: 12px;
                margin-top: 6px;
                line-height: 1.4;
            }
            .password-static-hint.valid {
                color: #2e7d32 !important;
            }
            .password-static-hint.invalid {
                color: #d32f2f !important;
            }
            .validation-item {
                display: flex;
                align-items: center;
                margin: 4px 0;
            }
            .validation-icon {
                margin-right: 8px;
                font-weight: bold;
            }
            .validation-item.pass .validation-icon::before {
                content: '\\2713';
                color: #2e7d32;
            }
            .validation-item.fail .validation-icon::before {
                content: '\\2717';
                color: #d32f2f;
            }
        `;
        document.head.appendChild(style);
    },

    initTogglePassword: function(fieldSelector) {
        const field = document.querySelector(fieldSelector);
        if (!field || field.dataset.passwordToggleReady === '1') {
            return;
        }

        this.ensureStyles();

        const fieldGroup = field.closest('.form-group') || field.closest('.profileForm') || field.parentElement;
        if (!fieldGroup) {
            return;
        }

        fieldGroup.classList.add('has-password-toggle');

        let toggleBtn = fieldGroup.querySelector('.password-toggle-btn');
        if (!toggleBtn) {
            toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'password-toggle-btn';
            toggleBtn.innerHTML = '<i class="fa fa-eye"></i>';
            toggleBtn.title = 'Show/Hide Password';
            toggleBtn.setAttribute('tabindex', '-1');
            fieldGroup.appendChild(toggleBtn);

            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const type = field.type === 'password' ? 'text' : 'password';
                field.type = type;
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.className = type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
                }
            });
        }

        field.dataset.passwordToggleReady = '1';
    },

    initStaticHint: function(fieldSelector, hintSelector) {
        const field = document.querySelector(fieldSelector);
        const hint = document.querySelector(hintSelector);
        if (!field || !hint) {
            return;
        }

        if (!hint.dataset.originalText) {
            hint.dataset.originalText = hint.textContent.trim() || this.defaultHintText;
        }

        const updateHint = () => {
            const password = field.value;
            const isValid = this.isPasswordValid(password);

            if (!password.length) {
                hint.style.display = '';
                hint.classList.remove('valid');
                hint.classList.add('invalid');
                hint.textContent = hint.dataset.originalText;
                return;
            }

            if (isValid) {
                hint.style.display = '';
                hint.classList.remove('invalid');
                hint.classList.add('valid');
                hint.textContent = '\u2713 Password meets all requirements.';
                return;
            }

            hint.style.display = '';
            hint.classList.remove('valid');
            hint.classList.add('invalid');
            hint.textContent = hint.dataset.originalText;
        };

        if (field.dataset.staticHintReady !== '1') {
            field.addEventListener('input', updateHint);
            field.addEventListener('blur', updateHint);
            field.dataset.staticHintReady = '1';
        }

        updateHint();
    },

    initPasswordValidation: function(fieldSelector, feedbackSelector) {
        const field = document.querySelector(fieldSelector);
        if (!field) {
            return;
        }

        this.ensureStyles();

        let feedbackElement = feedbackSelector ? document.querySelector(feedbackSelector) : null;
        if (!feedbackElement) {
            const fieldGroup = field.closest('.form-group') || field.closest('.profileForm') || field.parentElement;
            feedbackElement = fieldGroup.querySelector('.password-validation-msg');
            if (!feedbackElement) {
                feedbackElement = document.createElement('div');
                feedbackElement.className = 'password-validation-msg';
                fieldGroup.appendChild(feedbackElement);
            }
        }

        const updateValidation = () => {
            const password = field.value;

            if (!password.length) {
                feedbackElement.classList.remove('show', 'valid', 'invalid');
                feedbackElement.innerHTML = '';
                return;
            }

            const validation = this.validatePassword(password);
            const isValid = this.isPasswordValid(password);

            let html = '<div style="margin-bottom: 4px; font-weight: bold;">';
            html += isValid ? 'Password is strong' : 'Password must contain:';
            html += '</div>';
            html += '<div class="validation-item ' + (validation.minLength ? 'pass' : 'fail') + '"><span class="validation-icon"></span><span>At least 8 characters</span></div>';
            html += '<div class="validation-item ' + (validation.hasUpperCase ? 'pass' : 'fail') + '"><span class="validation-icon"></span><span>1 Uppercase letter (A-Z)</span></div>';
            html += '<div class="validation-item ' + (validation.hasLowerCase ? 'pass' : 'fail') + '"><span class="validation-icon"></span><span>1 Lowercase letter (a-z)</span></div>';
            html += '<div class="validation-item ' + (validation.hasNumber ? 'pass' : 'fail') + '"><span class="validation-icon"></span><span>1 Number (0-9)</span></div>';
            html += '<div class="validation-item ' + (validation.hasSpecialChar ? 'pass' : 'fail') + '"><span class="validation-icon"></span><span>1 Special character (!@#$%^&*)</span></div>';

            feedbackElement.innerHTML = html;
            feedbackElement.classList.add('show');
            feedbackElement.classList.toggle('valid', isValid);
            feedbackElement.classList.toggle('invalid', !isValid);
        };

        if (field.dataset.validationReady !== '1') {
            field.addEventListener('input', updateValidation);
            field.addEventListener('blur', updateValidation);
            field.dataset.validationReady = '1';
        }

        updateValidation();
    },

    initPassword: function(fieldSelector, withValidation, feedbackSelector, hintSelector) {
        this.initTogglePassword(fieldSelector);
        if (withValidation) {
            this.initPasswordValidation(fieldSelector, feedbackSelector);
        }
        if (hintSelector) {
            this.initStaticHint(fieldSelector, hintSelector);
        }
    },

    initAll: function() {
        const self = this;

        document.querySelectorAll('input[type="password"].password-field, input[type="password"].password-field-full, input[type="password"].password-field-validate').forEach(function(field) {
            const selector = self.getFieldSelector(field);
            if (!selector) {
                return;
            }

            const withValidation = field.classList.contains('password-field-full') || field.classList.contains('password-field-validate');
            const hintId = field.getAttribute('data-password-hint');
            const hintSelector = hintId ? '#' + hintId : null;

            self.initPassword(selector, withValidation, null, hintSelector);
        });

        document.querySelectorAll('input[type="password"]:not(.password-field):not(.password-field-full):not(.password-field-validate)').forEach(function(field) {
            const form = field.closest('form');
            if (!form) {
                return;
            }
            field.classList.add('password-field');
            const selector = self.getFieldSelector(field);
            if (selector) {
                self.initTogglePassword(selector);
                if (field.name === 'password' || field.name === 'newpassword') {
                    self.initPasswordValidation(selector);
                }
            }
        });
    }
};

function initPasswordFields() {
    PasswordUtil.initAll();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPasswordFields);
} else {
    initPasswordFields();
}
