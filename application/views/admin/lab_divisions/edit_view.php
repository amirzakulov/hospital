<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<h4 class="page-title"><?= $title; ?></h4>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
		<div class="card-box">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<?= lang("lab_divisions_name", "name") ?>:<br />
						<?= form_input($name);?>
						<div class="invalid-feedback"><?php echo form_error('name'); ?></div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<?= lang("lab_divisions_sort", "sort") ?>:
						<?= form_input($sort);?>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<?= lang("lab_divisions_active", "active") ?>:<br />
						<?= form_dropdown($active, $active_options, $lab_divisions["active"]);?>
						<div class="invalid-feedback"><?= form_error('active'); ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="m-t-20 text-center">
			<button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
			<a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/lab_divisions") ?>"><?= lang("general_cancel") ?></a>
		</div>
		<?= form_close(); ?>
	</div>
</div>
