<html>
<head>
<title>Submit Submission</title>
<style>
body
{
  padding: 5px;
}
div.main
{
  border: 1px dotted black;
  padding: 10px;
}
span.title
{
  font-weight: bold;
  text-transform: uppercase;
  font-size: 1.5em;
}
label
{
  font-weight: bold;
}
</style>
</head>

<body>

<div class="main">
<span class="title">Submit Your Exercise Solution</span>
<form action="saveExercise.php" method="post"
enctype="multipart/form-data">
<label for="exercise">Choose an exercise</label>
<select id="exercise" name="exercise">
<?PHP
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

$h = False;
$d = "UploadedExercises/";
$allExercises = getDirectoryList($d, $h);
foreach ($allExercises as $exercise)
{
  echo '<option value="'.$exercise.'">'.$exercise.'</option>';
}
?>
</select>
<br />
<label for="username">A-Number:</label>
<input type="text" id="username" name="username"></input>
<br />
<label for="file">Filename:</label>
<input type="file" name="file" id="file">
<br />
<input type="submit" name="submit" value="Submit">
</form>

<a href="CheckExerciseSubmissions.php">Check Exercise Submissions</a>

</div>

</body>
</html>