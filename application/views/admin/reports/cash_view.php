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
                <table class="table table-border custom-table mb-0 compact">
                    <thead>
                    <tr class="bg-dark text-white">
                        <th>Тўланди</th>
                        <th>Қарз</th>
                        <th>Жами</th>
                        <th>Тўлов тури</th>
                    </tr>
                    </thead>
                    <?php if(count($reports) > 0) { ?>
                    <?php foreach ($reports as $report) { ?>
                    <tr>
                        <td><?= $report["paid"]; ?></td>
                        <td><?= $report["debt"]; ?></td>
                        <td><?= $report["total"]; ?></td>
                        <td><?= $payment_types[$report["payment_type"]]; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td colspan="4">Тўлов бажарилмаган</td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <div class="col-md-6"></div>
</div>
