<?php
  
  if(isset($_GET['data']) && $_GET['data'] != "") {
 
    require_once "../config/settings.php";
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
 
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if(mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: ".mysqli_connect_error();
      die();
    }
    $db->set_charset("utf8");
 
    $SQL  = null;
    $JSON = null;
    $DATA = trim($_GET['data']);
    $_ID  = trim($_GET['id']);
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type:Application/json');
 
    switch($DATA) {

      case "last":
        $SQL = "SELECT * FROM clipper_items WHERE own='1' ORDER BY item_id DESC LIMIT 104";
      break;
      
      case "sets":
        $SQL = "SELECT * FROM clipper_sets ORDER BY image, name ASC";
      break;
 
      case "items":
        $SQL = "SELECT * FROM clipper_items WHERE own='1' ORDER BY image, name ASC";
      break;
      
      case "miss":
        $SQL = "SELECT * FROM clipper_items WHERE own='0' ORDER BY image, name ASC";
      break;
 
      case "set":
        if(isset($_ID) && $_ID != " ") {
          $SQL = "SELECT cs.set_id AS set_id, cs.name AS set_name, cs.own AS set_own, ci.item_id AS item_id, ci.name AS name, ci.image AS image, ci.own AS own, ci.tag_id AS tag_id, ci.datum AS datum, ci.modified AS modified FROM clipper_sets cs JOIN clipper_items ci ON cs.set_id = ci.set_id WHERE ci.set_id='".$_ID."' ORDER BY cs.name, ci.name ASC;";
        } else {
          die("<div style='position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);'>Keine ID angegeben!</div>");
        }
      break;
 
      case "stats":
        $SQL = "SELECT count(*) AS sets_gesamt, (SELECT count(*) FROM clipper_sets WHERE own='1') AS sets_komplett, (SELECT count(*) FROM clipper_items WHERE own='1') AS items_gesamt FROM clipper_sets";
      break;
    }
 
    if($SQL != null) {
      $res = $db->query($SQL);
      $dataArray = array();
      while($row = $res->fetch_assoc()) {
        $dataItem = array();
        $dataItem = $row;
	    array_push($dataArray, $dataItem);
      }
      $JSON = array("clipper" => $dataArray);
    } 
	
    echo json_encode($JSON, JSON_PRETTY_PRINT);      
    $db->close();
    
  } else {
    die("<div style='position:absolute;left:50%;top:50%;transform:translate(-50%, -50%);'><div style='color:#FF0000;font-weight:bold;font-size:35px;'><strong>!ERROR!</strong></div></div>");
  }
 
?>
 
