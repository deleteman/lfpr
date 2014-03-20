<?php
$projects = $this->entity->getProjects();
if(current_user() != null) {
  $current_user = $this->entity->id == current_user()->id;
} else {
  $current_user = false;
}
?>
<div class="page-hader relative">
  <h1>
    <?=image_tag($this->entity->avatar_url, array("class" => "avatar"))?>
    <?=$this->entity->name?>
  </h1>
  <ul class="dev-stats pull-right">
    <li><i class="icon-list"></i> <?=count($projects)?> Projects</li>
    <li><i class="icon-check"></i> <?=$this->entity->commitCount()?> Commits</li>
  </ul >
</div>

<ul class="breadcrumb">
  <li><a href="<?=home_root_path_path()?>">Home</a> <span class="divider">/</span></li>
  <li class="active"><?=$this->entity->name?></li>
</ul>
<h2>Projects</h2>
<div class="well">
  <ul class="projects-list">
    <?php foreach($projects as $i => $proj) { ?> 
    <?php
      $class = ($i % 2) ? "par" : "impar";
      ?>
      <li class="<?=$class?>">
      <?php if($proj->published) { ?> 
        <?=link_to(project_show_path($proj), truncate_string($proj->name, 10), array("class" => "project-lnk"))?>
        <span class="language">(<?=$proj->language?>)</span>
        <p class="project-description">
          <?=truncate_string($proj->description, 90)?>
        </p>

        <?=link_to(project_show_path($proj), '<i class="fui-eye-24"></i> Read more', array("class" => "btn btn-large btn-primary "))?>
        <?php if($current_user) { ?> 
          <?=link_to(project_unpublish_path($proj), '<i class="fui-cross-24"></i> Un-publish', array("id" => "unpublish-btn", "class" => "btn btn-large btn-remove"))?>
        <?php } ?>
      <?php } else { ?> 
        <span class="project-name"><?=truncate_string($proj->name, 10)?></span>
        <span class="language">(<?=$proj->language?>)</span>
        <p class="project-description">
          <?=$proj->description?>
        </p>

 
        <?php if($current_user) { ?> 
          <?=link_to(project_publish_path($proj), '<i class="fui-plus-24"></i> Publish', array("class" => "btn btn-large"))?>
        <?php } ?>
      <?php } ?>
      
    </li>
    <?php }?>
  </ul>
<div class="clearfix"></div >
</div>
