<html>
<head>
<title>Submit Exercise</title>
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
textarea, input[type=text]
{
  width: 100%;
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

</style>
</head>

<body>

<div class="main">
<span class="title">Submit Your Own Exercise</span>
<form action="save_exercise.php" method="post"
enctype="multipart/form-data">

<label for="name">Exercise Name:</label>
<input type="text" id="name" name="name"></input>
<br />
<label for="info">Exercise Info:</label><br />
<textarea id="info" name="info">This is the exercise description. you can have any <b>HTML</b> in here, too.</textarea>
<br />
<hr />
<br />
<label for="command_1">Command Line Parameters 1:</label><br />
<em>Important Note: Use the input file 1, or <span class="codeOutput">in_1.txt</span>, file if it is necessary to process a file.
To use in_1.txt file, include the command line parameter <span class="codeOutput">"../testCases/in_1.txt"</span>,
remembering the enclosing "quotes". The <span class="codeOutput">../testCases/</span> 
part is responsible for changing the directory out and into the testCases directory, where these files are stored.
</em><br />
<input type="text" id="command_1" name="command_1"></input>
<br />
<label for="in_1">Input File 1:</label><br />
<textarea id="in_1" name="in_1">This is a file that can be used if required by the program.
For instance, one of the command line parameters from above may direct the program to process a file.
It should be referring to this file: in_1.txt.</textarea>
<br />
<label for="out_1">Expected Output File for Test Case 1:</label><br />
<textarea id="out_1" name="out_1">If the program worked properly, this is the desired output.</textarea>
<br />
<br />
<input type="submit" name="submit" value="Submit">
</form>
<hr /><br />
<a href="exercise.php">Check Exercise Submissions</a>

</div>

</body>
</html>