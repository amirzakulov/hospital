<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">
        <?php if($this->uri->segment(2) == "lab_categories") { ?>
        <a href="<?= site_url("admin/lab_categories/add") ?>" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("lab_category_add") ?></a>
        <?php } else if ($this->uri->segment(2) == "lab_divisions") { ?>
			<a href="<?= site_url("admin/lab_divisions/add") ?>" class="btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> <?= lang("lab_divisions_add") ?></a>
        <?php } ?>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-box">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified nav-justified--block">
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(2) == "laboratory" ? "active":"" ?>" href="<?= $this->uri->segment(2) == "laboratory" ? "javascript:void(0);":site_url("admin/laboratory") ?>">Лабораториялар</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(2) == "lab_categories" ? "active":"" ?>" href="<?= $this->uri->segment(2) == "lab_categories" ? "javascript:void(0);":site_url("admin/lab_categories") ?>">Категориялар</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(2) == "lab_divisions" ? "active":"" ?>" href="<?= $this->uri->segment(2) == "lab_divisions" ? "javascript:void(0);":site_url("admin/lab_divisions") ?>">Бўлимлар</a>
                </li>
            </ul>
            <div class="tab-content">

