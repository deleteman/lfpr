<div class="row">
	<div class="page-header">
		<h1>
			Stats for project: <small><?=$this->project->name?></small>
		<?=link_to(project_list_path(), "&lt;&lt; Back to list", array("class" => "btn btn-large pull-right"))?>
		</h1>
	</div>

	<ul class="breadcrumb">
	  <li><a href="<?=home_root_path_path()?>">Home</a> <span class="divider">/</span></li>
	  <li ><a href="<?=project_list_path()?>">List of projects</a> <span class="divider">/</span></li>
	  <li class="active">Stats for project</li>
	</ul>
	<h2 class="pull-left">Current data</h2>
	<h3 class="pull-right" >
		by
		<?=link_to(project_list_path(array("language" => "All", "owner" => $this->project->owner()->name)),
										$this->project->owner()->name,
										array("class" => "dev-link", "data-title" => "See all projects by this user"))?>
		<?=image_tag($this->project->owner()->avatar(), array("class" => "avatar"))?> 
	</h3>
	<div class="clearfix"></div>
	<div class="well">
		<ul class="simple-data span4">
			<li><span class="fui-menu-24"></span> <?=$this->project->forks?> forks</li>
			<li><span class="fui-heart-24"></span> <?=$this->project->stars?> stars</li>
			<li><span class="fui-settings-24"></span> <?=$this->project->language()?> </li>

			<li><span class="fui-eye-24"></span> <?=link_to($this->project->url, "GitHub URL", array("target" => "_blank"))?></li>
		</ul>
		<div class="span7">
			<p>
				<?=$this->project->description?>
			</p>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<div class="row">
	<div class="span6 pull-left">
		<h2>Popularity over time</h2>
		<div class="well" id="main-stats" style="height:300px;">
		</div>
	</div>
	<div class="span6 pull-left">
		<h2>Commits activity</h2>
		<div class="well " id="commits-stats" style="height:300px;">
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="span12">
		<h2>Pull requests activity</h2>
		<div class="well " id="pull-stats" style="height:400px;">
		</div>
	</div>
</div>
<script type="text/javascript" src="/javascripts/highcharts/js/highcharts.js"></script>
<script >
<?php
//TODO: Improve following code...
$stats = $this->project->getStats();

$forks = array();
$stars = array();
$dates = array();
$commits = array();
$commits_dates = array();
$new_dates = array();
foreach($stats as $st) {
	if($st->forks != -99) {
		$forks[] 		= $st->forks;
		$stars[] 		= $st->stars;
		$new_dates[] 	= array_shift(explode(" ", $st->sample_date));
	}
	$dates[] 		= array_shift(explode(" ", $st->sample_date));
	$commits[] 		= ($st->commits_count === null) ? 0 : $st->commits_count;
	$new_pulls[] 	= ($st->new_pulls === null) ? 0 : $st->new_pulls;
	$closed_pulls[] = ($st->closed_pulls === null) ? 0 : $st->closed_pulls;
	$merged_pulls[] = ($st->merged_pulls === null) ? 0 : $st->merged_pulls;
}

?>

$(document).ready(function() {

  var chart1 = new Highcharts.Chart({
        chart: {
            renderTo: 'main-stats',
            type: 'column'
        },
        title: {
            text: 'Stars & Forks over time'
        },
        xAxis: {
        	categories: [<?="'".implode("','", $new_dates)."'"?>],
            title: {
            	text: "Dates"
            }
        },
        yAxis: {
                    },
        series: [{
            name: 'Forks',
            type: 'column',
            data: [<?=implode(",", $forks)?>]
        }, {
            name: 'Stars',
            type: 'spline',
            data: [<?=implode(",", $stars)?>]
        }]
    });


  var chart2 = new Highcharts.Chart({
        chart: {
            renderTo: 'commits-stats',
            type: 'spline'
        },
        title: {
            text: 'Commits activity'
        },
        xAxis: {
        	categories: [<?="'".implode("','", $dates)."'"?>],
            title: {
            	text: "Dates"
            },
            tickInterval: 7
         },
        yAxis: {
        	title: {
        		text: "# of commits"
        	}
        },
        series: [{
            name: 'Commits ',
            data: [<?=implode(",", $commits)?>]
        }]
    });


	var chart3 = new Highcharts.Chart({
        chart: {
            renderTo: 'pull-stats',
            type: ''
        },
        title: {
            text: 'Pull requests activity'
        },
        xAxis: {
        	categories: [<?="'".implode("','", $dates)."'"?>],
            title: {
            	text: "Dates"
            },
            tickInterval: 7
        },
        yAxis: {
        	title: {
        		text: "# of Pull Requests"
        	}
        },
        series: [{
            name: 'Merged',
            type: 'column',
            data: [<?=implode(",", $merged_pulls)?>]
        },
        {
            type: 'spline',
            name: 'Closed',
            data: [<?=implode(",", $closed_pulls)?>]
        },
        {
            type: 'areaspline',
            name: 'Opened',
            data: [<?=implode(",", $new_pulls)?>]
        }]
    });
});

</script>