import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
window.Swiper = Swiper;
import 'bootstrap';
import '@popperjs/core';
import Swal from 'sweetalert2';

window.Swal = Swal;


$(document).ready(function () {


    $('.select2').select2({
        placeholder: 'انتخاب کنید',
        width: '100%',
        dropdownCssClass: 'select2-dropdown-custom',
        dir: 'rtl',
    });

    // Select all input, select, and textarea elements
    const inputs = document.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        // Set autocomplete to "off"


        // Add a random attribute to confuse the browser
        input.setAttribute('data-autocomplete', 'off');

        // For sensitive fields, use "new-password"
        if (input.type === 'password' || input.type === 'text') {
            input.setAttribute('autocomplete', 'new-password');
        }

    });
});
window.showMessage = function (message, type) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: type,
        title: message
    });
};

(() => {
    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const postJson = async (url) => {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf(),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: '{}',
        });
        if (!res.ok) {
            throw new Error('request_failed');
        }
        return res.json();
    };

    const updateBadge = (unread) => {
        document.querySelectorAll('[data-sp-notify-badge]').forEach((badge) => {
            const n = Number(unread) || 0;
            badge.textContent = n > 9 ? '9+' : String(n);
            badge.classList.toggle('is-hidden', n <= 0);
        });
        document.querySelectorAll('[data-sp-notify-readall]').forEach((btn) => {
            btn.classList.toggle('is-hidden', (Number(unread) || 0) <= 0);
        });
    };

    const markRowRead = (row) => {
        if (!row) return;
        row.classList.remove('is-unread');
        const meta = row.querySelector('.sp-dash__row-meta');
        if (meta) {
            meta.innerHTML = '<span class="sp-dash__chip is-done">خوانده‌شده</span>';
        }
    };

    const navRoot = document.querySelector('[data-sp-nav]');
    const navToggle = document.querySelector('[data-sp-nav-toggle]');
    const navScrim = document.querySelector('[data-sp-nav-scrim]');

    const setNavOpen = (open) => {
        if (!navRoot) return;
        navRoot.classList.toggle('is-open', open);
        document.body.classList.toggle('is-nav-lock', open);
        if (navToggle) {
            navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            navToggle.setAttribute('aria-label', open ? 'بستن منو' : 'باز کردن منو');
        }
        if (navScrim) {
            if (open) navScrim.removeAttribute('hidden');
            else navScrim.setAttribute('hidden', '');
        }
        if (!open) {
            document.querySelectorAll('.sp-menu__item.is-expanded').forEach((item) => {
                item.classList.remove('is-expanded');
                const trigger = item.querySelector('[data-sp-menu-section]');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            });
        }
    };

    const closeOpenMenus = (except = null) => {
        document.querySelectorAll('.sp-nav__notify.is-open, .sp-nav__account.is-open').forEach((el) => {
            if (except && el === except) return;
            el.classList.remove('is-open');
            const toggle = el.querySelector('[aria-expanded]');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        });
    };

    document.addEventListener('click', async (event) => {
        const navToggleBtn = event.target.closest('[data-sp-nav-toggle]');
        if (navToggleBtn) {
            event.preventDefault();
            const opening = !navRoot?.classList.contains('is-open');
            closeOpenMenus();
            setNavOpen(opening);
            return;
        }

        if (event.target.closest('[data-sp-nav-close]') || event.target.closest('[data-sp-nav-scrim]')) {
            event.preventDefault();
            setNavOpen(false);
            return;
        }

        const sectionBtn = event.target.closest('[data-sp-menu-section]');
        if (sectionBtn && window.matchMedia('(max-width: 767px)').matches) {
            event.preventDefault();
            const item = sectionBtn.closest('.sp-menu__item');
            if (!item) return;
            const opening = !item.classList.contains('is-expanded');
            item.parentElement?.querySelectorAll('.sp-menu__item.is-expanded').forEach((other) => {
                if (other !== item) {
                    other.classList.remove('is-expanded');
                    other.querySelector('[data-sp-menu-section]')?.setAttribute('aria-expanded', 'false');
                }
            });
            item.classList.toggle('is-expanded', opening);
            sectionBtn.setAttribute('aria-expanded', opening ? 'true' : 'false');
            return;
        }

        const notifyToggle = event.target.closest('[data-sp-notify-toggle]');
        if (notifyToggle) {
            event.preventDefault();
            const wrap = notifyToggle.closest('[data-sp-notify]');
            const opening = !wrap.classList.contains('is-open');
            closeOpenMenus(opening ? wrap : null);
            setNavOpen(false);
            wrap.classList.toggle('is-open', opening);
            notifyToggle.setAttribute('aria-expanded', opening ? 'true' : 'false');
            return;
        }

        const accountToggle = event.target.closest('[data-sp-account-toggle]');
        if (accountToggle) {
            event.preventDefault();
            const wrap = accountToggle.closest('[data-sp-account]');
            const opening = !wrap.classList.contains('is-open');
            closeOpenMenus(opening ? wrap : null);
            wrap.classList.toggle('is-open', opening);
            accountToggle.setAttribute('aria-expanded', opening ? 'true' : 'false');
            return;
        }

        const readAllBtn = event.target.closest('[data-sp-notify-readall]');
        if (readAllBtn) {
            event.preventDefault();
            const url = readAllBtn.getAttribute('data-url');
            if (!url) return;
            try {
                const data = await postJson(url);
                updateBadge(data.unread);
                document.querySelectorAll('[data-sp-notify-item].is-unread').forEach((item) => {
                    item.classList.remove('is-unread');
                    item.setAttribute('data-unread', '0');
                });
                document.querySelectorAll('[data-sp-notify-page-list] .sp-dash__row.is-unread').forEach(markRowRead);
                if (window.showMessage) window.showMessage(data.message || 'انجام شد', 'success');
            } catch (e) {
                if (window.showMessage) window.showMessage('خطا در به‌روزرسانی اعلان‌ها', 'error');
            }
            return;
        }

        const markBtn = event.target.closest('[data-sp-notify-mark]');
        if (markBtn) {
            event.preventDefault();
            const url = markBtn.getAttribute('data-url');
            const row = markBtn.closest('[data-notification-id]');
            if (!url) return;
            try {
                const data = await postJson(url);
                markRowRead(row);
                updateBadge(data.unread);
            } catch (e) {
                if (window.showMessage) window.showMessage('خطا در خواندن اعلان', 'error');
            }
            return;
        }

        const item = event.target.closest('[data-sp-notify-item]');
        if (item) {
            event.preventDefault();
            const href = item.getAttribute('href');
            const unread = item.getAttribute('data-unread') === '1';
            const readUrl = item.getAttribute('data-read-url');
            try {
                if (unread && readUrl) {
                    const data = await postJson(readUrl);
                    item.classList.remove('is-unread');
                    item.setAttribute('data-unread', '0');
                    updateBadge(data.unread);
                }
            } catch (e) {
                // still navigate
            }
            if (href) window.location.href = href;
            return;
        }

        if (!event.target.closest('[data-sp-notify]') && !event.target.closest('[data-sp-account]')) {
            closeOpenMenus();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setNavOpen(false);
            closeOpenMenus();
        }
    });

    window.addEventListener('resize', () => {
        if (window.matchMedia('(min-width: 768px)').matches) {
            setNavOpen(false);
        }
    });
})();
