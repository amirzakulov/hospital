<?php $this->load->view('admin/reports/header_template_view'); ?>

<div class="row">
	<div class="col-md-12">

		<div id="js_inout_block">
			<?php $this->load->view('admin/reports/main_cash_tab_view') ?>
		</div>

	</div>


	<div class="col-md-12 mt-4">
		<?= form_open("", array("class" => "w-100")); ?>
		<div class="col-md-12">
			<div class="card-box">
				<div class="row">
					<div class="col-sm-5">
						<div class="form-group">
							<div class="cal-icon"><?php echo form_input($start_date);?></div>
							<div class="invalid-feedback"><?php echo form_error('start_date'); ?></div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-group">
							<div class="cal-icon"><?php echo form_input($end_date);?></div>
							<div class="invalid-feedback"><?php echo form_error('end_date'); ?></div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<button type="submit" name="show_report" class="btn btn-primary btn-block">Кўрсатиш</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?= form_close(); ?>
	</div>
</div>


<?php
$paid_debts = 0;
foreach ($from_old_debts as $paid_debt) {
	$paid_debts += $paid_debt["amount"];
}

$data = ["paid_debts" => $paid_debts];

?>

<?php $this->load->view('admin/reports/daily_reports/total', $data) ?>
<?php $this->load->view('admin/reports/daily_reports/partners') ?>
<?php $this->load->view('admin/reports/daily_reports/doctors') ?>

<div style="height: 5px;" class="bg-secondary mt-5"></div>
<?php $this->load->view('admin/reports/monthly_reports/partners') ?>
<?php $this->load->view('admin/reports/monthly_reports/doctors') ?>

<?php $this->load->view('admin/reports/footer_template_view'); ?>
