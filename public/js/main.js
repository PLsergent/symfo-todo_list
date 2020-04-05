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
	  
	$('table').DataTable({
		"lengthMenu": [[5, 10, 15], [5, 10, 15]],
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": 5 }, 
		]
	});
	
	$('.form-control-sm').removeClass().addClass("form-control");

})(jQuery);
