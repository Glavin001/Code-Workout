<?PHP

$exerciseName = $_POST['name'];
$exerciseInfo = $_POST['info'];

$command_1 = $_POST['command_1'];
$in_1 = $_POST['in_1'];
$out_1 = $_POST['out_1'];

$root = "UploadedExercises/";

if (validateInput())
{
  if (!is_dir($root.$exerciseName)) {
      mkdir($root.$exerciseName);
      chmod($root.$exerciseName, 0777);
      //chown($root.$exerciseName, "g_wiechert");
      
      file_put_contents($root.$exerciseName."/info.txt", $exerciseInfo);
      chmod($root.$exerciseName."/info.txt", 0777);
      //chown($root.$exerciseName."/info.txt", "g_wiechert");
      mkdir($root.$exerciseName."/submissions/");
      chmod($root.$exerciseName."/submissions/", 0777);
      //chown($root.$exerciseName."/submissions/", "g_wiechert");
      
      mkdir($root.$exerciseName."/testCases/");
      chmod($root.$exerciseName."/testCases/", 0777);
      //chown($root.$exerciseName."/testCases/", "g_wiechert");
      file_put_contents($root.$exerciseName."/testCases/"."command_1.txt", $command_1);
      chmod($root.$exerciseName."/testCases/"."command_1.txt", 0777);
      //chown($root.$exerciseName."/testCases/"."command_1.txt", "g_wiechert");
      file_put_contents($root.$exerciseName."/testCases/"."in_1.txt", $in_1);
      chmod($root.$exerciseName."/testCases/"."in_1.txt", 0777);
      //chown($root.$exerciseName."/testCases/"."in_1.txt", "g_wiechert");
      file_put_contents($root.$exerciseName."/testCases/"."out_1.txt", $out_1);
      chmod($root.$exerciseName."/testCases/"."out_1.txt", 0777);
      //chown($root.$exerciseName."/testCases/"."out_1.txt", "g_wiechert");
      // Successful
      header("Location: exercise.php?name=".$_POST['name']);
   
  }
  else
  {
    echo "Exercise ".$exerciseName." already exists.";
  }
}
else
{

}

function validateInput()
{
  return true;
}

?>