/**
 * Sidai Resort — Admin Dashboard Configuration
 */
const AdminConfig = Object.freeze({
    apiBase: '',
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
    currency: 'KES',
    dateFormat: { day: '2-digit', month: 'short', year: 'numeric' },
    roles: {
        super_admin: 'Super Admin',
        manager: 'Manager',
        receptionist: 'Receptionist',
        finance: 'Finance',
        kitchen: 'Kitchen',
        housekeeping: 'Housekeeping',
    },
    statusColors: {
        pending: 'badge-pending',
        confirmed: 'badge-confirmed',
        paid: 'badge-paid',
        completed: 'badge-completed',
        cancelled: 'badge-cancelled',
        failed: 'badge-failed',
        active: 'badge-active',
        inactive: 'badge-inactive',
        partial: 'badge-partial',
        checked_in: 'badge-checked_in',
        checked_out: 'badge-checked_out',
        unpaid: 'badge-unpaid',
        preparing: 'badge-preparing',
        ready: 'badge-ready',
        delivered: 'badge-delivered',
    },
});

if (typeof window !== 'undefined') {
    window.AdminConfig = AdminConfig;
}
