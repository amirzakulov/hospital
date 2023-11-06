<div class="row">
    <div class="col-lg-8">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?= form_open("", array("class"=>"needs-validation ".$was_validated, "novalidate"=>"")); ?>
        <div class="card-box">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo lang('general_name', 'name', array(), true);?>
						<?php echo form_input($name);?>
						<div class="invalid-feedback"><?php echo form_error('name'); ?></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group mt-3">
						<label class="d-block pb-2">Статус:</label>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" name="active" <?= $expense_type["active"] ? 'checked':''; ?> value="1" class="form-check-input">Фаол
							</label>
						</div>
						<div class="form-check-inline">
							<label class="form-check-label">
								<input type="radio" name="active" <?= !$expense_type["active"] ? 'checked':''; ?> value="0" class="form-check-input">Нофаол
							</label>
						</div>
					</div>
				</div>
			</div>
        </div>

        <div class="m-t-20 text-center">
            <button class="btn btn-primary submit-btn"><?= lang("general_save") ?></button>
            <a role="button" class="btn btn-secondary submit-btn" href="<?= site_url("admin/expense_type") ?>"><?= lang("user_cancel_button") ?></a>
        </div>
        <?= form_close(); ?>
    </div>
</div>
