
$(document).ready(function() {
	$('a[type="submit"]').click(function () {
		$(this).parent("form").submit();
	});


	$(".date-field").datepicker({
								"dateFormat": "yy-mm-dd",
								"regional" : "es"
								});
	$(".time-field").timePicker();


	$(".dropkick-select").dropkick();

	$("#query-btn").on('click', function() {
		var $this = $("#project_url");
		var btn = $(this);
		btn.addClass("disabled");
		$.post('/project/grab_data',
			 {url: $this.val()},
			 function(data, textStatus, xhq) {
			 	var json = JSON.parse(data);
			 	if(json.message) {
			 		$("#error-box").html(json.message);
			 		$("#error-box").fadeIn();

				 	$("#save-project-btn").attr("disabled", true);
				 	$("#save-project-btn").addClass("disabled");
			 	} else {
			 		$("#error-box").fadeOut();
					$("#project_name").val(json.name);
				 	$("#project_description").val(json.description);
				 	$("#project_owner_name").val(json.owner_name);
				 	$("#project_stars").val(json.stars);
				 	$("#project_forks").val(json.forks);
				 	$("#project_last_update").val(json.last_update);

				 	$("#owner_avatar").val(json.avatar_url);
				 	
				 	$("#stars_field").html(json.stars);
				 	$("#forks_field").html(json.forks);
				 	$("#last_update_field").html(json.last_update);
		
				 	$("#project_language").val(json.language);

				 	$("#save-project-btn").attr("disabled", false);
				 	$("#save-project-btn").removeClass("disabled");
			 	}
			 	
			 	btn.removeClass("disabled");
			 });
	});

	$(".dev-link, .goto-github, .has-tooltip").tooltip();
});