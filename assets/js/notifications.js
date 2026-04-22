/**
 * LMS Toast Notification System
 * Inspired by Sileo - Physics-based toast notifications
 * Pure JavaScript implementation for CodeIgniter 3
 */

(function(window) {
    'use strict';

    const Toaster = {
        container: null,
        defaultOptions: {
            position: 'top-right',
            duration: 4000,
            maxToasts: 5,
            animation: {
                enter: 'slide-in',
                exit: 'slide-out'
            }
        },
        activeToasts: [],

        init: function(options) {
            this.defaultOptions = Object.assign(this.defaultOptions, options || {});
            this.createContainer();
            this.injectStyles();
        },

        createContainer: function() {
            if (this.container) return;

            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = `toast-container toast-${this.defaultOptions.position}`;
            document.body.appendChild(this.container);
        },

        injectStyles: function() {
            if (document.getElementById('toast-styles')) return;

            const styles = `
                .toast-container {
                    position: fixed;
                    z-index: 9999;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    pointer-events: none;
                }

                .toast-container.toast-top-right {
                    top: 20px;
                    right: 20px;
                }

                .toast-container.toast-top-left {
                    top: 20px;
                    left: 20px;
                }

                .toast-container.toast-top-center {
                    top: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                }

                .toast-container.toast-bottom-right {
                    bottom: 20px;
                    right: 20px;
                    flex-direction: column-reverse;
                }

                .toast-container.toast-bottom-left {
                    bottom: 20px;
                    left: 20px;
                    flex-direction: column-reverse;
                }

                .toast-container.toast-bottom-center {
                    bottom: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    flex-direction: column-reverse;
                }

                .toast-notification {
                    background: white;
                    border-radius: 16px;
                    padding: 16px 20px;
                    min-width: 320px;
                    max-width: 420px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1);
                    display: flex;
                    align-items: flex-start;
                    gap: 14px;
                    pointer-events: all;
                    position: relative;
                    overflow: hidden;
                    transform-origin: center;
                    will-change: transform, opacity;
                }

                .toast-notification.toast-success {
                    border-left: 4px solid #10b981;
                }

                .toast-notification.toast-error {
                    border-left: 4px solid #ef4444;
                }

                .toast-notification.toast-warning {
                    border-left: 4px solid #f59e0b;
                }

                .toast-notification.toast-info {
                    border-left: 4px solid #3b82f6;
                }

                .toast-icon {
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    font-size: 18px;
                }

                .toast-success .toast-icon {
                    background: #d1fae5;
                    color: #059669;
                }

                .toast-error .toast-icon {
                    background: #fee2e2;
                    color: #dc2626;
                }

                .toast-warning .toast-icon {
                    background: #fef3c7;
                    color: #d97706;
                }

                .toast-info .toast-icon {
                    background: #dbeafe;
                    color: #2563eb;
                }

                .toast-content {
                    flex: 1;
                    min-width: 0;
                }

                .toast-title {
                    font-weight: 600;
                    font-size: 15px;
                    color: #1f2937;
                    margin-bottom: 4px;
                    line-height: 1.4;
                }

                .toast-message {
                    font-size: 14px;
                    color: #6b7280;
                    line-height: 1.5;
                }

                .toast-close {
                    width: 28px;
                    height: 28px;
                    border: none;
                    background: transparent;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    color: #9ca3af;
                    font-size: 18px;
                    transition: all 0.2s ease;
                    flex-shrink: 0;
                    margin: -4px -8px -4px 0;
                }

                .toast-close:hover {
                    background: #f3f4f6;
                    color: #4b5563;
                }

                .toast-progress {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background: currentColor;
                    opacity: 0.3;
                    transition: width linear;
                }

                .toast-success .toast-progress { background: #10b981; }
                .toast-error .toast-progress { background: #ef4444; }
                .toast-warning .toast-progress { background: #f59e0b; }
                .toast-info .toast-progress { background: #3b82f6; }

                /* Physics-based animations */
                @keyframes toastSlideIn {
                    0% {
                        opacity: 0;
                        transform: translateX(100%) scale(0.9);
                    }
                    60% {
                        transform: translateX(-8px) scale(1.02);
                    }
                    100% {
                        opacity: 1;
                        transform: translateX(0) scale(1);
                    }
                }

                @keyframes toastSlideOut {
                    0% {
                        opacity: 1;
                        transform: translateX(0) scale(1);
                    }
                    40% {
                        transform: translateX(8px) scale(0.98);
                    }
                    100% {
                        opacity: 0;
                        transform: translateX(100%) scale(0.9);
                    }
                }

                @keyframes toastSlideInLeft {
                    0% {
                        opacity: 0;
                        transform: translateX(-100%) scale(0.9);
                    }
                    60% {
                        transform: translateX(8px) scale(1.02);
                    }
                    100% {
                        opacity: 1;
                        transform: translateX(0) scale(1);
                    }
                }

                @keyframes toastSlideOutLeft {
                    0% {
                        opacity: 1;
                        transform: translateX(0) scale(1);
                    }
                    40% {
                        transform: translateX(-8px) scale(0.98);
                    }
                    100% {
                        opacity: 0;
                        transform: translateX(-100%) scale(0.9);
                    }
                }

                @keyframes toastSlideInCenter {
                    0% {
                        opacity: 0;
                        transform: translateY(-20px) scale(0.9);
                    }
                    60% {
                        transform: translateY(4px) scale(1.02);
                    }
                    100% {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                @keyframes toastSlideOutCenter {
                    0% {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                    100% {
                        opacity: 0;
                        transform: translateY(-20px) scale(0.9);
                    }
                }

                .toast-notification.entering {
                    animation: toastSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                }

                .toast-notification.entering-left {
                    animation: toastSlideInLeft 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                }

                .toast-notification.entering-center {
                    animation: toastSlideInCenter 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                }

                .toast-notification.exiting {
                    animation: toastSlideOut 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
                }

                .toast-notification.exiting-left {
                    animation: toastSlideOutLeft 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
                }

                .toast-notification.exiting-center {
                    animation: toastSlideOutCenter 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
                }

                @media (max-width: 640px) {
                    .toast-container {
                        left: 16px !important;
                        right: 16px !important;
                        top: 16px !important;
                        transform: none !important;
                    }

                    .toast-notification {
                        min-width: auto;
                        max-width: none;
                        width: 100%;
                    }
                }
            `;

            const styleSheet = document.createElement('style');
            styleSheet.id = 'toast-styles';
            styleSheet.textContent = styles;
            document.head.appendChild(styleSheet);
        },

        getIcon: function(type) {
            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠',
                info: 'ℹ'
            };
            return icons[type] || icons.info;
        },

        show: function(message, options) {
            options = Object.assign({
                type: 'info',
                title: null,
                duration: this.defaultOptions.duration,
                position: this.defaultOptions.position
            }, options || {});

            this.createContainer();

            // Remove oldest toast if max reached
            if (this.activeToasts.length >= this.defaultOptions.maxToasts) {
                this.remove(this.activeToasts[0]);
            }

            const toast = this.createToast(message, options);
            this.container.appendChild(toast);
            this.activeToasts.push(toast);

            // Trigger enter animation
            requestAnimationFrame(() => {
                const enterClass = options.position.includes('left') ? 'entering-left' :
                                  options.position.includes('center') ? 'entering-center' : 'entering';
                toast.classList.add(enterClass);
            });

            // Auto dismiss
            if (options.duration > 0) {
                const progressBar = toast.querySelector('.toast-progress');
                if (progressBar) {
                    requestAnimationFrame(() => {
                        progressBar.style.width = '0%';
                        progressBar.style.transition = `width ${options.duration}ms linear`;
                    });
                }

                toast._timeout = setTimeout(() => {
                    this.remove(toast);
                }, options.duration);
            }

            return toast;
        },

        createToast: function(message, options) {
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${options.type}`;

            const title = options.title || this.getDefaultTitle(options.type);
            const icon = this.getIcon(options.type);

            toast.innerHTML = `
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${this.escapeHtml(title)}</div>
                    <div class="toast-message">${this.escapeHtml(message)}</div>
                </div>
                <button class="toast-close" aria-label="Close">×</button>
                <div class="toast-progress" style="width: 100%;"></div>
            `;

            // Close button handler
            toast.querySelector('.toast-close').addEventListener('click', () => {
                this.remove(toast);
            });

            // Pause on hover
            toast.addEventListener('mouseenter', () => {
                if (toast._timeout) {
                    clearTimeout(toast._timeout);
                    toast._timeout = null;
                }
                const progressBar = toast.querySelector('.toast-progress');
                if (progressBar) {
                    const computedStyle = window.getComputedStyle(progressBar);
                    const width = computedStyle.width;
                    progressBar.style.transition = 'none';
                    progressBar.style.width = width;
                }
            });

            toast.addEventListener('mouseleave', () => {
                const remainingTime = 1000; // Continue for 1 second after mouse leave
                const progressBar = toast.querySelector('.toast-progress');
                if (progressBar) {
                    progressBar.style.transition = `width ${remainingTime}ms linear`;
                    progressBar.style.width = '0%';
                }
                toast._timeout = setTimeout(() => {
                    this.remove(toast);
                }, remainingTime);
            });

            return toast;
        },

        remove: function(toast) {
            if (!toast || toast._isRemoving) return;
            toast._isRemoving = true;

            if (toast._timeout) {
                clearTimeout(toast._timeout);
            }

            const position = this.defaultOptions.position;
            const exitClass = position.includes('left') ? 'exiting-left' :
                             position.includes('center') ? 'exiting-center' : 'exiting';
            toast.classList.add(exitClass);

            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
                const index = this.activeToasts.indexOf(toast);
                if (index > -1) {
                    this.activeToasts.splice(index, 1);
                }
            }, 300);
        },

        removeAll: function() {
            [...this.activeToasts].forEach(toast => this.remove(toast));
        },

        getDefaultTitle: function(type) {
            const titles = {
                success: 'Success',
                error: 'Error',
                warning: 'Warning',
                info: 'Information'
            };
            return titles[type] || 'Notification';
        },

        escapeHtml: function(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        // Convenience methods
        success: function(message, options) {
            return this.show(message, Object.assign({}, options, { type: 'success' }));
        },

        error: function(message, options) {
            return this.show(message, Object.assign({}, options, { type: 'error' }));
        },

        warning: function(message, options) {
            return this.show(message, Object.assign({}, options, { type: 'warning' }));
        },

        info: function(message, options) {
            return this.show(message, Object.assign({}, options, { type: 'info' }));
        }
    };

    // Auto-initialize from flashdata
    document.addEventListener('DOMContentLoaded', function() {
        // Convert existing PHP flashdata toasts
        const flashMessages = [];

        // Check for data attributes on body or a hidden element
        const flashData = document.getElementById('flash-data');
        if (flashData) {
            const messages = JSON.parse(flashData.dataset.messages || '[]');
            messages.forEach(msg => {
                flashMessages.push(msg);
            });
        }

        // Convert old alerts if present
        const oldAlerts = document.querySelectorAll('.alert-modern');
        oldAlerts.forEach(alert => {
            const isSuccess = alert.classList.contains('alert-success-modern');
            const isError = alert.classList.contains('alert-danger-modern');
            const text = alert.textContent.trim();

            flashMessages.push({
                type: isSuccess ? 'success' : isError ? 'error' : 'info',
                message: text,
                title: isSuccess ? 'Success' : isError ? 'Error' : 'Notification'
            });

            // Hide old alert
            alert.style.display = 'none';
        });

        // Show flash messages
        if (flashMessages.length > 0) {
            Toaster.init({ position: 'top-right' });
            flashMessages.forEach((msg, index) => {
                setTimeout(() => {
                    Toaster.show(msg.message, {
                        type: msg.type,
                        title: msg.title,
                        duration: 4000
                    });
                }, index * 150);
            });
        } else {
            Toaster.init({ position: 'top-right' });
        }
    });

    // Expose global API
    window.Toaster = Toaster;
    window.toast = Toaster;

})(window);
