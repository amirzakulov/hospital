<div class="row">
	<div class="col-lg-8 offset-lg-1">
		<div class="row">
			<div class="col-lg-6">
				<h4 class="page-title"><?= $template["name"]; ?></h4>
			</div>
			<div class="col-lg-6">
				<a role="button" class="btn btn-primary float-right" href="<?= site_url("admin/uzi/template_edit/".$id) ?>"><?= lang("general_edit") ?></a>
			</div>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-lg-8 offset-lg-1">
		<h4><?= lang('edit_template_label', 'template');?></h4>
		<div class="card-box">
			<div class="row">
				<div class="col-sm-12" style="min-height: 10rem;">
					<?= $template["template"]; ?>
				</div>
			</div>
		</div>

		<h4><?= lang('edit_template_ru_label', 'template_ru');?></h4>
		<div class="card-box">
			<div class="row">
				<div class="col-sm-12" style="min-height: 10rem;">
					<?= $template["template_ru"]; ?>
				</div>
			</div>
		</div>
		<div class="text-center">
			<a role="button" class="btn btn-secondary" href="<?= site_url("admin/uzi/templates") ?>"><?= lang("general_cancel") ?></a>
		</div>
    </div>
</div>
