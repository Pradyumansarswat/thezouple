(function () {
    'use strict';

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', markReady);
    } else {
        markReady();
    }

    function markReady() {
        document.documentElement.classList.add('zouple-admin-luxury-ready');
    }
})();
