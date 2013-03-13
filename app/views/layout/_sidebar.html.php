<div class="page-header">
	<h1>Filtros</h1>
</div>
<?= form_tag("#", "") ?>
	<div  class="accordion" id="filtros-accordion">
		<ul id="filter-widget" class="nav nav-tabs nav-stacked accordion-group">
			<!--<li>
				<?=form_tag("#",null, array("class" => "form form-search"))?>
				<div class="input-append">
				  <?=text_field_tag("q", "q", array("no-container" => true, "class" => "input-medium search-query"))?>
				  <?=submit("Buscar", array("class" => "btn btn-primary"))?>
				</div>
				<?=form_end_tag()?>
			</li>-->
			<li class="first">
					<div class="accordion-heading">
						<a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#filtros-accordion" >
							Rango de fechas<i class="icon-chevron-right"></i> 
						</a>
					</div>
					<div id="collapseOne" class="accordion-body collapse ">
						<div class="accordion-inner">
							<?=date_field_tag("init_date", "init_date")?>
							<?=date_field_tag("end_date", "end_date")?>
						</div>
					</div>
			</li>
			<li>
				<div class="accordion-heading">
					<a href="#select-ubicacion" class="accordion-toggle" data-toggle="collapse" data-parent="#filtros-accordion">
						Ubicación<i class="icon-chevron-right"></i>
					</a>
				</dv>
				<div id="select-ubicacion" class="accordion-body collapse">
					<div class="accordion-inner">
						<?= select_field_tag("ubicacion","ubicacion", array("options" => array()))?>
					</div>
				</div>
			</li>
			<li >
					<div class="accordion-heading">
						<a href="#collapseTwo" class="accordion-toggle" data-toggle="collapse" data-parent="#filtros-accordion">
							Nombre del buque<i class="icon-chevron-right"></i> 
						</a>
					</div>
					<div id="collapseTwo" class="accordion-body collapse">
						<div class="accordion-inner">
							<?=text_field_tag("nombre-buque", "nombre-buque")?>
						</div>
					</div>
			</li>
			<li>
				<div class="accordion-heading">
					<a href="#campoEmpresa" class="accordion-toggle" data-toggle="collapse" data-parent="#filtros-accordion">
						Nombre de la empresa<i class="icon-chevron-right"></i> 
					</a>	
				</dv>
				<div id="campoEmpresa" class="accordion-body collapse">
					<div class="accordion-inner">
						<?= text_field_tag("nombre-empresa","nombre-empresa")?>
					</div>
				</div>
			</li>
			<li>
				<div class="accordion-heading">
					<a href="#selectBandera" class="accordion-toggle" data-toggle="collapse" data-parent="#filtros-accordion">
						Bandera <i class="icon-chevron-right"></i> 
					</a>
				</dv>
				<div id="selectBandera" class="accordion-body collapse">
					<div class="accordion-inner">
						<?= select_field_tag("nombre-empresa","nombre-empresa", array("options" => array()))?>
					</div>
				</div>
			</li>
			<li>
				<div class="accordion-heading">
					<a href="#select-tipo-entrada" class="accordion-toggle" data-toggle="collapse" data-parent="#filtros-accordion">
						Tipo de Entrada<i class="icon-chevron-right"></i>
					</a>
				</dv>
				<div id="select-tipo-entrada" class="accordion-body <?=($this->request->getParam('tipo-entrada') != '')?"in":"collapse"?>">
					<div class="accordion-inner">
						<?= select_field_tag("tipo-entrada","tipo-entrada", array("options" => 
																					array(array("all", "Todas"),
																						  array("pesquero_nacional","Pesquero Nacional"),
																						  array("buque_nacional",  "Buque Nacional")),
																				  "selected" => $this->request->getParam("tipo-entrada")
																			  ))?>
					</div>
				</div>
			</li>
			<li>
				<div class="accordion-heading">
					<a href="#select-tipo-buque" class="accordion-toggle" data-toggle="collapse" data-parent="#filtros-accordion">
						Tipo de buque<i class="icon-chevron-right"></i>
					</a>
				</dv>
				<div id="select-tipo-buque" class="accordion-body collapse">
					<div class="accordion-inner">
						<?= select_field_tag("tipo-buque","tipo-buque", array("options" => list_tipo_buque(), 
																			  "text_field" => "name",
																			  "value_field" => "id"))?>
					</div>
				</div>
			</li>
		</ul>
		<?= submit("Filtrar", array("class" => "btn btn-primary pull-right"))?>
	</div>
<?=form_end_tag()?>