<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title">Беморлар</h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20">
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-box">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified nav-justified--block">
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) == "" ? "active":"" ?>" href="<?= $this->uri->segment(3) == "" ? "javascript:void(0);":site_url("doctor/patients_uzi") ?>">Қабулдаги беморларим </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link show <?= $this->uri->segment(3) != "" ? "active":"" ?>" href="<?= $this->uri->segment(3) != "" ? "javascript:void(0);":site_url("doctor/patients_uzi/all") ?>">Барча беморларим</a>
                </li>
            </ul>
            <div class="tab-content">

