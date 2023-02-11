<?php
include('includes/checklogin.php');
check_login();
if(isset($_POST['save']))
{
  $vehicletitle=$_POST['biketitle'];
  $brand=$_POST['brandname'];
  $vehicleoverview=$_POST['description'];
  $priceperday=$_POST['priceperday'];
  $vimage1=$_FILES["img1"]["name"];
  $vimage2=$_FILES["img2"]["name"];

  move_uploaded_file($_FILES["img1"]["tmp_name"],"img/vehicleimages/".$_FILES["img1"]["name"]);
  move_uploaded_file($_FILES["img2"]["tmp_name"],"img/vehicleimages/".$_FILES["img2"]["name"]);

  $sql="INSERT INTO tblvehicles(VehiclesTitle,VehiclesBrand,VehiclesOverview,PricePerDay,Vimage1,Vimage2) VALUES(:vehicletitle,:brand,:vehicleoverview,:priceperday,:vimage1,:vimage2)";
  $query = $dbh->prepare($sql);
  $query->bindParam(':vehicletitle',$vehicletitle,PDO::PARAM_STR);
  $query->bindParam(':brand',$brand,PDO::PARAM_STR);
  $query->bindParam(':vehicleoverview',$vehicleoverview,PDO::PARAM_STR);
  $query->bindParam(':priceperday',$priceperday,PDO::PARAM_STR);
  $query->bindParam(':vimage1',$vimage1,PDO::PARAM_STR);
  $query->bindParam(':vimage2',$vimage2,PDO::PARAM_STR);
  $query->execute();
  $lastInsertId = $dbh->lastInsertId();
  if($lastInsertId)
  {
    echo '<script>alert("Xác nhận thành công")</script>';
  }
  else 
  {
    echo '<script>alert("Cập nhật thất bại. Vui lòng thử lại")</script>'; 
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <?php @include("includes/header.php");?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_sidebar.html -->
      <?php @include("includes/sidebar.php");?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class=" col -md-12 card">
              <div class="modal-header">
                <h5 class="modal-title" style="float: left;">Quản lý thêm khách sạn</h5>
              </div>
              <div class="col-md-12 mt-4">
                <div class="row ">
                  <div class="form-group col-md-6 ">
                    <label for="exampleInputPassword">Loại<span style="color:red">*</span></label>
                    <select  name="brandname"  class="form-control" required>
                      <option value="">Hotel </option>
                      <?php
                      $sql="SELECT * from tblbrands";
                      $query = $dbh -> prepare($sql);
                      $query->execute();
                      $results=$query->fetchAll(PDO::FETCH_OBJ);
                      if($query->rowCount() > 0)
                      {
                        foreach($results as $row)
                        {
                          ?> 
                          <option value="<?php  echo $row->id;?>"><?php  echo $row->BrandName;?></option>
                          <?php 
                        }
                      } ?>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="exampleInputName1">Tên khách sạn<span style="color:red">*</span></label>
                    <input type="text" name="biketitle" class="form-control" value="" id="product" placeholder="Nhập tên xe" required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-12">
                    <label for="exampleInputName1">Địa chỉ<span style="color:red">*</label>
                     <textarea class="form-control" name="description" rows="3" required placeholder="Thêm mô tả"></textarea>
                   </div>
                 </div>
                 <div class="row">
                   <div class="form-group col-md-3">
                    <label for="exampleInputName1">Giá theo ngày (VND)<span style="color:red">*</label>
                      <input type="text" name="priceperday" value="" placeholder="Giá thuê / ngày" class="form-control" id="price"required>
                    </div>
                    </div>
                      <div class="row ">
                       <div class="form-group col-md-4 pl-md-0">
                        <label class="col-sm-12 pl-0 pr-0 ">Ảnh 1<span style="color:red">*</label>
                          <div class="col-sm-12 pl-0 pr-0">
                            <input type="file" name="img1" class="file-upload-default">
                            <div class="input-group ">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Thêm hình ảnh">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" style="" type="button">Cập nhật</button>
                              </span>
                            </div>
                          </div>
                        </div> 
                        <div class="form-group col-md-4 pl-md-0">
                          <label class="col-sm-12 pl-0 pr-0 ">Ảnh 2<span style="color:red">*</label>
                            <div class="col-sm-12 pl-0 pr-0">
                              <input type="file" name="img2" class="file-upload-default">
                              <div class="input-group ">
                                <input type="text" class="form-control file-upload-info" disabled placeholder="Thêm hình ảnh">
                                <span class="input-group-append">
                                  <button class="file-upload-browse btn btn-gradient-primary" style="" type="button">Cập nhật</button>
                                </span>
                              </div>
                            </div>
                          </div>
                          </div>
                 <button type="submit" style="float: right;" name="save" class="btn btn-primary  mr-2 mb-4">Lưu</button>
               </div>
             </div>
           </form>
         </div>
         <!-- content-wrapper ends -->
         <!-- partial:../../partials/_footer.html -->
         <?php @include("includes/footer.php");?>
         <!-- partial -->
       </div>
       <!-- main-panel ends -->
     </div>
     <!-- page-body-wrapper ends -->
   </div>
   <!-- container-scroller -->
   <?php @include("includes/foot.php");?>
   <!-- End custom js for this page -->
 </body>
 </html>