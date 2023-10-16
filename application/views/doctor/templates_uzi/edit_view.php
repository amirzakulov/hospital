<div class="row">
    <div class="col-lg-8 offset-lg-1">
        <h4 class="page-title"><?= $user_template["title"]; ?></h4>
    </div>
</div>
<div class="row">
	<div class="col-lg-8 offset-lg-1">
		<?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
		<div class="card-box">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<?= lang('edit_template_label', 'template');?>
						<?= form_textarea($template);?>
						<div class="invalid-feedback"><?php echo form_error('template'); ?></div>
					</div>
				</div>
			</div>
		</div>

		<div class="card-box">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<?= lang('edit_template_ru_label', 'template_ru');?>
						<?= form_textarea($template_ru);?>
						<div class="invalid-feedback"><?php echo form_error('template_ru'); ?></div>
					</div>
				</div>
			</div>

		</div>
		<div class="m-t-20 text-center">
			<button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
			<a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("doctor/templates_uzi/tview/".$user_template["id"]) ?>"><?= lang("general_cancel") ?></a>
		</div>
		<?= form_close(); ?>
	</div>
</div>


