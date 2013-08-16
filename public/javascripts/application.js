function formatDate(d) {
	var month = d.getMonth() + 1;
	var date = d.getDate();
	var year = d.getFullYear();

	month = (month < 10) ? "0" + month : month;
	date = (date < 10) ? "0" + date : date ;
	return month + "/" + date + "/" + year;
}


$(document).ready(function() {
	$('a[type="submit"]').click(function () {
		$(this).parent("form").submit();
	});

	$("#project-tab").click(function(e) {
		e.preventDefault();
		$(this).tab('show');
	})

	$("#issues-cont .pagination ul li a").live('click', function() {
		var $elem = $(this);
		var p = $elem.data("target");
		var pid = $elem.data("pid");
		$("#issues-cont").addClass("loading");
		$.get("/issue/" + pid + "?p=" + p, function(data) {
			$(".has-tooltip").tooltip();
			$("#issues-cont").html(data).removeClass("loading");
		});
		return false;
	});

	$(".date-field").datepicker({
								"dateFormat": "yy-mm-dd",
								"regional" : "es"
								});
	$(".time-field").timePicker();


	$("select").dropkick();

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
				 	$("#project_open_issues").val(json.open_issues);
				 	$("#project_closed_issues").val(json.closed_issues);

				 	$("#owner_avatar").val(json.avatar_url);
				 	
				 	$("#stars_field").html(json.stars);
				 	$("#forks_field").html(json.forks);
				 	$("#last_update_field").html(json.last_update);
				 	$("#open_issues_field").html(json.open_issues);
				 	$("#closed_issues_field").html(json.closed_issues);
		
				 	$("#project_language").val(json.language);

				 	$("#save-project-btn").attr("disabled", false);
				 	$("#save-project-btn").removeClass("disabled");
			 	}
			 	
			 	btn.removeClass("disabled");
			 });
		return false;
	});

	$(".project-spotlight").hover(function(){
		$(this).addClass("over");
	}, function() {
		$(this).removeClass("over");
	});

	$(".dev-link, .goto-github, .has-tooltip").tooltip();


});