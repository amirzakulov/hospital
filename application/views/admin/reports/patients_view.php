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
                        <span class="font-weight-bold">Бўлимлар</span>
                        <span class="font-weight-bold">Беморлар сони</span>
                    </li>
                    <?php $total = 0; ?>
                    <?php foreach ($reports as $report) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= $report["department"]; ?>
                            <span><?= $report["count"]; ?></span>
                        </li>
                    <?php $total += $report["count"]; ?>
                    <?php } ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center active">
                        <span class="font-weight-bold">Жами</span>
                        <span class="font-weight-bold"><?= $total ?></span>
                    </li>
                </ul>
            </div>
        </div>
    <div class="col-md-6"></div>
</div>
