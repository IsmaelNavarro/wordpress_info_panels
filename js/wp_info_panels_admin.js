
jQuery(document).ready(function($) {

	$(".wip_new_group_panel").click(function() {
		$(".wip_new_group_panel_form").toggle(800);
	});

	$("#wip_new_group").submit(function(event) {
		event.preventDefault();
		console.log($(this).serialize());
	});

});