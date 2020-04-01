(function($) {

    $('.modal-edit').on('click', function() {
        var url = $(this).attr('data-url');

        $.get(url, function(data) {
            $('.modal-body').html(data);
        })
    });

    $(".modal").on('click', '.submit-todo', function(e){
        e.preventDefault();
        $(this).closest("form").submit();
    });

})(jQuery);
