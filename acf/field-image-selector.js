(function ($, undefined) {
    var Field = acf.Field.extend({
        type: 'wp_custom_acf_image_selector',
        events: {
            'click input[type="radio"]': 'onClick'
        },
        $control: function () {
            return this.$('.wp-custom-acf-image-selector');
        },
        $input: function () {
            return this.$('input:checked');
        },
        $inputText: function () {
            return this.$('input[type="text"]');
        },
        getValue: function () {
            return this.$input().val();
        },
        onClick: function (e, $el) {
            // vars
            var $label = $el.parent('label');

            // remove previous selected
            this.$('.selected').removeClass('selected');

            // add active class
            $label.addClass('selected');
        }
    });
    acf.registerFieldType(Field);

    acf.registerConditionForFieldType('selectEqualTo', 'wp_custom_acf_image_selector');
})(jQuery);
