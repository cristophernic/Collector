<?php 


require '../initiateCollector.php';
ob_end_clean(); // no need to transmit useless data
ini_set('html_errors', false);

$file             = $_POST['file'];
$data             = $_POST['data'];

$read_write_list       = $_POST['read_write_list'];

//check the filename is a json
if ($file === '' OR preg_match('/[^a-zA-Z0-9._ -]/', $file) !== 0) {
    exit('error: invalid json name: "' . $file . '"');
} else {
  if (strtolower(substr($file, -5)) === '.json') {
    // that is good
  } else {
    $file_exploded=explode(".",$file);
    if(count($file_exploded)>1){
      exit("error: invalid file type submitted"); // might be a bit harsh, maybe automatically rename
    } else {
      $file = "$file.json";
    }
  }
}

$file_directory = $FILE_SYS-> get_path("Jsons");
$file_location  = "$file_directory/$file";
if($read_write_list == "Read"){
    echo file_get_contents($file_location);
} 
if($read_write_list == "Write"){
    file_put_contents($file_location,$data);
    echo $file_location;
    echo $data;
}
if($read_write_list == "List"){
    $json_path = $FILE_SYS-> get_path("Jsons");
    $file = str_replace(".json","",$file);
    //echo "$json_path/$file"."*.json";
    //$files = glob("$json_path/$file"."*.json");
    $files = glob("$json_path/Mousetracker*");
    foreach($files as &$file){
        $filename = explode("/",$file);
        $file = $filename[count($filename) -1];        
    }
    echo json_encode($files);

}



//echo ("$file,$data,$experiment_name,$read_write");



?>