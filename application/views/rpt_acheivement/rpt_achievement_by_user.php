<!DOCTYPE html>
<html lang="en">
<head>
  <title>Report Achievement by User</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
	<link rel="stylesheet" href="../assets/bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<script src="../assets/bootstrap-3.3.7-dist/js/jquery-3.1.1.min.js"></script>
	<script src="../assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 550px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
        
    /* On small screens, set height to 'auto' for the grid */
    @media screen and (max-width: 767px) {
      .row.content {height: auto;} 
    }
  </style>
</head>
<body>


<div class="container-fluid">
  <div class="row content">
	<br>
    <div class="col-sm-12">
      <div class="well" align="center">
        <h4>Report Achievement by User</h4>
        <p><?=$_REQUEST['dte_frm'];?> s/d <?=$_REQUEST['dte_pto'];?></p>
      </div>
	  <!-- looping disini -->
	  <?php 
	  $itungan = 0;
	  #print_r($all_data_by_user);
	  foreach($all_data_by_user as $dt1){
		  if(0 == ($itungan % 3)){
			print '<div class="row">';
		  }
		  print '<div class="col-sm-4">
			<div class="well">
					<span class="media-left">
						<img src="../assets/img-usr/'.(is_null($dt1->file_name)?"no_photo_available.png":$dt1->file_name) .'" class="img-circle" alt="Cinque Terre" width="150" height="150" /> 	
					</span>
					<div class="media-body">
						<h3 class="media-heading">' . $dt1->user_id . '</h3>
						<h5 class="media-heading">' . $dt1->nama_user . '</h5>
						<div witdh="100%">
							<span class="media-body">
								<img src="../assets/img-usr/red_char.png" width="' . $dt1->jml_achievement . '%" height="25"/> 	
							</span>
						</div>
						<br>
						<div witdh="100%">
							<span class="media-body">
								<img src="../assets/img-usr/yellow_char.png" width="' . $dt1->jml_plan . '%" height="25"/> 	
							</span>
						</div><br>
						<h5 class="media-heading">Achieved: ' . $dt1->jml_achievement . '</h5>
						<h5 class="media-heading">Plan: ' . $dt1->jml_plan . '</h5>
					</div>
					
				</div>
			</div>';
			
		if(2 == ($itungan % 3)){
			print '</div>';
		  }
		  $itungan++;
	  }
	  ?>
      
    </div>
  </div>
</div>

</body>
</html>
