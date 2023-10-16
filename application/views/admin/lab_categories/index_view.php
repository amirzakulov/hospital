<?php $this->load->view('admin/laboratory/header_template_view'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0 datatable1">
                <thead>
                <tr>
                    <th>#</th>
                    <th>У/Н</th>
                    <th><?= lang("lab_category_name"); ?></th>
					<th><?= lang("lab_category_division_name"); ?></th>
					<th><?= lang("lab_category_sort"); ?></th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($lab_categories as $counter => $lab_category) {?>
                <tr id="js_row_<?= $lab_category["id"] ?>">
                    <td><?= ++$counter; ?></td>
                    <td style="text-transform: uppercase"><?= $lab_category["code"]; ?></td>
                    <td><?= $lab_category["name"]; ?></td>
                    <td><?= $lab_category["division_name"]; ?></td>
                    <td><?= $lab_category["sort"]; ?></td>
                    <td class="text-right">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url("admin/lab_categories/edit/".$lab_category['id']); ?>"><i class="fa fa-pencil m-r-5"></i>  <?= lang("general_edit"); ?></a>
                                <a class="dropdown-item js_delele_item" href="javascript:void (0);" data-href="<?= site_url("admin/lab_categories/delete/"); ?>" data-id="<?= $lab_category['id'] ?>"><i class="fa fa-trash-o m-r-5"></i>  <?= lang("general_delete"); ?></a>
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
