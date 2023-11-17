<link rel="stylesheet" ref="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Excluded Product List</h4>
                <p class="card-category">List of Product Excluded from Daily Data Update</p>
            </div>
            <div class="card-body">

                <table id="basic-table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Stock</th>
                            <th>Stock Status</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products) { ?>
                            <?php foreach ($products as $p) { ?>

                                <tr>
                                    <td><?= $p->isbn; ?></td>
                                    <td><?= $p->stock; ?></td>
                                    <td><?= stockName($p->stock_status_id); ?></td>
                                    <td><a href="<?=base_url('exclude_product?action=delete&isbn=' . $p->isbn);?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="material-icons">delete</i> DELETE</a></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4">No Data Available</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ISBN</th>
                            <th>Stock</th>
                            <th>Stock Status</th>
                            <th>Delete</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Add Excluded Product</h4>
                <p class="card-category">Exclude Product From Daily Data Update</p>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="<?= base_url('exclude_product'); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">Input ISBN</label>
                                    <input type="text" class="form-control" name="isbn" placeholder="9780446310789" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">Stock</label>
                                    <input type="number" class="form-control" name="stock" placeholder="10" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">Stock Status</label>
                                    <select class="form-control" name="stock_status_id" required>
                                        <option value="">== Select Stock Status ==</option>
                                        <option value="2">Delivered in 3-7 Days</option>
                                        <option value="3">Delivered in 25-40 Days</option>
                                        <option value="4">E-Book</option>
                                        <option value="5">Pre-Order</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Add Excluded Product</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

function stockName($id)
{

    if ($id == '2') {
        return "Delivered in 3-7 Days";
    } else if ($id == '3') {
        return "Delivered in 25-40 Days";
    } else if ($id == '4') {
        return "E-Book";
    } else if ($id == '5') {
        return "Pre-Order";
    }
}

?>