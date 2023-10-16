<div class="row">
	<div class="col-lg-11 offset-lg-1">
		<h4 class="page-title">Номи: <?= $template["name"]; ?></h4>
	</div>
</div>
<div class="row mb-4">
	<div class="col-lg-8 offset-lg-1">
		<div class="row bg-white pt-3 pb-3">
			<div class="col-lg-4">
				<div class="btn-group" role="group">
					<a target="_blank" href="<?= site_url("doctor/templates_uzi/view_pdf/1/".$template["id"]); ?>" class="btn btn-outline-success"><span class="fa fa-file-pdf-o"></span> Узб</a>
					<a target="_blank" href="<?= site_url("doctor/templates_uzi/view_pdf/2/".$template["id"]); ?>" class="btn btn-outline-success"><span class="fa fa-file-pdf-o"></span> Рус</a>
				</div>
			</div>
			<div class="col-lg-8">
				<a role="button" class="btn btn-primary float-right" href="<?= site_url("doctor/templates_uzi/edit/".$template["id"]) ?>"><?= lang("general_edit") ?></a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 offset-lg-1 bg-white pt-3 pb-3">
		<h4 class="p-0 m-0 text-dark"><?= lang('edit_template_label', 'template');?></h4>
		<div class="card-box">
			<div class="row">
				<div class="col-sm-12" style="min-height: 10rem; color: #000000;">
					<?= $template["template"]; ?>
				</div>
			</div>
		</div>

		<h4 class="text-dark"><?= lang('edit_template_ru_label', 'template_ru');?></h4>
		<div class="card-box">
			<div class="row">
				<div class="col-sm-12" style="min-height: 10rem; color: #000000;">
					<?= $template["template_ru"]; ?>
				</div>
			</div>
		</div>
		<div class="text-center">
			<a role="button" class="btn btn-secondary" href="<?= site_url("doctor/templates_uzi") ?>"><?= lang("general_back") ?></a>
		</div>
	</div>
</div>
