<div class="container-fluid p-0">
	<h4 class="text-danger" style="margin-top: -15px;"><?= date_formating(time(), "mt"); ?></h4>
	<div class="card no-shadow bg-transparent no-border rm-borders mb-3 report_page js_sticky_block">
		<div class="card">
			<div class="p-0 row">
                <div class="col-md-12 col-lg-2 d-none">
                    <ul class="list-group list-group-flush">
                        <li class="bg-transparent list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Умумий</div>
                                            <div class="font-18 text-danger font-weight-bold"><?= money_formatting($cash["total_payment"]["total"]); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="col-md-12 col-lg-2">
                    <ul class="list-group list-group-flush">
                        <li class="bg-transparent list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Касса</div>
                                            <div class="font-18 text-danger font-weight-bold"><span style="font-size: 12px;">Умумий:<br></span> <?= money_formatting($cash["real_payment"]["paid"] - ($cash["expenditure"] + $cash["doctors_bill"])); ?></div>
											<div class="d-none font-18 text-danger font-weight-bold"><span style="font-size: 12px;">Нақд:<br></span> <?= money_formatting($cash["real_payment"]["by_cash"] - ($cash["expenditure"] + $cash["doctors_bill"])); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
				<div class="col-md-12 col-lg-2">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Кирим</div>
											<div class="widget-subheading1 font-18 text-dark"><?= money_formatting($cash["real_payment"]["paid"]); ?></div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>

				<div class="col-md-12 col-lg-2">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Чиқим</div>
											<div class="widget-subheading1 font-18 text-dark"><?= money_formatting($cash["expenditure"]); ?></div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-md-12 col-lg-2">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Қарз</div>
											<div class="widget-subheading font-18 text-danger"><a href="<?= site_url("admin/reports/debts/".$start_date_param."/".$end_date_param); ?>"><?= money_formatting($cash["debt"]["debt"]); ?></a></div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-md-12 col-lg-2">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Чегирма</div>
											<div class="widget-subheading font-18 text-dark"><?= money_formatting($cash["total_payment"]["discount"]); ?></div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-md-12 col-lg-2">
					<ul class="list-group list-group-flush">
						<li class="bg-transparent list-group-item">
							<div class="widget-content p-0">
								<div class="widget-content-outer">
									<div class="widget-content-wrapper">
										<div class="widget-content-left">
											<div class="widget-heading">Бугунги умумий</div>
<!--											<div class="widget-heading">Шифокорларга</div>-->
											<div class="widget-subheading font-18 text-dark"><?= money_formatting($cash["total_payment"]["total"]); ?></div>
<!--											<div class="widget-subheading font-18 text-dark">--><?//= money_formatting($cash["doctors_bill"]); ?><!--</div>-->
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>



