<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage {
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
$obj = new main();


class main {

    public function __construct()
    {
        //print_r($_REQUEST);
        //set default page request when no parameters are in URL
        $pageRequest = 'form';
        //check if there are parameters
        if(isset($_REQUEST['page'])) {
            //load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }
        //instantiate the class that is being requested
         $page = new $pageRequest;


        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $page->get();
        } else {
            $page->post();
        }

    }

}

abstract class page {
    protected $html;

    public function __construct()
    {
        $this->html .= '<html>';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';
    }
    public function __destruct()
    {
        $this->html .= '</body></html>';
        strings::printThis($this->html);
    }

    public function get() {
        echo 'default get message';
    }

    public function post() {
        print_r($_POST);
    }
}

class form extends page {

    public function get() {

        $goto = '<form action="index.php" method="post" enctype="multipart/form-data">';
        $goto.= 'Choose a file to be uploaded: <br><br>';
        $goto.= '<input type="file" name="fileToUpload" id="fileToUpload"><br><br>';
        $goto.= '<input type="submit" value="Upload" name="submit">';
        $goto.= '</form>';
        $this->html .= 'form';
        $this->html .= $goto;
    }


   public function post() {
   
   

     $target_dir = "/afs/cad/u/h/k/hk378/public_html/project1/uploads/";
     $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
     print_r($target_file);
     $uploadOk = 1;
     $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} 
   else
 {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
     {
     echo "The   file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    header('Location: https://web.njit.edu/~hk378/project1/index.php?page=table&filename='.$target_file);
        
        }
   else
     {
        echo "Sorry, there was an error uploading your file.";
    }
    }
    }
    }
   
   
   
class table extends page
{

    public function get()
    {
       echo "The file has been uploaded";
  
  $myfile = fopen($_GET['filename'], "r") or die("unable to open file!");
  $html='';
  $html='<table>';
 while(!feof($myfile))
 {
 $html.='<tr>';
 $line = fgetcsv($myfile);
 foreach($line as $value)
 {
 //eccho "$value <br>";
 $html.='<td style="border:1px solid black;">'.$value.'</td>';
 //print_r($html);
 }
 
   $html.='</tr>';
 }
  //print_r($html);
  $html.='</table>';
  print_r($html);
  fclose($myfile);
  //print_r(str_split ($line, ));
  //print_r(array str_getcsv ( string $input [, string $delimiter = "," [, string $enclosure = '"' [string $escape = "\\" ]]] ));
}
}?>