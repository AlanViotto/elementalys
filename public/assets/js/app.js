(function ($) {
    'use strict';

    $(function () {
        $('[data-stock]').each(function () {
            const $element = $(this);
            const stock = parseInt($element.data('stock'), 10);
            const min = parseInt($element.data('min'), 10);

            if (Number.isFinite(stock) && Number.isFinite(min) && stock <= min) {
                $element.addClass('table-warning');
            }
        });

        const $lowStockCard = $('#low-stock-card');
        if ($lowStockCard.find('[data-stock]').length > 0 || $lowStockCard.find('li').length > 0) {
            $lowStockCard.addClass('pulse');
        }

        const $typeSelect = $('.product-type-select');
        const $conditionalFields = $('.conditional-field');

        function toggleConditionalFields() {
            const currentType = ($typeSelect.val() || '').trim();

            $conditionalFields.each(function () {
                const $field = $(this);
                const requiredType = ($field.data('type') || '').trim();
                const shouldShow = requiredType === currentType;

                $field.toggleClass('is-visible', shouldShow);
                $field.find('select, input, textarea').prop('required', shouldShow);
            });
        }

        if ($typeSelect.length) {
            toggleConditionalFields();
            $typeSelect.on('change', toggleConditionalFields);
        }
    });
})(jQuery);
