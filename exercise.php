<?PHP
$exercise = $_GET['name'];

function getContents($filePath)
{
  if (is_readable($filePath)) 
  {
  if (!$handle = fopen($filePath, 'r')) 
  {
    //echo "Cannot open file ($fullFile)";
    return False;
    exit;
  }
  $contents = fread($handle, filesize($filePath));  
  fclose($handle);
  } 
  else 
  {
    //echo "The file $fullFile is not writable";
  }
  return $contents; 
}

function getDirectoryList($d,$h) 
{
  // create an array to hold directory list
  $results = array();
  // create a handler for the directory
  $handler = opendir($d);
  // open directory and walk through the filenames
  while ($file = readdir($handler)) 
  {  
    // if file isn't this directory or its parent, add it to the results
    if ($file != "." && $file != "..") 
    {
      $results[] = $file;
    }
  }
  // tidy up: close the handler
  closedir($handler);
  // Check if should include hidden files, $h parameter
  if ($h == false)
  { 
  $results = array_filter($results, create_function('$a','return ($a[0]!=".");')); 
  } 
  // done!
  return $results;
}

function getSubmissions($thisExercise)
{
  $h = False;
  $d = "UploadedExercises/".$thisExercise."/submissions/";
  $list = getDirectoryList($d, $h);
  return $list;
}

function getTestCases($thisExercise)
{
  /*
  $caseNum = 1;
  $continuing = True;
  while ($continuing)
  {
    $continuing = False;
  }
  */
  $h = False;
  $d = "UploadedExercises/".$thisExercise."/testCases/";
  $fileList = getDirectoryList($d, $h);
  $testCases = array('command'=>array(), 'in'=>array(), 'out'=>array());
  foreach($fileList as $curr)
  {
    $temp = explode("_", $curr);
    $type = $temp[0];
    $num = $temp[1];
    array_push($testCases[$type], $num);
  }
  return $testCases;
}

function compileSubmission($thisExercise, $filename)
{
  $uDir = "UploadedExercises/".$thisExercise."/submissions/";
  $ext1 = ".cpp";
  $ext2 = ".out";
  // Run code
  $c11 = True;
  $command1="cd ".$uDir."; g++ ".(($c11)?" -std=c++0x ":"").$filename.$ext1." -o ".$filename.$ext2." 2>&1; chmod 777 ".$filename.$ext2.";";
  //echo $command1 . "<br />";
  $console = shell_exec($command1);
  //echo $console . "<br />";
  return $console;
}

function runSubmission($thisExercise, $filename, $commandParameters)
{
  $uDir = "UploadedExercises/".$thisExercise."/submissions/";
  $ext1 = ".cpp";
  $ext2 = ".out";
  // Run code
  $c11 = True;
  $command1="cd ".$uDir."; g++ ".(($c11)?" -std=c++0x ":"").$filename.$ext1." -o ".$filename.$ext2." 2>&1; chmod 777 ".$filename.$ext2.";";
  //echo $command1 . "<br />";
  //$console = shell_exec($command1);
  //echo $console . "<br />";
  $output = $console;
  if ($output=="")
  {
    //$command2 = "cd ".$uDir."; ./".$filename.$ext2." < ".$inputFile." 2>&1";
    $command2 = "cd ".$uDir."; ./".$filename.$ext2." ".$commandParameters." 2>&1";
    //echo $command2 . "<br />";
    $run = shell_exec($command2);
    /*
    // Display output
    <textarea  name="output" id="output" readonly="readonly"><? echo $run; ?></textarea><br />
    */
    $output = $run;
  }
  return $output;
}

?>

<!DOCTYPE html>
<html>

