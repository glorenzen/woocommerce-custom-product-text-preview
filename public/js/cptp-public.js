jQuery(document).ready(function($) {
    function checkVariationsSelected() {
        let allSelected = true;
        $('.variations select').each(function() {
            if ($(this).val() === '') {
                allSelected = false;
            }
        });
        return allSelected;
    }

    function toggleCustomTextField() {
        if (checkVariationsSelected()) {
            $('.cptp-product-custom-text').show();
        } else {
            $('.cptp-product-custom-text').hide();
        }
    }

    // Check variations on change
    $('.variations select').change(function() {
        toggleCustomTextField();
    });

    // Initial check
    toggleCustomTextField();
});