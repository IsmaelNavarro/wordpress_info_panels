
jQuery(document).ready(function($) {

	$(".wip_new_group_panel").click(function() {
		$(".wip_new_group_panel_form").toggle(800);
		if($(this).find("span").hasClass("glyphicon-plus"))
			$(this).find("span").switchClass("glyphicon-plus", "glyphicon-minus");
		else
			$(this).find("span").switchClass("glyphicon-minus", "glyphicon-plus");
	});

	$(".wip_update_group").submit(function(event) {
		event.preventDefault();
		var form_data = $(this).serialize();
		
		var data = {
			'action': 'create_group',
			'form': form_data
		};

		$.post(ajaxurl, data, function(response) {
			console.log(response);
			if(response == 1){
				if(form_data.wip_update_group === "0")
					$(".wip_new_group_panel_form").find(".panel").insert_after_panel_info("El grupo ha sido creado correctamente", "fadeOut", "success");
				else
					$(".info_group").find(".panel").insert_after_panel_info("El grupo ha sido actualizado correctamente", "fadeOut", "success");
			}
		});
	});

	$(".groups").delegate(".list-group-item", "click", function(event) {
		event.preventDefault();
		
		if($(this).hasClass("active")){
			$(this).removeClass("active");

			if($(".info_group").css("display") === "block")
				$(".info_group").toggle("slide", 800);
		}
		else{
			$(this).closest(".groups").find(".active").removeClass("active");
			$(this).addClass("active");	

			$group = $(this);

			if($(".info_group").css("display") === "block"){
				$(".info_group").toggle("slide", 800, function() {
					$(this).find("#wip_panel_name").val($group.html());
					$(this).find("#wip_panel_sizex").val($group.data("sizex"));
					$(this).find("#wip_panel_sizey").val($group.data("sizex"));
					$(this).find("#wip_group_id").val($group.data("id"));
					$(".info_group").toggle("slide", 800);
				});
			}
			else{
				$(".info_group").find("#wip_panel_name").val($group.html());
				$(".info_group").find("#wip_panel_sizex").val($group.data("sizex"));
				$(".info_group").find("#wip_panel_sizey").val($group.data("sizex"));
				$(".info_group").find("#wip_group_id").val($group.data("id"));
				$(".info_group").toggle("slide", 800);
			}
			
		}
	});

	//Script para cerrar los paneles de info
	 $('*').delegate('.clickable', 'click',function(){
        var effect = $(this).data('effect');
        $(this).closest('.panel')[effect]();
    });

});

jQuery.fn.extend({
	"serialize": function() {
		var obj = {};
		jQuery(this).find("input").each(function() {
			if(jQuery(this).attr("name"))
				obj[jQuery(this).attr("name")] = jQuery(this).val();
		});
		return obj;
	},
	insert_after_panel_info: function(texto, efecto, tipo){
        var result = '<div class="panel panel-'+tipo+'">'+
                     '   <div class="panel-heading">'+
                     '       <h3 class="panel-title"> '+texto+' </h3>'+
                     '       <span class="pull-right clickable" data-effect="'+efecto+'"><i class="glyphicon glyphicon-remove"></i></span>'+
                     '   </div>'+
                     '</div>';
                    
        jQuery(this).after(result);
    }
});