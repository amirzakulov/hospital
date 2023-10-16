<?php $this->load->view('admin/laboratory/header_template_view'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <input id="lab_search" class="form-control search-items" name="search" placeholder="Қидириш..." type="text" data-list=".js_lab_search" aria-describedby="button-addon2">
            <table class="table table-striped custom-table mb-0 datatable1">
                <thead class="">
                <tr class="bg-light">
                    <th width="5px">#</th>
                    <th width="5px">У/Н</th>
                    <th width="300px"><?= lang("laboratory_name"); ?></th>
                    <th width="100px"><?= lang("laboratory_default_value"); ?></th>
                    <th><?= lang("laboratory_sort"); ?></th>
                    <th><?= lang("laboratory_price"); ?></th>
                    <th><?= lang("laboratory_price_partner"); ?></th>
                    <th><?= lang("laboratory_partners"); ?></th>
                    <th></th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody class="js_lab_search">
                <?php foreach ($laboratories as $category) {?>
                    <?php $color = ($category["active"] == 1) ? "":"#c3b8b8 !important;"; ?>
                <tr class="bg-green" style="background-color: <?= $color; ?>">
                    <td colspan="9" class="font-weight-bold p-1">
                        <span class="text-uppercase"><?= $category["name"]; ?></span>
                        <a href="<?= site_url("admin/laboratory/add/".$category["id"]) ?>" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> <?= lang("laboratory_add_button") ?></a>
                    </td>
                    <td></td>
                </tr>
                <?php if(count($category["sub"]) == 0) {?>
                <tr>
                    <td colspan="9" class="text-center">Лаборатория қўшилмаган</td>
                </tr>
                <?php } else { ?>
                    <?php $counter = 1; ?>
                    <?php foreach ($category["sub"] as $laboratory) {?>
                    <?php $color = ($category["active"] == 1 && $laboratory["active"] == 1) ? "#000":"#c3b8b8"; ?>
                    <tr id="js_row_<?= $laboratory["id"] ?>" style="color: <?= $color; ?>">
                        <td><?= $counter++; ?></td>
                        <td><?= $laboratory["code"]; ?></td>
                        <td><?= $laboratory["name"]; ?></td>
                        <td><?= count($laboratory["sub"]) > 0 ? "":$laboratory["default_value"]; ?></td>
                        <td class="text-center"><?= $laboratory["sort"]; ?></td>
                        <td><?= $laboratory["price"]; ?></td>
                        <td><?= $laboratory["price_partner"]; ?></td>
                        <td><?= $laboratory["company"]; ?></td>
                        <td><?php if(count($laboratory["sub"]) > 0) { ?>
                                <a href="<?= site_url("admin/laboratory/sublabs/".$laboratory["id"]) ?>" class="btn btn-success float-right text-right" style="width: 161px"><span class="fa fa-eye"></span> <?= lang("laboratory_sublab"); ?></a>
                            <?php } ?>
                        </td>
                        <td class="text-right" width="50">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= site_url("admin/laboratory/sublabs_add/".$laboratory["id"]) ?>"><i class="fa fa-plus m-r-5 text-info"></i> <?= lang("laboratory_sublab_add_button"); ?></a>
                                    <a class="dropdown-item" href="<?= site_url("admin/laboratory/edit/".$laboratory["id"]) ?>"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                                    <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="<?= site_url("admin/laboratory/delete/") ?>" data-id="<?= $laboratory["id"] ?>"><i class="fa fa-trash-o m-r-5 text-danger"></i> <?= lang("general_delete"); ?></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('admin/laboratory/footer_template_view'); ?>
