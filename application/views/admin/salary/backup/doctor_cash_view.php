<div class="row">
    <div class="col-md-12"><a class="backurl" href="<?= site_url("admin/salary") ?>"><span class="fa fa-long-arrow-left"></span></a></div>
</div>

<div class="row">
    <div class="col-md-12"><strong>Шифокор</strong>: <?= $doctor["last_name"]." ".$doctor["first_name"] ?></div>
    <div class="col-md-12"><strong><?= lang('salary_start_date'); ?></strong>: <?= date("d.m.Y", strtotime($start_date)); ?></div>
    <div class="col-md-12"><strong><?= lang('salary_end_date');?></strong>: <?= date("d.m.Y", strtotime($end_date)); ?></div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable mb-0 compact">
            <thead class="thead-dark">
            <tr class="text-center">
                <th class="align-text-top" width="22%">Сана</th>
                <th class="align-text-top" width="">Тушум</th>
                <th class="align-text-top text-center">Ш.Улуш (%)</th>
                <th class="align-text-top" width="">Ш.Улуш (сўм)</th>
            </tr>
            </thead>
            <tbody>
            <?php $total_price = $total_cash = 0; ?>
            <?php foreach ($doctor_cash as $cash) {?>
            <tr class="text-center">
                <td class="align-text-top"><?= date("d.m.Y", strtotime($cash["payment_date"])); ?></td>
                <td class="align-text-top"><?= number_format($cash["price"]); ?></td>
                <td class="align-text-top text-center"><?= $cash["percent"]; ?></td>
                <td class="align-text-top">
                    <?php $dcash = ($cash["percent"]/100)*$cash["price"]; echo number_format($dcash); ?>
                </td>
            </tr>
            <?php $total_price += $cash["price"]; ?>
            <?php $total_cash += $dcash; ?>
            <?php } ?>
            </tbody>
            <tfoot class="thead-dark">
            <tr class="text-center">
                <th class="align-text-top"></th>
                <th class="align-text-top"><?= number_format($total_price); ?></th>
                <th class="align-text-top text-center"></th>
                <th class="align-text-top"><?= number_format($total_cash); ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
