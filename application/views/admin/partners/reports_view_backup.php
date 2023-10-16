<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title">Хамкорлар</h4>
    </div>
    <div class="col-sm-5 col-6">

    </div>
    <div class="col-sm-3 col-3 text-right m-b-20">

    </div>
</div>
<div class="row">
	<div class="col-md-12">
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
<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped datatable mb-0 ">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="">ФИШ</th>
                <th class="align-text-top" width="">Тўлов</th>
                <th class="align-text-top" width="">Улуш (%)</th>
                <th class="align-text-top" width="">Улуш (Сум)</th>
            </tr>
            </thead>
            <tbody>
			<?php $total_income = $total_amount = 0; ?>
			<?php foreach ($partners as $partner) {?>
			<tr>
				<td class="font-weight-bold"><a href="<?= site_url("admin/partners/share_details/".$partner["partner_id"])."/".$start_date_param."/".$end_date_param; ?>"><?= $partner["last_name"] ." ". $partner["first_name"]; ?></a></td>
				<td class="font-weight-bold"><?= money_formatting($partner["income"]) ?></td>
				<td class="font-weight-bold"><?= $partner["agreement"] ?></td>
				<td class="font-weight-bold"><?= money_formatting($partner["amount"]) ?></td>
			</tr>
			<?php $total_income += $partner["income"]; ?>
			<?php $total_amount += $partner["amount"]; ?>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr class="bg-dark text-white">
				<td></td>
				<td><?= money_formatting($total_income); ?></td>
				<td></td>
				<td><?= money_formatting($total_amount); ?></td>
			</tr>
			</tfoot>
        </table>
    </div>
</div>
