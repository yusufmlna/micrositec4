<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Category Discount</h4>
                <p class="card-category">Setup Discount Based on Category ID</p>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="<?= base_url('category_disc'); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">Category ID</label>
                                    <input type="text" class="form-control" name="category_id" placeholder="136" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">Discount Amount (%)</label>
                                    <input type="text" class="form-control" name="discount_amount" placeholder="10" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" placeholder="" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">End Date</label>
                                    <input type="date" class="form-control" name="end_date" placeholder="" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Setup Discount</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>