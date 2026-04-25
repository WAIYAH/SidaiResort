/**
 * Sidai Resort — Admin Dashboard JavaScript
 */
(() => {
    'use strict';

    const qs = (s, c = document) => c.querySelector(s);
    const qsa = (s, c = document) => Array.from(c.querySelectorAll(s));

    /* ━━ TOAST NOTIFICATIONS ━━ */
    const showToast = (message, type = 'success', duration = 4000) => {
        let container = qs('#admin-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'admin-toast-container';
            container.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:999;display:grid;gap:0.5rem;max-width:22rem;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const colors = {
            success: 'background:#065F46;color:#D1FAE5;border-left:4px solid #34D399;',
            error: 'background:#991B1B;color:#FEE2E2;border-left:4px solid #F87171;',
            info: 'background:#1E40AF;color:#DBEAFE;border-left:4px solid #60A5FA;',
            warning: 'background:#92400E;color:#FEF3C7;border-left:4px solid #FBBF24;',
        };

        toast.style.cssText = `${colors[type] || colors.info}padding:0.85rem 1.1rem;border-radius:0.5rem;font-size:0.875rem;font-family:Montserrat,sans-serif;box-shadow:0 8px 24px rgba(0,0,0,0.2);animation:toastSlideIn 0.3s ease-out;`;
        toast.textContent = message;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'toastSlideOut 0.3s ease-in forwards';
            setTimeout(() => toast.remove(), 350);
        }, duration);
    };

    // Inject toast animations
    if (!qs('#admin-toast-styles')) {
        const style = document.createElement('style');
        style.id = 'admin-toast-styles';
        style.textContent = `
            @keyframes toastSlideIn { from { opacity:0; transform:translateX(100px); } to { opacity:1; transform:translateX(0); } }
            @keyframes toastSlideOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(100px); } }
        `;
        document.head.appendChild(style);
    }

    /* ━━ MODAL SYSTEM ━━ */
    const openModal = (modalId) => {
        const backdrop = qs(`#${modalId}`);
        if (backdrop) {
            backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };

    const closeModal = (modalId) => {
        const backdrop = qs(`#${modalId}`);
        if (backdrop) {
            backdrop.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    /* ━━ CONFIRM DELETE ━━ */
    const confirmAction = (message, callback) => {
        if (window.confirm(message)) {
            callback();
        }
    };

    /* ━━ FETCH HELPER ━━ */
    const adminFetch = async (url, options = {}) => {
        const defaults = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.AdminConfig?.csrfToken || '',
            },
        };

        const merged = { ...defaults, ...options };
        if (options.headers) {
            merged.headers = { ...defaults.headers, ...options.headers };
        }

        try {
            const response = await fetch(url, merged);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }

            return data;
        } catch (error) {
            showToast(error.message || 'An error occurred', 'error');
            throw error;
        }
    };

    /* ━━ TABLE SEARCH ━━ */
    const initTableSearch = () => {
        qsa('[data-table-search]').forEach((input) => {
            const tableId = input.dataset.tableSearch;
            const table = qs(`#${tableId}`);
            if (!table) return;

            input.addEventListener('input', () => {
                const query = input.value.toLowerCase();
                const rows = qsa('tbody tr', table);

                rows.forEach((row) => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        });
    };

    /* ━━ TABLE STATUS FILTER ━━ */
    const initStatusFilter = () => {
        qsa('[data-status-filter]').forEach((select) => {
            const tableId = select.dataset.statusFilter;
            const table = qs(`#${tableId}`);
            if (!table) return;

            select.addEventListener('change', () => {
                const status = select.value.toLowerCase();
                const rows = qsa('tbody tr', table);

                rows.forEach((row) => {
                    if (!status) {
                        row.style.display = '';
                        return;
                    }

                    const badges = qsa('.badge', row);
                    const match = badges.some((badge) =>
                        badge.textContent.trim().toLowerCase() === status
                    );
                    row.style.display = match ? '' : 'none';
                });
            });
        });
    };

    /* ━━ AUTO-DISMISS ALERTS ━━ */
    const initAlertDismiss = () => {
        qsa('.admin-alert[data-auto-dismiss]').forEach((alert) => {
            const delay = parseInt(alert.dataset.autoDismiss, 10) || 5000;
            setTimeout(() => {
                alert.style.animation = 'toastSlideOut 0.3s ease-in forwards';
                setTimeout(() => alert.remove(), 350);
            }, delay);
        });
    };

    /* ━━ MODAL EVENT DELEGATION ━━ */
    const initModalTriggers = () => {
        document.addEventListener('click', (e) => {
            // Open modal
            const openTrigger = e.target.closest('[data-modal-open]');
            if (openTrigger) {
                e.preventDefault();
                openModal(openTrigger.dataset.modalOpen);
                return;
            }

            // Close modal
            const closeTrigger = e.target.closest('[data-modal-close]');
            if (closeTrigger) {
                e.preventDefault();
                const backdrop = closeTrigger.closest('.admin-modal-backdrop');
                if (backdrop) {
                    closeModal(backdrop.id);
                }
                return;
            }

            // Close on backdrop click
            if (e.target.classList.contains('admin-modal-backdrop')) {
                closeModal(e.target.id);
            }
        });
    };

    /* ━━ SIDEBAR TOGGLE FOR MOBILE ━━ */
    const initSidebarToggle = () => {
        const toggle = qs('[data-sidebar-toggle]');
        const sidebar = qs('.admin-sidebar');
        if (!toggle || !sidebar) return;

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('expanded');
        });
    };

    /* ━━ FORMAT CURRENCY ━━ */
    const formatKES = (amount) => {
        const num = parseFloat(amount) || 0;
        return 'KES ' + num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    /* ━━ INIT ━━ */
    const init = () => {
        initTableSearch();
        initStatusFilter();
        initAlertDismiss();
        initModalTriggers();
        initSidebarToggle();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }

    // Expose utilities globally
    window.AdminUtils = {
        showToast,
        openModal,
        closeModal,
        confirmAction,
        adminFetch,
        formatKES,
    };

    // Global sidebar toggle for mobile
    window.toggleAdminSidebar = () => {
        const sidebar = document.querySelector('.admin-sidebar');
        const overlay = document.querySelector('.admin-sidebar-overlay');
        if (!sidebar) return;

        const isOpen = sidebar.classList.toggle('open');
        if (overlay) {
            overlay.classList.toggle('active', isOpen);
        }
        document.body.style.overflow = isOpen ? 'hidden' : '';
    };
})();
