<div class="page-header">
	<h1>
		Stats for project: <small><?=$this->project->name?></small>
	<?=link_to(project_list_path(), "&lt;&lt; Back to list", array("class" => "btn btn-large pull-right"))?>
	</h1>
</div>

<div class="row">
	<h2>Current data</h2>
	<div class="well">
		<ul class="simple-data span4">
			<li><span class="fui-menu-24"></span> <?=$this->project->forks?> forks</li>
			<li><span class="fui-heart-24"></span> <?=$this->project->stars?> stars</li>
			<li><span class="fui-settings-24"></span> <?=$this->project->language?> </li>
			<li ><span class="fui-man-24"></span> <?=link_to(project_list_path(array("language" => "All", "owner" => $this->project->owner()->name)),
										$this->project->owner()->name,
										array("class" => "dev-link", "data-title" => "See all projects by this user"))?></li>
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
	<h2>Historic data</h2>
	<div class="well" id="main-stats" style="height:400px;">
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
foreach($stats as $st) {
	$forks[] = $st->forks;
	$stars[] = $st->stars;
	$dates[] = array_shift(explode(" ", $st->sample_date));
}

?>

$(document).ready(function() {

  var chart1 = new Highcharts.Chart({
        chart: {
            renderTo: 'main-stats',
            type: 'line'
        },
        title: {
            text: 'Stars & Forks over time'
        },
        xAxis: {
        	categories: [<?="'".implode("','", $dates)."'"?>],
            title: {
            	text: "Dates"
            }
        },
        yAxis: {
                    },
        series: [{
            name: 'Forks',
            data: [<?=implode(",", $forks)?>]
        }, {
            name: 'Stars',
            data: [<?=implode(",", $stars)?>]
        }]
    });
});
</script>