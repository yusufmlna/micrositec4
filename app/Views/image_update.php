<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Update Image</h4>
                <p class="card-category">Update Product Image from URL</p>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="<?= base_url('image_update'); ?>">
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
                                    <label for="exampleFormControlSelect1">Image URL</label>
                                    <input type="text" class="form-control" name="url" placeholder="https://image.google.com/logo.png" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Update Image</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>