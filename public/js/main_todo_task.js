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

    $(".modal").on('click', '.submit-btn', function(e) {
        e.preventDefault();
        $(this).closest("form").submit();
    });

    $('.complete-btn').on('click', function() {
        $this = $(this);
        $.confirm({
            theme: 'supervan',
            title: 'Todo completed',
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

    $('.restore-btn').on('click', function() {
        $this = $(this);
        var url = $(this).attr('data-url');

        $.post(url, async function() {
            $this.closest('tr').fadeOut();
            await sleep(400);
            location.reload();
        });
    });

    $('.delete-btn').on('click', function() {
        $this = $(this);
        $.confirm({
            theme: 'supervan',
            title: 'Delete todo',
            content: 'Do you want to permanently delete this todo?',
            buttons: {
                Confirm: {
                    btnClass: 'btn-primary',
                    action: function() {
                        var url = $this.attr('data-url');

                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: async function() {
                                $this.closest('tr').fadeOut();
                                await sleep(400);
                                location.reload();
                            }
                        });
                    }
                },
                Cancel: function() {}
            }
        });
    })

})(jQuery);
