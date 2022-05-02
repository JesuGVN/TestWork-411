jQuery(document).ready(function() {

    var $ = jQuery;
    $('#create-product-form input[type="file"][preview-target-id]').on('change', function() {
        var input = $(this)
        if (!window.FileReader) return false // check for browser support
        if (input[0].files && input[0].files[0]) {
            var reader = new FileReader()
            reader.onload = function (e) {
                var target = $('#' + input.attr('preview-target-id'))
                var background_image = 'url(' + e.target.result + ')'
                target.css('background-image', background_image)
                target.parent().show()
            }
            reader.readAsDataURL(input[0].files[0]);
            $('.product-thumbnail .clear').css('display','block');
        }
    })

    let clearButton = $('#submit_cancel');

    clearButton.click(function () {
        let inputs = $('#create-product-form input');

        for(var i = 0; i <= inputs.length - 1; i++) {
            $(inputs[i]).val("");
        }

        $('#thumbnail_preview').css('background-image', 'none');
        jQuery("#create-product-form .product-type select").val("Default");
    });

    let thumbnailClear = $('.product-thumbnail .clear');

    $(thumbnailClear).click(function () {
        $('#thumbnail_preview').css('background-image', 'none');
        $('#thumbnail').val("");
        $(this).css('display','none');
    });
});
