(function($) {

	"use strict";

	var fullHeight = function() {

		$('.js-fullheight').css('height', $(window).height());
		$(window).resize(function(){
			$('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

	$('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active');
	  });
	  
	$('#todos').DataTable({
		"lengthMenu": [[5, 10, 15], [5, 10, 15]],
		"order": [[ 4, "desc" ]]
	});

	$('#tasks').DataTable({
		"lengthMenu": [[5, 10, 15], [5, 10, 15]],
		"order": [[ 4, "desc" ]]
	});
	
	$('.form-control-sm').removeClass().addClass("form-control");

})(jQuery);
