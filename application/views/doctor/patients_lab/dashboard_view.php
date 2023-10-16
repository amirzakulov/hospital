<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row mb-2">
    <div class="col-6"><h3><?= $title; ?></h3></div>
    <div class="col-6">



		<form>
			<div class="form-row lab_date_range js_lab_date_range">
				<div class="col">
					<input type="text" class="form-control datetimepicker js_start_date" value="<?= date("d.m.Y"); ?>" placeholder="Бош сана">
					<small class="text-danger"></small>
				</div>
				<div class="col">
					<input type="text" class="form-control datetimepicker js_end_date" value="<?= date("d.m.Y"); ?>" placeholder="Якуний сана">
					<small class="text-danger"></small>
				</div>
				<div class="col">
					<input type="button" class="form-control bg-header text-white js_lab_date_range_submit" value="Кўриш">
				</div>
			</div>
		</form>
	</div>
</div>

<div class="row">
<!--	<pre>-->
<!--	--><?php //print_r($patients_laboratories); ?>
<!--	</pre>-->
</div>

<div class="row">
    <div class="col-md-12">
        <div id="pl_results" class="card-box p-0">
			<ul class="nav nav-tabs nav-tabs-solid nav-justified nav-justified--block nav-lab-block nav-justified--report">
				<li class="nav-item bg-dark">
					<a class="nav-link show text-white js_lab_division active" href="javascript:void(0);"
					   data-division-id="0"
					   data-url="<?= site_url("doctor/patients_lab/ajax_division_patient_laboratories") ?>">Умумий</a>
				</li>
				<?php foreach ($lab_divisions as $division) { if($division["active"]) { ?>
				<li class="nav-item bg-dark">
					<a class="nav-link show text-white js_lab_division" href="javascript:void(0);"
					data-division-id="<?= $division["id"]; ?>"
					data-url="<?= site_url("doctor/patients_lab/ajax_division_patient_laboratories") ?>"><?= $division["name"]; ?></a>
				</li>
				<?php } } ?>
			</ul>
			<div id="pl_results_table">
				<?php if(count($laboratories) == 0) { ?>
					<div class='p-5 text-center'>Malumot yuq</div>
				<?php } else { ?>
					<table class="table table-striped table-bordered dataTable lab_dashboard_dt" id="lab_dashboard_dt">
						<thead>
						<tr class="bg-dark text-white first_row">
							<th style="width: 200px; min-height: 400px;" class="fixed_col text-center">Беморлар</th>
							<th style="width: 80px;" class="fixed_col text-center">Чек</th>
							<th style="width: 100px;" class="fixed_col text-center">Сана</th>
							<?php foreach ($laboratories as $laboratory) { ?>
								<th width="100px"><?= $laboratory["laboratory_name"] ?></th>
							<?php } ?>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($patients as $payment_id => $patient) { ?>
							<tr>
								<td style="width: 200px;" class="fixed_col text-left">
									<a class="text-white font-weight-bold" href="<?= site_url("doctor/patients_lab/patient/".$payment_id) ?>"><?= $patient["patient_name"]; ?></a><br>
								</td>
								<td style="width: 80px;" class="fixed_col text-center"><span class="text-warning"><?= $payment_id; ?></span></td>
								<td style="width: 100px;" class="fixed_col text-center"><?= date("d.m.Y H:i", strtotime($patient["patient_date"])); ?></td>
								<?php foreach ($laboratories as $laboratory) { ?>
									<?php
									$class = $result = $status = "";
									if(isset($patient_laboratories[$patient["patient_id"]][$laboratory["lab_id"]])) {
										$status = $patient_laboratories[$patient["patient_id"]][$laboratory["lab_id"]]["status"];
										$result = $patient_laboratories[$patient["patient_id"]][$laboratory["lab_id"]]["result"];
										$class = !$status ? "bg-info":"bg-success";
									}
									?>
									<td width="100px" class="<?= $class; ?>"><?= $result; ?></td>
								<?php } ?>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				<?php } ?>

			</div>

		</div>
    </div>
</div>

