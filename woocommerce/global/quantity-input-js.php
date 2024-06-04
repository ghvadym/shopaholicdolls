<script type="text/javascript">
    (function($){
        $(document).ready(function() {
            $( "body" ).on( "click", ".td-quantity-button", function(e) {
                var $this = $(this);
                var $input = $this.parent().find('input');
                var $quantity = $input.val();
                var $new_quantity = 0;
                if ($this.hasClass('plus')) {
                    var $new_quantity = parseFloat($quantity) + 1;
                } else {
                    if ($quantity > 0) {
                        var $new_quantity = parseFloat($quantity) - 1;
                        if( $new_quantity == 0){
                            $this.closest('.cart_item').find('.remove:first').click();
                        }
                    }
                }
                $( "button[name='update_cart']" ).prop("disabled", false);

                $input.val($new_quantity);
            });
        });
    })(jQuery);
</script>