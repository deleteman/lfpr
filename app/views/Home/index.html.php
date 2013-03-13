<div class="hero-unit">
<h3>Welcome to </h3>
<h1> Looking for Pull Request!</h1>
<p>
	Use the site to find a project to contribute to, <em>OR</em> publish your projects and let the 
	world know you're looking for contributors
</p>
	<p class="pull-left">
	<?=link_to(project_list_path(), "Find projects", array("class" => "btn btn-primary btn-large"))?>
	<?=link_to("#", "Publish your project", array("class" => "btn btn-large"))?>
	</p>
	<div class="well span4 pull-right">
		<p>Subscribe to get weekly updates on the site and new repos published.</p>
		<form action="">
			<input type="email" placeholder="your@email.com" />
			<input type="submit" class="btn btn-primary btn-large" value="Subscribe" />
		</form>
	</div>
	<div class="clearfix"></div>
</div>

	<h3>Latest projects</h3>
	<div class="row latest-projects">
		<div class="span3 text-center">
			<div class="project-spotlight">
				<h3>Project title1</h3>
				<p>This is the description of the project</p>
				<p>By <a href="#">dleetman</a></p>
				<?=link_to("#", "Read more", array("class" => "btn btn-primary btn-large"))?>
			</div>
		</div>
		<div class="span3 text-center">
			<div class="project-spotlight">
				<h3>Project title #2</h3>
				<p>This is the description of the project, it's a bit bigger than the rest of them</p>
				<p>By <a href="#">dleetman</a></p>
				<?=link_to("#", "Read more", array("class" => "btn btn-primary btn-large"))?>
			</div>
		</div>
		<div class="span3 text-center">
			<div class="project-spotlight">
				<h3>Project title #3</h3>
				<p>This is the description of the project</p>
				<p>By <a href="#">dleetman</a></p>
				<?=link_to("#", "Read more", array("class" => "btn btn-primary btn-large"))?>
			</div>
		</div>
		<div class="span3 text-center">
			<div class="project-spotlight">
				<h3>Your project</h3>
				<p>
					<img src="/img/illustrations/clipboard.png">
				</p>
				<?=link_to("#", "Publish now!", array("class" => "btn btn-success btn-large"))?>
			</div>
		</div>
	</div>
