(function($) {

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }      

    $('.modal-edit').on('click', function() {
        var url = $(this).attr('data-url');

        $.get(url, function(data) {
            console.log(data)
            $('.modal-body').html(data);
        })
    });

    $(".modal").on('click', '.submit-todo', function(e) {
        e.preventDefault();
        $(this).closest("form").submit();
    });

    $('.confirm-todo').on('click', function() {
        $this = $(this);
        $.confirm({
            theme: 'supervan',
            title: 'Done?',
            content: 'Are you done with this?',
            buttons: {
                Confirm: {
                    btnClass: 'btn-primary',
                    action: function() {
                        var url = $this.attr('data-url');

                        $.post(url, async function() {
                            $this.closest('tr').fadeOut();
                            await sleep(400);
                            location.reload();
                        })
                    }
                },
                Cancel: function() {}
            }
        });
    })

    $('.restore-todo').on('click', function() {
        $this = $(this);
        var url = $(this).attr('data-url');

        $.post(url, async function() {
            $this.closest('tr').fadeOut();
            await sleep(400);
            location.reload();
        });
    });

})(jQuery);
