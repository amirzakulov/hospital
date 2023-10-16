<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-4 col-6">
        <?php $month = date("F"); ?>
        <h4><?= date("d ") . lang($month) . date(" Y"); ?></h4>
    </div>
    <div class="col-sm-4 col-3 text-right m-b-20">
<!--        <a href="--><?//= site_url("admin/patients/add") ?><!--" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> ппппп</a>-->
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-box">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified nav-justified--block d-none">
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "" ? "javascript:void(0);":site_url("admin/patients") ?>">Шифокорлар </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "credit_patients" ? "active":"" ?>" href="javascript:void(0);">Хафталик</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "debitor_patients" ? "active":"" ?>" href="javascript:void(0);">Ойлик</a>
                </li>
            </ul>
            <div class="tab-content">

