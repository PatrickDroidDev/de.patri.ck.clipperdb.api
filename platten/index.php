<?php
  
  if(isset($_GET['data']) && $_GET['data'] == "audio") {
 
    require_once "../config/settings.php";
    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if(mysqli_connect_errno()) { die("Failed to connect to MySQL: ".mysqli_connect_error()); }
    $db->set_charset("utf8");
 
    $SQL  = null;
    $JSON = null;
    $DATA = trim($_GET['data']);
    $_ID  = trim($_GET['id']);
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type:Application/json');
 
    if(isset($_ID) && !empty($_ID)) {
	    $SQL = "SELECT * FROM ".TBL_PLATTEN." ORDER BY artist, title ASC";
    } else {
	    $SQL = "SELECT * FROM ".TBL_PLATTEN." WHERE track_id='".$_ID."' ORDER BY artist, title ASC";
	  }
 
    if($SQL != null) {
      
      $res = $db->query($SQL);
      $dataArray = array();
      
      while($row = $res->fetch_assoc()) {
        
        $dataItem = array();
        $dataItem = $row;
	      array_push($dataArray, $dataItem);
        
      }
      $JSON = array("platten" => $dataArray);
    } 	
    echo json_encode($JSON, JSON_PRETTY_PRINT);      
    $db->close();
    
  } else {
    die("<div style='position:absolute;left:50%;top:50%;transform:translate(-50%, -50%);'><div style='color:#FF0000;font-weight:bold;font-size:35px;'><strong>!ERROR!</strong></div></div>");
  }
 
?>
