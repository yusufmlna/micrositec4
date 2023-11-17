<div class="row">
   <div class="col-md-8">
      <div class="card">
         <div class="card-header card-header-primary">
            <h4 class="card-title">Upload File</h4>
            <p class="card-category">Upload Formatted File Here (Only .xlsx file is supported)</p>
         </div>
         <div class="card-body">
            <form enctype="multipart/form-data" method="POST" action="<?=base_url('products_upload/upload');?>">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-file-upload form-file-multiple">
                        <input type="file" name="products_file" class="inputFileHidden" accept=".xlsx" required>
                        <div class="input-group">
                           <input type="text" class="form-control inputFileVisible" placeholder="Select File">
                           <span class="input-group-btn">
                           <button type="button" class="btn btn-fab btn-round btn-primary">
                           <i class="material-icons">attach_file</i>
                           </button>
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary pull-right">Upload File</button>
               <div class="clearfix"></div>
            </form>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card card-profile">
         <div class="card-body">
            <h3 class="card-title">Download Template</h3>
            <a href="<?=base_url('assets/files/product_upload_template.xlsx');?>" class="btn btn-primary btn-round">Download</a>
         </div>
      </div>
   </div>
</div>