<?php if(isset($last_payments)) { ?>
	<div class="accordion mt-3" id="accordionExample">
		<div class="card">
			<div class="card-header p-0" id="headingOne">
				<h5 class="mb-0 pb-1">
					<button class="btn btn-block text-left js_report_ribbon_btn" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						<h4 class="float-left">Сўнгги 5 та бемор</h4>
						<span class="fa fa-chevron-down fa-1x float-right pt-1"></span>
					</button>
				</h5>
			</div>
			<div id="collapseOne" class="collapse show1" aria-labelledby="headingOne" data-parent="#accordionExample">
				<div class="card-body p-0">
					<div class="row">
						<div class="col-md-12">
							<div class="card-box p-0">
								<table class="table">
									<thead class="thead-dark">
									<tr>
										<th class="align-text-top" width="10%"><?= lang("index_payment_id_th"); ?></th>
										<th class="align-text-top" width="22%"><?= lang("index_fname_th"); ?></th>
										<th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
										<th class="align-text-top" width="15%"><?= lang("index_payment"); ?></th>
										<th class="align-text-top" width="10%">Йўлланма берувчи</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ($last_payments as $patient) {?>
										<tr id="js_row_<?= $patient["id"] ?>">
											<td class="font-weight-bold">
												<?= $patient["id"]; ?>
												<div class="font-weight-normal"><?= date("d M Y H:i", strtotime($patient["payment_date"])); ?></div>
											</td>
											<td>
												<span><?= $patient["last_name"] ." ". $patient["first_name"] ." ". $patient["surname"];?></span>
												<div class="doc-prof doc-prof--mb0">
													<i class="fa fa-map-marker text-danger"></i> <?= $patient["region_name"] .", ". $patient["city_name"] .", ". $patient["address"]; ?>
												</div>
											</td>
											<td><?= is_null($patient["phone"]) ? "":phone_number_format($patient["phone"]);?></td>
											<td class="font-weight-bold text-danger">
												<span class="js_payments_<?= $patient["id"]; ?>"><?= $patient["paid"] ."/".$patient["total"]; ?></span>
												<div class="doc-prof doc-prof--mb0">
													<?= ($patient["debt"] > 0) ? '<span class="badge badge-danger">Қарз</span>':''; ?>
													<?= ($patient["discount"] > 0) ? '<span class="badge badge-info">Чегирма</span>':''; ?>
												</div>
												<?php $paid_services = ""; ?>
												<?php
												//doctorni statusiga qarab rangini belgilaymiz
												if($patient["doctor_status"] == 0) {$paid_services .= "";}
												elseif ($patient["doctor_status"] == 2) {$paid_services .= "<span class='text-danger'>D</span>";}
												else {$paid_services .= "<span title='Шифокор кўриги'>D</span>";}

												//labni statusiga qarab rangini belgilaymiz
												if($patient["laboratory_status"] == 0) {$paid_services .= "";}
												elseif (in_array($patient["laboratory_status"], array(2,4))) {$paid_services .= " <span class='text-danger'>L</span>";}
												else {$paid_services .= "<span title='Лаборатория'>L</span>";}

												//uzini statusiga qarab rangini belgilaymiz
												if($patient["uzi_status"] == 0) {$paid_services .= "";}
												elseif ($patient["uzi_status"] == 2) {$paid_services .= " <span class='text-danger'>U</span>";}
												else {$paid_services .= "<span title='УЗИ'>U</span>";}

												//serviceni statusiga qarab rangini belgilaymiz
												if($patient["service_status"] == 0) {$paid_services .= "";}
												elseif ($patient["service_status"] == 2) {$paid_services .= " <span class='text-danger'>X</span>";}
												else {$paid_services .= "<span title='Қўшимча хизматлар'>X</span>";}

												//roomni statusiga qarab rangini belgilaymiz
												if($patient["room_status"] == 0) {$paid_services .= "";}
												elseif ($patient["room_status"] == 2) {$paid_services .= " <span class='text-danger'>X</span>";}
												else {$paid_services .= "<span title='Хона хизмати'>R</span>";}
												?>
												<div class="text-dark"><?= $paid_services; ?></div>
											</td>
											<td><?= $patient["partner_last_name"] ." ". $patient["partner_first_name"];?></td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>


<div class="row mb-4">
	<div class="col-md-12">
        <div class="card-box">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified nav-justified--block my_nav_tabs border-bottom border-info" data-start-date="<?= $start_date_param; ?>" data-end-date="<?= $end_date_param; ?>">
                <li class="nav-item">
                    <a class="nav-link show <?= ($this->uri->segment(3) == "" || $this->uri->segment(3) == "index") ? "active":"" ?>" href="<?= $this->uri->segment(3) == "" ? "javascript:void(0);":site_url("admin/reports/index/".$start_date_param."/".$end_date_param) ?>">Касса</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "doctors") ? "active":"" ?>" href="<?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "doctors") ? "javascript:void(0);":site_url("admin/reports/doctors/".$start_date_param."/".$end_date_param) ?>">Шифокорлар</a>
                </li>
                <li class="nav-item">
                    <a class="d-none nav-link show <?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "laboratory") ? "active":"" ?>" href="<?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "laboratory") ? "javascript:void(0);":site_url("admin/reports/laboratory/".$start_date_param."/".$end_date_param); ?>">Лаборатория</a>
                    <a class="js_report_tab laboratory nav-link show <?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "laboratory") ? "active":"" ?>" data-href="<?= site_url("admin/reports/ajax_laboratory"); ?>">Лаборатория</a>
                </li>
                <li class="nav-item">
					<a class="d-none nav-link show <?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "uzi") ? "active":"" ?>" href="<?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "uzi") ? "javascript:void(0);":site_url("admin/reports/uzi/".$start_date_param."/".$end_date_param); ?>">УЗИ</a>
					<a class="js_report_tab uzi nav-link show <?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "uzi") ? "active":"" ?>" data-href="<?= site_url("admin/reports/ajax_uzi"); ?>">УЗИ</a>
                </li>
				<li class="nav-item">
					<a class="nav-link show <?= $this->uri->segment(3) == "services" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "services" ? "javascript:void(0);":site_url("admin/reports/services/".$start_date_param."/".$end_date_param) ?>">Қ.Хизматлар</a>
				</li>
				<li class="nav-item">
					<a class="nav-link show <?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "room") ? "active":"" ?>" href="<?= ($this->uri->segment(2) == "reports" && $this->uri->segment(3) == "room") ? "javascript:void(0);":site_url("admin/reports/room/".$start_date_param."/".$end_date_param); ?>">Ётоқ</a>
                </li>
				<li class="nav-item">
					<a class="nav-link show <?= $this->uri->segment(3) == "expenses" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "expenses" ? "javascript:void(0);":site_url("admin/reports/expenses/".$start_date_param."/".$end_date_param) ?>">Чиқимлар</a>
                </li>
				<li class="nav-item">
					<a class="nav-link show <?= $this->uri->segment(3) == "doctors_bill" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "doctors_bill" ? "javascript:void(0);":site_url("admin/reports/doctors_bill/".$start_date_param."/".$end_date_param) ?>">Шифокорларга</a>
                </li>
				<li class="nav-item">
					<a class="nav-link show <?= $this->uri->segment(3) == "from_old_debts" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "from_old_debts" ? "javascript:void(0);":site_url("admin/reports/from_old_debts/".$start_date_param."/".$end_date_param) ?>">Эски қарзлардан</a>
                </li>
				<li class="nav-item">
					<a class="nav-link show <?= $this->uri->segment(3) == "debts" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "debts" ? "javascript:void(0);":site_url("admin/reports/debts/".$start_date_param."/".$end_date_param) ?>">Қарздорлар</a>
                </li>
            </ul>

            <div class="tab-content">



