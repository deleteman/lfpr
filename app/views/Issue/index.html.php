<?php
if(count($this->issues) > 0) { ?>
    <div class="row">
    <h3>Can you fix this? Help! <small> Showing <?=count($this->issues)?> out of <?=$this->pagination['total_results']?> results...</small></h3>

    <div class="accordion well" id="issues-list">
        <?php 
        foreach($this->issues as $idx => $issue) { ?>
        <div class="accordion-group <?=($idx % 2 == 0) ? 'odd' : 'even'?>">
            <div class="accordion-heading">
             <h3>
                <small class="issue-nmbr ">#<?=$issue->num?></small>
                <?=link_to("#issue".$idx, htmlentities($issue->title), array("class" => "proj-name-link accordion-toggle collapsed", "data-toggle" => "collapse", "data-parent" => "issues-list"))?></a>
                <?=link_to($issue->url, '<i class="icon-share"></i>', array("target" => "_blank", "class" => "has-tooltip", "data-title" => "Check it out on Github..."))?>
             </h3>
            </div>
            <div class="accordion-body collapse collapse" id="issue<?=$idx?>">
                <div class="accordion-inner">
                 <div class="">  
                   <div class="clearfix"></div>
                 </div>
                 <p><?=$issue->body?></p>
             </div>
            </div>
                   
       </div>
       <?php } ?>
   </div>
   <?php
	if($this->pagination['total_pages'] > 1) { 
		$page = $this->pagination['current_page'];
		$total_pages = $this->pagination['total_pages'];
	?>
	<div class="pagination">
		<ul>
			<?php if($page > 0) { ?>
			<li class="previous">
				<a href="#" data-target="<?=($page-1)?>" data-pid="<?=$this->pid?>">
					<img src="/img/pager/previous.png">
				</a>
			</li>
			<?php  } ?>
			<?php
			for($i = 0; $i < $total_pages; $i++) { ?>
				<li class="<?=($i == $page) ? "active":""?>">
					<a href="#"  data-target="<?=$i?>" data-pid="<?=$this->pid?>">
						<?=($i+1)?>
					</a>
				</li>
			<?php } ?>
			<?php if($page < $total_pages - 1) { ?>
			<li class="next">
				<a href="#" data-target="<?=$page + 1?>" data-pid="<?=$this->pid?>">
					<img src="/img/pager/next.png">
				</a>
			</li>
			<?php  } ?>
		</ul>
	</div>
	<?php } ?>
    </div>
<?php
    }
?>