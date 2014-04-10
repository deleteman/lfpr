<h1>Admin main screen</h1>

<div class="well span12">
  <div class="well span6">
    <h2>Website Messages</h2>
    <ol>
    <?php 
    foreach($this->messages as $msg) { ?>
      <li><?=formatDate($msg->created_at, 'd/m/y')?> - <?=$msg->email?> - <?=link_to(web_site_message_retrieve_path($msg), truncate_string($msg->body, 100))?></li>
    <?php } ?>
    </ol>
  </div>

  <div class="well span6">
    <h2>Most common languages</h2>
    <ol>
    <?php 
    foreach($this->langs as $langData) { ?>
      <li><?=$langData['lang']?> - <?=$langData['total']?> 
        (<a href="http://www.lookingforpullrequests.com/project/?language=<?=$langData['lang']?>&owner=&sort=language_asc" target="_blank">list projects</a>)
      </li>
    <?php } ?>
    </ol>
  </div>
  <div class="well span6">
    <h2>Most commits</h2>
    <ol>
    <?php 
    foreach($this->commits as $pdata) { ?>
      <li><?=$pdata['project']->name?> - <?=$pdata['commits']?> commits 
        (<a href="http://www.lookingforpullrequests.com/project/<?=$pdata['project']->id?>" target="_blank">open</a>)
      </li>
    <?php } ?>
    </ol>
  </div>

  <div class="well span6">
    <h2>Most pull requests</h2>
    <ol>
    <?php 
    foreach($this->most_pr as $pdata) { ?>
      <li><?=$pdata['project']->name?> - <?=$pdata['total_activity']?> 
          pulls (<?=$pdata['new_pulls']?> + <?=$pdata['closed_pulls']?> + <?=$pdata['merged_pulls']?>)

        (<a href="http://www.lookingforpullrequests.com/project/<?=$pdata['project']->id?>" target="_blank">open</a>)
        </li>
    <?php } ?>
    </ol>
  </div>
  <div class="well span6">
    <h2>Latest projects</h2>
    <ol>
    <?php 
    foreach($this->latest as $pdata) { ?>
      <li><?=$pdata->name?> (Id: <?=$pdata->id?>)
        (<a href="http://www.lookingforpullrequests.com/project/<?=$pdata->id?>" target="_blank">open</a>)
      </li>
    <?php } ?>
    </ol>
  </div>
  <div class="well span6">
    <h2>Suscribers</h2>
    <ol>
    <?php 
    foreach($this->suscribers as $sus) { ?>
      <li><?=$sus->email?> (Id: <?=$sus->id?>)</li>
    <?php } ?>
    </ol>
  </div>
</div>
