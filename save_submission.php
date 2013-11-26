<?php
$overwrite = True; // Should overwrite if file already exists.
$_FILES["file"]["name"] = "".$_POST['username'].".cpp";
$_FILES["file"]["type"] = "text/plain";
if (validateInput())
{
  // All okay to save file!
  $saveDir = "UploadedExercises/" . $_POST['exercise'] ."/"."submissions/";
  if ($_FILES["file"]["error"] > 0)
  {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  }
  else
  {
    /*
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
    */
    if (!$overwrite && file_exists($saveDir. $_FILES["file"]["name"]))
    {
      chmod($saveDir. $_FILES["file"]["name"], 0777);
      echo $_FILES["file"]["name"] . " already exists. ";
    }
    else
    {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      $saveDir. $_FILES["file"]["name"]);
      chmod($saveDir. $_FILES["file"]["name"], 0777);
      //echo "Stored in: " . $saveDir. $_FILES["file"]["name"];
      
      // Successful
      header("Location: exercise.php?name=".$_POST['exercise']);
    }
  }
}

function validateInput()
{
  if ($_FILES["file"]["size"] > 20000)
  {
    echo "File too large.";
    return False;
  }
  
  $allowedExts = array("cpp");
  $extension = end(explode(".", $_FILES["file"]["name"]));
  if (!in_array($extension, $allowedExts))
  {
    echo "Invalid file type: ".$extension;
    return False;
  }
  
  return True;
}

?>
