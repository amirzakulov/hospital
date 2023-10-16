<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20"></div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= form_open() ?>
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
                    <button type="submit" name="show_report" class="btn btn-primary">Кўрсатиш</button>
                </div>
            </div>
        </div>
        <?= form_close(); ?>
        <div class="row">
            <div class="col-sm-12">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center active">
                        <span class="font-weight-bold">Тушум</span>
                        <span class="font-weight-bold"></span>
                    </li>
                    <?php $pure_profit = 0; ?>
                    <?php $total = 0; ?>
                    <?php foreach($total_income as $income) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#"><?= $payment_types[$income["payment_type"]]; ?></a>
                        <span><?= is_null($income["total"]) ? 0:number_format($income["total"],0,","," "); ?></span>
                    </li>
                    <?php $total +=$income["total"]; ?>
                    <?php } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                        <strong>Жами</strong>
                        <strong><?= number_format($total,0,","," "); ?></strong>
                    </li>
                    <?php $pure_profit += $total; ?>
                </ul>

                <ul class="list-group mt-5">
                    <li class="list-group-item d-flex justify-content-between align-items-center active">
                        <span class="font-weight-bold">Шифокорларга</span>
                        <span class="font-weight-bold"></span>
                    </li>
                    <?php $total = 0; ?>
                    <?php foreach($doctors_income as $income) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#"><?= $payment_types[$income["payment_type"]]; ?></a>
                            <span><?= is_null($income["total"]) ? 0:number_format($income["total"],0,","," "); ?></span>
                        </li>
                        <?php $total +=$income["total"]; ?>
                    <?php } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                        <strong>Жами</strong>
                        <strong><?= number_format($total,0,","," "); ?></strong>
                    </li>
                    <?php $pure_profit -= $total; ?>
                </ul>

                <ul class="list-group mt-5">
                    <li class="list-group-item d-flex justify-content-between align-items-center active">
                        <span class="font-weight-bold">Бошқа чиқимлар</span>
                        <span class="font-weight-bold"></span>
                    </li>
                    <?php $total = 0; ?>
                    <?php foreach($expenses as $income) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#"><?= $payment_types[$income["payment_type"]]; ?></a>
                            <span><?= is_null($income["total"]) ? 0:number_format($income["total"],0,","," "); ?></span>
                        </li>
                        <?php $total +=$income["total"]; ?>
                    <?php } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                        <strong>Жами</strong>
                        <strong><?= number_format($total,0,","," "); ?></strong>
                    </li>
                    <?php $pure_profit -= $total; ?>
                </ul>

                <ul class="list-group mt-5">
                    <li class="list-group-item d-flex justify-content-between align-items-center ">
                        <span class="font-weight-bold">Тоза фойда</span>
                        <span class="font-weight-bold"><?= number_format($pure_profit,0,","," "); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    <div class="col-md-6"></div>
</div>
