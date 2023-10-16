<div class="row">
	<div class="col-md-12">
		<h5 class="font-weight-bold">Киримлар</h5>
		<ul class="list-group">
			<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
				<a class="d-none" href="<?= site_url("admin/reports/doctors/".$start_date_param."/".$end_date_param)  ?>">Шифокорлар</a>
				<a href="javascript:void(0)">Шифокорлар</a>
				<span class=""><?= money_formatting($income["doctor"]); ?></span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
				<a class="d-none" href="<?= site_url("admin/reports/laboratory/".$start_date_param."/".$end_date_param)  ?>">Лаборатория</a>
				<a href="javascript:void(0)">Лаборатория</a>
				<span class=""><?= money_formatting($income["lab"]); ?></span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
				<a class="d-none" href="<?= site_url("admin/reports/uzi/".$start_date_param."/".$end_date_param)  ?>">УЗИ</a>
				<a href="javascript:void(0)">УЗИ</a>
				<span class=""><?= money_formatting($income["uzi"]); ?></span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
				<a class="d-none" href="<?= site_url("admin/reports/room/".$start_date_param."/".$end_date_param)  ?>">Ётоқ</a>
				<a href="javascript:void(0)">Ётоқ</a>
				<span class=""><?= money_formatting($income["room"]); ?></span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
				<a class="d-none" href="<?= site_url("admin/reports/services/".$start_date_param."/".$end_date_param)  ?>">Қўшимча хизматлар</a>
				<a href="javascript:void(0)">Қўшимча хизматлар</a>
				<span class=""><?= money_formatting($income["service"]); ?></span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action text-danger font-weight-bold">
				<?php $paid_debts = $paid_by_cash = $paid_by_card = $paid_by_bank = 0; ?>
				<?php foreach ($from_old_debts as $paid_debt) {
					$paid_debts += $paid_debt["amount"];
					$paid_by_cash += $paid_debt["by_cash"];
					$paid_by_card += $paid_debt["by_card"];
					$paid_by_bank += $paid_debt["by_bank"];
				} ?>
				<a class="d-none" href="<?= site_url("admin/reports/from_old_debts/".$start_date_param."/".$end_date_param)  ?>">Эски қарзлардан</a>
				<a href="javascript:void(0)">Эски қарзлардан</a>
				<span class=""><?= money_formatting($paid_debts); ?></span>
			</li>

			<?php $total_income = $income["doctor"] + $income["lab"] + $income["uzi"] + $income["room"] + $income["service"] + $paid_debts; ?>
			<?php $real_income  = $income["by_cash"] + $income["by_card"] + $income["by_bank"] + $paid_debts; ?>
			<li class="list-group-item d-flex1 justify-content-between align-items-center list-group-item-action bg-header font-18">
				<div class="row">
					<div class="col-md-3">
						<span>Нақд: <strong class="text-warning"><?= money_formatting($income["by_cash"] + $paid_by_cash); ?></strong> </span>
					</div>
					<div class="col-md-3">
						<span>Пластик: <strong class="text-warning"><?= money_formatting($income["by_card"] + $paid_by_card); ?></strong> </span>
					</div>
					<div class="col-md-3">
						<span>Терминал: <strong class="text-warning"><?= money_formatting($income["by_bank"] + $paid_by_bank); ?></strong> </span>
					</div>
					<div class="col-md-3 text-right">
						<span class="font-weight-bold"><?= money_formatting($total_income); ?></span>
					</div>
				</div>
			</li>
		</ul>
	</div>

	<div class="col-md-12 mt-4">
		<h5 class="font-weight-bold">Чиқимлар</h5>
		<ul class="list-group">
			<?php $total = $by_cash = $by_card = $by_bank = 0; ?>
			<?php foreach ($expenditure as $expense) {?>
				<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
					<?= $expense["name"] ?>
					<span class=""><?= $expense["amount"] ?></span>
				</li>
			<?php } ?>
			<li class="list-group-item d-flex1 justify-content-between align-items-center list-group-item-action bg-header font-18">
				<div class="row">
					<div class="col-md-3">
						<span>Нақд: <strong class="text-warning"><?= money_formatting($expenditure_payment_types["by_cash"]); ?></strong> </span>
					</div>
					<div class="col-md-3">
						<span>Пластик: <strong class="text-warning"><?= money_formatting($expenditure_payment_types["by_card"]); ?></strong> </span>
					</div>
					<div class="col-md-3">
						<span>Терминал: <strong class="text-warning"><?= money_formatting($expenditure_payment_types["by_bank"]); ?></strong> </span>
					</div>
					<div class="col-md-3 text-right">
						<span class="font-weight-bold"><?= money_formatting($expenditure_payment_types["by_cash"]+$expenditure_payment_types["by_card"]+$expenditure_payment_types["by_bank"]); ?></span>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>
