<div class="page-header">
<h1>
  Search Results...
</h1>
</div>
<ul class="breadcrumb">
  <li><a href="<?=home_root_path_path()?>">Home</a> <span class="divider">/</span></li>
  <li class="active">Search results</li>
</ul>
<div class="well" id="expanded-search-form">
  <?php $this->renderView("_search_form")?>
  <div class="clearfix"></div>
</div>
  <?php
if(count($this->results) > 0) {  ?>
<div class="row">
<?php foreach($this->results as $idx => $entity) { ?>
  <div class="span3 text-center">
    <div class="project-spotlight">
      <h3><?=link_to(project_show_path($entity), truncate_string($entity->name, 13), array("class" => "proj-name-link"))?></a></h3>
      <div class="">
        <?php if($entity->open_issues) { ?>
          <div class="open-issues-indicator"><i class="fui-bubble-24"></i> <?=$entity->open_issues?> Open <?=($entity->open_issues > 1) ? "Issues" : "Issue" ?></div>
        <?php } ?>
        <ul class="simple-stats pull-left">
          <li><span class="fui-menu-24"></span> <?=$entity->forks?> forks</li>
          <li><span class="fui-heart-24"></span> <?=$entity->stars?> stars</li>
        </ul>
        <ul class="simple-stats pull-left">
          <li><span class="fui-settings-24"></span> <?=$entity->language()?> </li>
        <li ><span class="fui-man-24"></span> <?=link_to(developer_show_path($entity->owner()),
                  $entity->owner()->name,
                  array("class" => "dev-link", "data-title" => "See all projects by this user"))?></li>
        </ul> 
        <div class="clearfix"></div>
      </div>
      <p><?=truncate_string($entity->description, 70)?></p>

      <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$entity->url?>" data-text="Checkout this cool project on @Github!" data-via="lookingfor_pr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      <?=link_to(project_show_path($entity), 
          '<span class="fui-eye-24"></span> View Stats', array("class" => "btn btn-primary btn-large" ))?>
    </div>
  </div>
  <?php } ?>
</div>
  <?php
} else { ?>
  <div class="alert alert-warning">
    <h2>So... this is a bit embarrassing...</h2>
    We were unable to find results for your search, please try again using different parameters :)
  </div>
  <?php
}
?>
<?php
if($this->pagination && $this->pagination['total_pages'] > 1) { 
  $page = $this->pagination['current_page'];
  $total_pages = $this->pagination['total_pages'];
?>
<div class="pagination">
  <ul>
    <?php if($page > 0) { ?>
    <li class="previous">
      <a href="<?=project_search_path(array("q" => $this->q,
                                               "p" => $page - 1))?>">
        <img src="/img/pager/previous.png">
      </a>
    </li>
    <?php  } ?>
    <?php
    for($i = 0; $i < $total_pages; $i++) { ?>
      <li class="<?=($i == $page) ? "active":""?>">
        <a href="<?=project_search_path(array("q" => $this->q, "p" => $i))?>">
          <?=($i+1)?>
        </a>
      </li>
    <?php } ?>
    <?php if($page < $total_pages - 1) { ?>
    <li class="next">
      <a href="<?=project_search_path(array("q" => $this->q, "p" => $page + 1))?>">
        <img src="/img/pager/next.png">
      </a>
    </li>
    <?php  } ?>
  </ul>
</div>
<?php } ?>