<?PHP
if ($exercise != "" && is_dir("UploadedExercises/".$exercise))
{
?>

<head>

<title><?PHP echo $exercise; ?> - Submissions</title>

<style>
body
{
  margin: 10px;
}

span.exerciseTitle
{
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1.5em;
}

span.subTitle
{
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1.2em;
}


div.info
{
  border: 1px dotted grey;
  padding: 10px;
}

div.submissions
{
  margin: 10px 0;
  border: 1px dotted grey;
  padding: 10px;
}

div.submitExercise
{
  margin: 10px 0;
  border: 1px dotted grey;
  padding: 10px;
}

div.submissions table
{
  border: 1px solid black;
  width: 100%;
  background-color: rgba(0,0,0,0.2);
  border-radius: 5px;
}

div.submissions table th
{
  border: 1px solid black;
  text-transform: uppercase;
  font-family: bold;
  padding: 5px;
  background-color: rgba(255,255,255,0.7);

}

div.submissions table tr td
{
  border: 1px solid black;
  padding: 5px;
}

div.submissions table tr.correct, span.correct
{
  background-color: rgba(0,255,0,0.2);
}
div.submissions table tr.correct:hover
{
  background-color: rgba(0,255,0,0.5);
}

div.submissions table tr.wrong, span.wrong
{
  background-color: rgba(255,0,0,0.2);
}
div.submissions table tr.wrong:hover
{
  background-color: rgba(255,0,0,0.5);
}

.fileLink:hover:after
{
  content: " (Click here to view code)"
}

.codeOutput
{
  padding: 2px;
  background-color: rgba(255,255,255,0.5);
  border: 1px dotted grey;
}
.codeOutput:hover
{
  background-color: rgba(255,255,255,0.8);
  border: 1px solid black;
}

label
{
  font-weight: bold;
}

</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
<script>
$(function () {
  window.isRefreshing = false;
  window.refreshInterval = 10000;
  window.refreshSubmissions = function () {
    if (!window.isRefreshing)
    { 
      window.isRefreshing = true;
      $.ajax({
      type: "POST",
      url: "check_submissions.php",
      data: { name : "<?PHP echo $exercise; ?>" },
      dataType: "html"
      }).done(function( data ) {
        //console.log(data);
        document.getElementById("checkedSubmissions").innerHTML = data;
        window.isRefreshing = false;      
      });
    } 
  } 
  window.refreshSubmissions();
  setInterval(window.refreshSubmissions, refreshInterval);
});
</script>

</head>

<body>

<span class="exerciseTitle"><?PHP echo $exercise; ?></span>
<small><a href="exercise.php">Click here to choose another Exercise.</a></small>

<div class="info">
<span class="subTitle">Exercise Info</span><br />
<?PHP
$fullFile = "UploadedExercises/".$exercise."/"."info.txt";
if ($contents = getContents($fullFile)) echo nl2br($contents);
?>
<!--
<hr />
<br />
<div>
Submit your own exercise submission <a href="exercise.php?name=<?PHP echo $exercise; ?>&submitExercise=True">here</a>.
</div>
-->
</div>

<?PHP
//if ($_GET['submitExercise'] == "True" || $_GET['submitExercise'] == "true")
//{
?>
<div class="submitExercise">
<span class="subTitle">Submit Your Exercise Solution</span>
<form action="save_submission.php" method="post"
enctype="multipart/form-data">
<input type="hidden" id="exercise" name="exercise" value="<?PHP echo $exercise;?>" />
<br />
<label for="username">A-Number:</label>
<input type="text" id="username" name="username"></input>
<br />
<label for="file">Filename:</label>
<input type="file" name="file" id="file">
<br />
<input type="submit" name="submit" value="Submit">
</form>
</div>
<?PHP
//}
?>

<div class="submissions">
<span class="subTitle">Check Submissions</span>
<button onclick="refreshSubmissions();" > Refresh</button>
<table id="checkedSubmissions">
<?PHP
/*
$submissions =  getSubmissions($exercise);
//print_r($submissions) . "<br />";
for ($i=0;$i<count($submissions);$i++)
{
  $currFile = $submissions[$i];
  $extension = end(explode(".", $currFile));
  if ($extension == "cpp")
  {
    $temp = explode(".", $currFile);
    //print_r($temp);
    $currUser = $temp[0];
    
    // Get last modified time
    $modifiedTime = filemtime("UploadedExercises/".$exercise."/submissions/".$currFile);
    
    // Run & check submission
    $testCases = getTestCases($exercise);
    $output = "";
    $isCorrect = True;
    $compileSuccessful = False;
    foreach ($testCases['command'] as $currNum)
    {
      $currCommand = "command_".$currNum;
      $commandPath = "UploadedExercises/".$exercise."/testCases/".$currCommand;
      $commandParameters = strtok(getContents($commandPath), "\n");
      //echo $commandPath;
      //echo $commandParameters;
      //print_r($testCases);
      //echo "<br />";
      $output .= "<u>Command Line Parameters: <strong>".$commandParameters."</strong></u><br />";
      $temp = compileSubmission($exercise, $currUser, $commandParameters);
      if ($temp == "")
      {
        $compileSuccessful = True;
        $runOutput = runSubmission($exercise, $currUser, $commandParameters);
        $output .= "<div class=\"codeOutput\">".$runOutput."</div>";
        $caseOutputPath = "UploadedExercises/".$exercise."/testCases/"."out_".$currNum;
        $caseOutput = getContents($caseOutputPath);
        if ($runOutput != $caseOutput)
        {
          $isCorrect = False;
          $output .= "<span class=\"wrong\">Wrong output.</span><br />";
          $output .= "Should be: "."<div class=\"codeOutput\">".$caseOutput."</div>";
        }
        else
        {
          $output .= "<span class=\"correct\">Correct output.</span><br />";
        }
      }
      else
      {
        $isCorrect = False;
        $output .= $temp;
      }
    }

    
    ?>
    <tr class="<?PHP echo ($isCorrect)?"correct":"wrong"; ?>">
    <td><a class="fileLink" href="<?PHP echo "UploadedExercises/".$exercise."/submissions/".$currFile; ?>"><?PHP echo $currUser; ?></a></td>
    <td><?PHP echo  date ("F d Y H:i:s.", $modifiedTime ); ?></td>
    <td><pre><?PHP echo (($compileSuccessful)?$output:"<span class=\"wrong\">Could not compile:</span> <br />".$output); ?></pre></td>
    <td><?PHP echo ($isCorrect)?"Correct!":"Wrong."; ?></td>
    </tr>
    <?PHP
    
  }
}
*/
?>
</table>
</div>

</body>

<?PHP
} 
else
{
  //echo "<div>Incorrect exercise name.</div>";
  ?>
  <head>
  
  <style>
  span.mainTitle
  {
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1.5em;
  }
  
  span.subTitle
  {
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1.2em;
  }
   
  div.main
  {
  border: 1px dotted grey;
  padding: 10px;
  }
  </style>
  
  </head>
  
  <body>
  
  <div class="main">
  <span class="mainTitle">Create An Exercise</span>
  <div>
  Create your own exercise <a href="create_exercise.php">here</a>.
  </div>
  <br />
  <hr />
  <br />
  <span class="mainTitle">Choose an Exercise</span>
  <?PHP
  $h = False;
  $d = "UploadedExercises/";
  $allExercises = getDirectoryList($d, $h);
  echo "<div>Please select a valid exercise by clicking one of these links:</div>";
  echo "<ul>";
  foreach ($allExercises as $exercise)
  {
    echo '<li><a href="exercise.php?name='.$exercise.'">'.$exercise.'</a></li>';
  }
  ?>
  </ul>
  </div>
  </body>
  <?PHP
}

?>

</html>