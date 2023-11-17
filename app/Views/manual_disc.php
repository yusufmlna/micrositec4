<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Manual Discount</h4>
                <p class="card-category">Setup Manual Discount Based on Comma Separated ISBNs</p>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="<?= base_url('manual_disc'); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group mt-5">
                                    <label for="exampleFormControlSelect1">ISBNs</label>
                                    <input type="text" class="form-control" name="isbns" placeholder="9781409376415,9781509823987,9781509855124 (NO SPACE ALLOWED!)" required />
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