(function ($) {
    'use strict';

    $(function () {
        $('[data-stock]').each(function () {
            const $row = $(this);
            const stock = parseInt($row.data('stock'), 10);
            const min = parseInt($row.data('min'), 10);

            if (Number.isFinite(stock) && Number.isFinite(min) && stock <= min) {
                $row.addClass('table-warning');
            }
        });

        const $lowStockCard = $('#low-stock-card');
        if ($lowStockCard.find('li').length > 0) {
            $lowStockCard.addClass('pulse');
        }
    });
})(jQuery);
