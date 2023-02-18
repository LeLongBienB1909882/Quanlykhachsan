<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['submit']))
{
  $fromdate=$_POST['fromdate'];
  $todate=$_POST['todate']; 
  $message=$_POST['message'];
  $useremail=$_SESSION['login'];
  $status=0;
  $vhid=$_GET['vhid'];
  $bookingno=mt_rand(100000000, 999999999);
  $ret="SELECT * FROM tblbooking where (:fromdate BETWEEN date(FromDate) and date(ToDate) || :todate BETWEEN date(FromDate) and date(ToDate) || date(FromDate) BETWEEN :fromdate and :todate) and VehicleId=:vhid";
  $query1 = $dbh -> prepare($ret);
  $query1->bindParam(':vhid',$vhid, PDO::PARAM_STR);
  $query1->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
  $query1->bindParam(':todate',$todate,PDO::PARAM_STR);
  $query1->execute();
  $results1=$query1->fetchAll(PDO::FETCH_OBJ);

  if($query1->rowCount()==0)
  {

    $sql="INSERT INTO  tblbooking(userEmail,VehicleId,FromDate,ToDate,message,Status,BookingNumber) VALUES(:useremail,:vhid,:fromdate,:todate,:message,:status,:bookingno)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
    $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
    $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
    $query->bindParam(':todate',$todate,PDO::PARAM_STR);
    $query->bindParam(':message',$message,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->bindParam(':bookingno',$bookingno,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
      echo "<script>alert('Đặt thành công.');</script>";
      echo "<script type='text/javascript'> document.location = 'my_booking.php'; </script>";
    }
    else 
    {
      echo "<script>alert('Đã xảy ra lỗi. Vui lòng thử lại');</script>";
    } 
  }  else{
   echo "<script>alert('Xe đã được đặt những ngày này');</script>"; 
 }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Đặt khách sạn | Chi tiết khách sạn</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
  <meta content="Author" name="WebThemez">
  <!-- Favicons -->
  <link href="img/logo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet"> 
</head>

<body id="body"> 
 <?php include('includes/header.php');?>
 <section id="innerBanner"> 
  <div class="inner-content">
    <h2><span>GIỚI THIỆU KHÁCH SẠN</span></h2>
    <div> 
    </div>
  </div> 
</section><!-- #Page Banner -->

<main id="main">
    <!--==========================
      Clients Section
      ============================-->
      <section id="clients"  class="wow fadeInUp">
        <div class="container">
          <div class="section-header">
            <h2>Thông tin khách sạn</h2>
          </div>
          <?php 
          $vhid=intval($_GET['vhid']);
          $sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid  from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.id=:vhid";
          $query = $dbh -> prepare($sql);
          $query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
          $query->execute();
          $results=$query->fetchAll(PDO::FETCH_OBJ);
          $cnt=1;
          if($query->rowCount() > 0)
          {
            foreach($results as $result)
            {  
              $_SESSION['brndid']=$result->bid;  
              ?>  
              <div class="owl-carousel clients-carousel">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="" style="height: 150px; width:300px;">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" alt="" style="height: 150px; width: 300px;">
              </div>
            </div>
          </section><!-- #clients -->

          <!--Listing-detail-->
          <section class="listing-detail">
            <div class="container">
              <div class="listing_detail_head row">
                <div class="col-md-9">
                  <h2><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h2>
                </div>
                <div class="col-md-3">
                  <div class="price_info">
                    <p><?php echo htmlentities($result->PricePerDay);?> </p> VND / Ngày

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-9">
                  <div class="listing_more_info">
                    <div class="listing_detail_wrap"> 
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs gray-bg" role="tablist">
                        <li><a href="#vehicle-overview " aria-controls="vehicle-overview" role="tab" data-toggle="tab">Địa chỉ</a></li>
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content"> 
                        <!-- vehicle-overview -->
                        <div role="tabpanel" class="tab-pane active" id="vehicle-overview">

                          <p><?php echo htmlentities($result->VehiclesOverview);?></p>
                        </div>
                    </div>
                  </div>

                </div>
                <?php 
              }
            } ?>

          </div>

          <!--Side-Bar-->
          <aside class="col-md-3">

            <div class="sidebar_widget">
              <div class="widget_heading">
                <h5><i class="fa fa-envelope" aria-hidden="true"></i>Đặt ngay</h5>
              </div>
              <form method="post">
                <div class="form-group">
                  <label>Từ ngày:</label>
                  <input type="date" class="form-control" name="fromdate" placeholder="Từ ngày" required>
                </div>
                <div class="form-group">
                  <label>Đến ngày:</label>
                  <input type="date" class="form-control" name="todate" placeholder="Đến ngày" required>
                </div>
                <div class="form-group">
                  <textarea rows="4" class="form-control" name="message" placeholder="Ghi chú"></textarea>
                </div>
                <?php if($_SESSION['login'])
                {?>
                  <div class="form-group">
                    <input type="submit" class="btn" style="background-color: #49a3ff;"  name="submit" value="Đặt ngay">
                  </div>
                  <?php 
                } else { ?>
                  <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal" style="background-color: #49a3ff;">Đăng nhập để tiếp tục</a>

                  <?php 
                } ?>
              </form>
            </div>
          </aside>
          <!--/Side-Bar--> 
        </div>
   </section>
    </main>

    <?php include('includes/footer.php');?><!-- #footer -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    <!--Login-Form -->
    <?php include('includes/login.php');?>
    <!--/Login-Form --> 

    <!--Register-Form -->
    <?php include('includes/registration.php');?>

    <!--/Register-Form --> 

    <!--Forgot-password-Form -->
    <?php include('includes/forgotpassword.php');?>

    <!-- JavaScript  -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/superfish/hoverIntent.js"></script>
    <script src="lib/superfish/superfish.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/magnific-popup/magnific-popup.min.js"></script>
    <script src="lib/sticky/sticky.js"></script> 
    <script src="contact/jqBootstrapValidation.js"></script>
    <script src="contact/contact_me.js"></script>
    <script src="js/main.js"></script>

  </body>
  </html>
