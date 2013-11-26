<tr>
<th>Submission</th>
<th>Time</th>
<th>Output</th>
<th>Correct?</th>
</tr>
<?PHP
$exercise = $_POST['name'];

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
    
    /*
    // Save $code to file
    $ext1 = ".cpp";
    $ext2 = ".out";
    //echo $fullFile;
    
    write2File($uDir,$uDir.$filename.$ext1,$code);
    
    // Run code
    //$console = shell_exec("python -c '".$code."'");
    $command1="cd ".$uDir."; g++ ".$filename.$ext1." -o ".$filename.$ext2." 2>&1";
    //echo $command1;
    $console = shell_exec($command1);
    //echo $console;
    $output = $console;
    if ($output=="")
    {
    $command2 = "cd ".$uDir."; ./".$filename.$ext2." < ".$inputFile." 2>&1";
    //echo $command2;
    $run = shell_exec($command2);
    $output = $run;
    }
    */
  }
}
?>