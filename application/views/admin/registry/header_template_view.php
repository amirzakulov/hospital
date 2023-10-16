<div class="row">
    <div class="col-sm-3 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-6 col-6 js_patient_search_content">
        <form action="">
            <div class="form-group row">
                <div class="col-md-12">
                    <input type="text" placeholder="Бемор қидириш: Фамилия, Исм, Телефон" class="js_patient_searchbox form-control form-control-sm" data-url="<?= site_url("admin/registry/ajax_patients_list"); ?>" data-patient-details-url="<?= site_url("admin/registry/ajax_patient_details"); ?>">
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="patient_search_result_box js_patient_search_result_box">

                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-3 text-right m-b-20">
		<button type="button" class="btn btn-danger js_show_cash_expenses" title="Чиқимлар" data-url="<?= site_url("admin/registry/ajax_show_expenses") ?>" data-url-cash="<?= site_url("admin/registry/ajax_get_cash_today") ?>"><span class="fa fa-book"></span></button>
		<?php $href = $page == "rooms" ? site_url("admin/registry/assign_to_room") : site_url("admin/registry/add"); ?>
		<?php $teg_title = $page == "rooms" ? lang("room_assign_patient_to_room") : lang("patients_new_patient_add"); ?>
		<a class="btn btn-primary " href="<?= $href; ?>" title="<?= $teg_title; ?>" role="button"><span class="fa fa-plus"></span></a>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-box">
<!--            <h6 class="card-title">Botto line justified</h6>-->
            <ul class="nav nav-tabs nav-tabs-solid nav-justified nav-justified--block">
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "" ? "javascript:void(0);":site_url("admin/registry") ?>">Бугунги беморлар <?= !$patients_counts["today_count"] ? '':'<span class="badge badge-warning js_today_incomplete_patients_count">'.$patients_counts["today_count"].'</span>'; ?></a>
                </li>
                <li class="nav-item d-none">
                    <a class="nav-link show <?= $this->uri->segment(3) == "credit_patients" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "credit_patients" ? "javascript:void(0);":site_url("admin/registry/credit_patients") ?>"><div>Улгурмаган беморлар <?= !$patients_counts["credit_count"] ? '':'<span class="badge badge-warning js_credit_incomplete_patients_count">'.$patients_counts["credit_count"].'</span>'; ?></div></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "debitor_patients" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "debitor_patients" ? "javascript:void(0);":site_url("admin/registry/debitor_patients") ?>">Қарздорлар <?= !$patients_counts["debitor_count"] ? '':'<span class="badge badge-warning">'.$patients_counts["debitor_count"].'</span>'; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "rooms" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "rooms" ? "javascript:void(0);":site_url("admin/registry/rooms") ?>">Хоналар </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "for_payment_patients" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "for_payment_patients" ? "javascript:void(0);":site_url("admin/registry/for_payment_patients") ?>">Тўловга <?= !$patients_counts["for_payment_count"] ? '':'<span class="badge badge-warning">'.$patients_counts["for_payment_count"].'</span>'; ?></a>
                </li>
				<li class="nav-item">
					<a class="nav-link show <?= $this->uri->segment(3) == "archive_patients" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "archive_patients" ? "javascript:void(0);":site_url("admin/registry/archive_patients") ?>">Беморлар</a>
				</li>
				<li class="nav-item">
					<a class="nav-link show <?= $this->uri->segment(3) == "payments_history" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "payments_history" ? "javascript:void(0);":site_url("admin/registry/payments_history") ?>">Тўловлар Тарихи</a>
				</li>
            </ul>
            <div class="tab-content">

