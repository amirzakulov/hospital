<?php $this->load->view('admin/laboratory/header_template_view'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0 datatable1">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?= lang("lab_divisions_name"); ?></th>
                    <th><?= lang("lab_divisions_sort"); ?></th>
                    <th><?= lang("lab_divisions_active"); ?></th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($lab_divisions as $counter => $division) {?>
                <tr id="js_row_<?= $division["id"] ?>">
                    <td><?= ++$counter; ?></td>
                    <td><?= $division["name"]; ?></td>
                    <td><?= $division["sort"]; ?></td>
                    <td><?= $division["active"] ? "<span class='text-success'>Фаол</span>":"<span class='text-danger'>Нофаол</span>"; ?></td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/lab_divisions/edit/".$division['id']); ?>"><i class="fa fa-pencil m-r-5"></i>  <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void (0);" data-href="<?= site_url("admin/lab_divisions/delete/"); ?>" data-id="<?= $division['id'] ?>"><i class="fa fa-trash-o m-r-5"></i>  <?= lang("general_delete"); ?></a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('admin/laboratory/footer_template_view'); ?>
