<div class="row">
    <div class="col-sm-4 col-3 offset-md-2">
        <h4 class="page-title">Хамкорлар</h4>
    </div>
    <div class="col-sm-5 col-6">

    </div>
    <div class="col-sm-3 col-3 text-right m-b-20">

    </div>
</div>
<div class="row d-none">
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
    <div class="col-md-8 offset-md-2">
        <table class="table table-bordered table-striped datatable mb-0 table-small">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="200">ФИШ</th>
                <th class="align-text-top" width="200">Ташкилот</th>
                <th class="align-text-top" width="200">Улуш (Сум)</th>
                <th class="align-text-top">Ойлик (Жорий ой учун)</th>
            </tr>
            </thead>
            <tbody>
			<?php $total_income = $total_amount = 0; ?>
			<?php foreach ($partners as $partner) {?>
			<tr class="js_row_<?=$partner["partner_id"]; ?>">
				<td class=""><a href="<?= site_url("admin/partners/share_details/".$partner["partner_id"])."/".$start_date_param."/".$end_date_param; ?>">
						<?php
						if(!empty($partner["last_name"]) && !empty($partner["first_name"])) {
							echo $partner["last_name"] ." ". $partner["first_name"];
						} elseif(!empty($partner["company"])) {
							echo $partner["company"];
						}
						?>
					</a>
				</td>
				<td class=""><?= $partner["company"]; ?></td>
				<?php $debt = $partner["amount"] - $partner["bill_amount"]; ?>
				<td class="js_bill_amount <?= $debt < 0 ? "text-danger":""; ?>">
					<div class="d-inline-block w-100 text-right">
						<span class="pr-2"><?= money_formatting($debt); ?></span>
						<a href="javascript:void(0);" class="js_bill_edit"><span class="fa fa-edit"></span></a>
					</div>

					<div class="input-group d-none js_bill_input">
						<input type="text" class="form-control-sm w-50">
						<div class="input-group-append">
							<button class="btn btn-success btn-sm" type="button"
									data-partner-id="<?= $partner["partner_id"]; ?>"
									data-url="<?= site_url("admin/partners/ajax_partner_checkout") ?>"
							><span class="fa fa-check"></span></button>
							<button class="btn btn-danger btn-sm" type="button"><span class="fa fa-remove"></span></button>
						</div>
					</div>
				</td>
				<td class="text-right js_current_month_salary">
					<span>
					<?= isset($partners_income[$partner["partner_id"]]) ? money_formatting($partners_income[$partner["partner_id"]]["bill_amount"]) : 0; ?>
					</span>
					<input type="hidden" value="<?= !empty(isset($partners_income[$partner["partner_id"]]["bill_amount"])) ? $partners_income[$partner["partner_id"]]["bill_amount"] : 0; ?>">
				</td>
			</tr>
			<?php $total_amount += ($partner["amount"] - $partner["bill_amount"]); ?>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr class="bg-dark text-white">
				<td></td>
				<td></td>
<!--				<td>--><?//= money_formatting($total_income); ?><!--</td>-->
				<td class="js_bill_amount_total">
					<span><?= money_formatting($total_amount); ?></span>
					<input type="hidden" value=<?= $total_amount; ?>"">
				</td>
				<td></td>
			</tr>
			</tfoot>
        </table>
    </div>
</div>
<div id="partner_bill_form" class="modal fade" data-backdrop="static" aria-modal="true">
	<div class="modal-dialog modal-md" role="document">
		<?= form_open(site_url("admin/partners/ajax_partner_checkout"), array("class"=>"needs-validation w-100 form-inline", "novalidate"=>""), array("payment_id" => "", "bed_price" => "")); ?>
		<div class="modal-content bg-light border-dark">
			<div class="modal-header justify-content-center! bg-info">
				<div class="text-dark">Шифокор: <span class="text-light js_partner_name">Maxmudova Nodirahon</span></div>
				<div class="text-dark">Ташкилот: <span class="text-light js_partner_company">Hospital ZM</span></div>
			</div>
			<div class="modal-body">
				<input type="hidden" name="partner_id">
				<div class="row">
					<div class="col-md-3">Тўлов суммаси</div>
					<div class="col-md-9"><input type="text" name="amount" class="w-100"></div>
				</div>
			</div>
			<div class="modal-footer justify-content-center">
				<button class="btn btn-danger" data-dismiss="modal">Ойнани ёпиш</button>
				<button class="btn btn-primary js_partner_bill_form_save">Сақлаш</button>
			</div>
		</div>
		<?= form_close(); ?>
	</div>
</div>
