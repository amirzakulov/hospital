<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-8 col-9 text-right m-b-20"></div>
</div>
<div class="row">
    <div class="col-md-8">
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
            <div class="col-sm-12" style="">
                <table class="table table-border custom-table mb-0 compact datatableY">
                    <thead>
                    <tr class="bg-dark text-white">
                        <th>ФИШ</th>
                        <th>Тўланди</th>
                        <th>Қарз</th>
                        <th>Жами</th>
                        <th>Тўлов тури</th>
                        <th>Тўлов куни</th>
                        <th>Хизматлар</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total = $paid = $debt = 0; ?>
                    <?php if(count($payments) > 0) { ?>
                    <?php foreach ($payments as $report) { ?>
                    <tr>
                        <td><?= $report["last_name"]." ".$report["first_name"]; ?></td>
                        <td><?= $report["paid"]; ?></td>
                        <td><?= $report["debt"]; ?></td>
                        <td><?= $report["total"]; ?></td>
                        <td><?= $payment_types[$report["payment_type"]]; ?></td>
                        <td><?= date("d.m.Y H:i", strtotime($report["created_date"])); ?></td>
                        <td>
                            <?= $report["doctor_status"] > 0 ? " <span data-toggle='tooltip' data-placement='top' title='Шифокор қабули'>Ш</span> ":""; ?>
                            <?= $report["laboratory_status"] > 0 ? " <span data-toggle='tooltip' data-placement='top' title='Лаборатория анализи'>Л</span> ":""; ?>
                            <?= $report["uzi_status"] > 0 ? " <span data-toggle='tooltip' data-placement='top' title='УЗИ анализи'>У</span> ":""; ?>
                            <?= $report["room_status"] > 0 ? " <span data-toggle='tooltip' data-placement='top' title='Хона хизмати'>Х</span> ":""; ?>
                        </td>
                    </tr>
                    <?php $total += $report["total"]; ?>
                    <?php $paid += $report["paid"]; ?>
                    <?php $debt += $report["debt"]; ?>
                    <?php } ?>
                    <?php } ?>
                    <tbody>
                    <?php if(count($payments) > 0) { ?>
                    <tfoot>
                    <tr class="bg-dark text-white">
                        <th>Беморлар сони: <?= count($payments); ?></th>
                        <th><?= money_formatting($paid); ?></th>
                        <th><?= money_formatting($debt); ?></th>
                        <th><?= money_formatting($total); ?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
