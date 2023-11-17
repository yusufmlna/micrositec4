<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Set Products for Journal Module</h4>
                <p class="card-category">Select Module and Input Product's ISBN</p>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('module_products'); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-5">
                                <label for="exampleFormControlSelect1">Select Module</label>
                                <select name="module_id" class="form-control" data-style="btn btn-link" id="exampleFormControlSelect1" required>
                                    <option value="298">Home > Top Picks for You</option>
                                    <option value="296">Home > New Releases</option>
                                    <option value="288">Home > Flash Sale</option>
                                </select>
                            </div>
                            <div class="form-group mt-5">
                                <label for="exampleFormControlSelect1">Input ISBN (Comma Separated)</label>
                                <input type="text" class="form-control" name="isbns" placeholder="9780446310789,9780571334650,9781401398033,9781408891957,9781524711474" required/>
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