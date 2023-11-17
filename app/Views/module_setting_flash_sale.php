<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Module Setting > Flash Sale</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('module_setting/flash_sale'); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mt-5">
                                <label for="exampleFormControlSelect1">Input ISBN (Comma Separated)</label>
                                <input type="text" class="form-control" name="isbns" placeholder="9780446310789,9780571334650,9781401398033,9781408891957,9781524711474" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mt-5">
                                <label for="exampleFormControlSelect1">Select End Date</label>
                                <input type="date" class="form-control" name="end_date" required />
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>