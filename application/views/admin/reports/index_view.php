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

<div id="js_monthly_reports_partners">
<?php $this->load->view('admin/reports/monthly_reports/partners') ?>
</div>
<div id="js_monthly_reports_doctors">
<?php $this->load->view('admin/reports/monthly_reports/doctors') ?>
</div>
<?php $this->load->view('admin/reports/daily_reports/partner_companies') ?>
<div id="js_monthly_reports_partner_company">
<?php $this->load->view('admin/reports/monthly_reports/partner_companies') ?>
</div>
<?php $this->load->view('admin/reports/daily_reports/expenditure') ?>
<?php $this->load->view('admin/reports/monthly_reports/expenditure') ?>


<div class="modal" id="partners_doctors_checkout" tabindex="-1" data-backdrop="static">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-body">
				<h4 class="js_partners_checkout__title"></h4>
				<table class="table table-border table-striped custom-table mb-0 compact expenses_table">
					<thead>
					<tr>
						<th>Сана</th>
						<th>Сумма</th>
						<th>Тўлов тури</th>
						<th>Ким киритди</th>
						<th class="text-right"></th>
					</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<div class="row col-md-9">
					<div class="js_partners_checkout__total float-left">
						<span>Жами:</span>
						<strong></strong>
					</div>
				</div>
				<div class="row col-md-3">
					<div class="text-right">
						<button type="button" class="btn btn-secondary btn-md" data-dismiss="modal">Ойнани ёпиш</button>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>

<?php $this->load->view('admin/reports/footer_template_view'); ?>

