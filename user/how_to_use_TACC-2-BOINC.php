<?php
//Added by Thomas
require_once("../inc/util.inc");

//Edited by Joshua
page_head(null, null, null, null,  null, "Guide for TACC/XSEDE Researchers");
echo "<br /><font size=+3>".tra("Guide for TACC/XSEDE Researchers")."</font>";
echo '<p>'.tra("To submit jobs as a researcher, you could either use the job submission interface available on this website, or you could submit the jobs after logging in to the Stampede2/Lonestar5 systems at TACC.").'</p>';

echo "<h3>".tra("Steps for Submitting Jobs from the Website")."</h3>";
echo '<ol>';
echo '<li> For submitting jobs through this website, you should first run the registration script from the Stampede2/Lonestar5 systems at least once</li>';
echo '<ul>';
echo '<li> Login to Stampede2 or Lonestar5 using your TACC portal credentials </li>';
echo '<li> Create a directory named "boinc" ( <font face="courier new">mkdir boinc</font>) and switch to this directory ( <font face="courier new">cd boinc</font>) </li>';
echo '<li> Copy the registration script by running the following command: <br>';
echo '<font face="courier new"> wget https://raw.githubusercontent.com/ritua2/BOINCatTACC/master/stampede2-backend/register-boinc.sh </font>';
echo '</li>';
echo '<li> Add execute permissions on the script: <br>';
echo '<font face="courier new">chmod +x register-boinc.sh</font>';
echo '</li>';
echo '<li> Run the script for registration (register-boinc.sh): <br>';
echo '<font face="courier new">./register-boinc.sh </font>';
echo '</li>';
echo '</ul>';
echo '';
echo '<li> Log in to this website as a Researcher using your TACC portal credentials: <a href = "https://boinc.tacc.utexas.edu/login_as_a_researcher_form.php " target="_blank">https://boinc.tacc.utexas.edu/login_as_a_researcher_form.php </a>';
echo '</li>';
echo '';
echo '<li> Go to the Job Submission page: https://boinc.tacc.utexas.edu/job_submission.php </li>';
echo '';
echo '<li> For running applications through BOINC@TACC, you will need to select one of the following options: <br>';
echo '';
echo '<ul><li> <b>Use the Docker image of an application supported by BOINC@TACC </b>: If you are using the Docker image provided by BOINC@TACC, keep the radio-button for the "List of BOINC@TACC Docker images" selected and select the desired Docker image</li>';
echo '';
echo '<li> <b> Use a Docker image of the application supported by you or someone else on Docker Hub </b>: If the Docker image is on Docker Hub but not supported by BOINC@TACC, click the radio button for Docker Hub. Type in the name of the desired Docker image as it appears on Docker Hub (not the URL). Then select the "Check if it exists on Docker hub" button and the BOINC@TACC system will check if the Docker image exists or not. </li>';
echo '';
echo '<li> If your code is not containerized yet, you can upload the code and the steps to compile it through the job submission page. A Docker image will be created automatically for you. </li></ul>';
echo '';
echo '</li>';
echo '';
echo '<li> Type in the list of commands necessary to run the file. Separate each command with a “semi-colon” or remember to hit enter after each line of command </li>';
echo '';
echo '<li> Select whether the input files will be uploaded as a compressed TAR file (*.tgz) or as a ZIP file (*.zip). Then browse to the desired *.tgz or *.zip file on your computer using the "Browse" button. Then select the file, and click the "Submit the Job" button. <br>';
echo 'Congrats, your job is submitted!';
echo '';
echo '</li>';
echo '<li> If your job got submitted successfully, you will see it listed under the "Job History" page: https://boinc.tacc.utexas.edu/job_history.php';
echo '</li>';
echo '';
echo '<li> Once your job has finished executing successfully, you will receive an email notification with the subject "BOINC job completed", and your job results will either be attached to the email as a *.tgz file or a link to download the results will also be provided';
echo '';
echo '</li>';
echo '';
echo '<p> <b>Note </b>: If your job was submitted successfully, and it ran on the client but there was a run-time error caused due to wrong parameters or wrong commands, you may still get an email with job completed with "success" but there will be no results.';
echo '';
echo '</ol>';

echo "<h3>".tra("Example: Step-by-Step Instructions for Running the OpenSees Application from the Job Submission Page.")."</h3>";

echo '<ol>';

echo '<li>Make sure you are a registered BOINC@TACC user and have completed the following steps:</li>';

echo '<ul><li>Login to Stampede2 or Lonestar5 using your TACC portal credentials</li>';

echo '<li>Create a directory named "boinc" (<font face="courier new">mkdir boinc</font>) and switch to this directory (<font face="courier new">cd boinc</font>)</li>';

echo '<li>Copy the registration script by running the following command: <br>';


echo '<font face="courier new">wget https://raw.githubusercontent.com/ritua2/BOINCatTACC/master/stampede2-backend/register-boinc.sh</font>';

echo '</li>';

echo '<li>Add execute permissions on the script: <br>';

echo '<font face="courier new">';

echo 'chmod +x register-boinc.sh';

echo '</font>';

echo '</li>';

echo '<li>Run the script for registration (register-boinc.sh): <br>';

echo '<font face="courier new">';

echo './register-boinc.sh';

echo '</font>';

echo '</li>';

echo '</ul>';

echo '<li>Create a directory named "data" on your computer and copy the sample input files for running OpenSees in this directory, example:<br>';

echo '<font face="courier new">';

echo 'mkdir data <br>';

echo 'cd data<br>';

echo 'curl -O http://opensees.berkeley.edu/wiki/images/a/a1/ElasticFrame.tcl<br>';

echo 'curl -O http://opensees.berkeley.edu/wiki/images/3/3d/MomentCurvature.tcl <br>';

echo 'cd ..<br>';

echo '</font>';

echo '</li>';

echo '<li>Create a *.zip or a *.tgz file containing the contents of the data directory, example:<br>';

echo '<font face="courier new">';

echo 'tar -cvzf data.tgz data';

echo '</font>';

echo '</li>';

echo '<li>Login to the BOINC@TACC website using your TACC credentials and click on "Job Submission" tab</li>';

echo '<li>By default, the radio-button for "List of docker images maintained by BOINC@TACC" will be selected. Keep this as-is, and click on the drop-down list to select "OpenSees" </li>';

echo '<li>In the text-box for the list of commands, enter:<br>';

echo '<font face="courier new">';

echo 'OpenSees < ./data/MomCurv.tcl;<br>';

echo 'OpenSees < ./data/ElFram.tcl; <br>';

echo '</font>';

echo '</li>';

echo '<li>Upload the data.zip or data.tgz file created in step # 3</li>';

echo '<li>Click on "Submit the job" button</li>';

echo '<li>If your job got submitted successfully, you will see it listed under the "Job History" page: https://boinc.tacc.utexas.edu/job_history.php</li>';

echo '<li>Once your job has finished executing successfully, you will receive an email notification with the subject "BOINC job completed", and your job results will either be attached to the email as a *.tgz file or a link to download the results will also be provided<p>';

echo 'Note: If your job was submitted successfully, and it ran on the client but there was a run-time error caused due to wrong parameters or wrong commands, you may still get an email with job completed with "success" but there will be no results';

echo '</li>';

echo '</ol>';

echo "<h3>".tra("Steps to Submit Jobs Directly Through Stampede2/Lonestar5")."</h3>";
echo 'In preparation of submitting jobs from Stampede2/Lonestar5, you will need to do the following:';
echo '';
echo '<ul>';
echo '';
echo '<li>Create a directory named "data" on your computer and copy the sample input files for running OpenSees in this directory </li>';
echo '';
echo '<li> Compress the “data” directory into either a *.tgz or a *.zip file </li>';
echo '';
echo '</ul>';
echo '';
echo '';
echo 'For submitting jobs directly from Stampede2/Lonestar5 systems, please run the BOINC@TACC job submission script available <a href="https://raw.githubusercontent.com/ritua2/BOINCatTACC/master/stampede2-backend/stampede_2_BOINC2.sh"> here. </a> You can run the following command to copy this script directly to Stampede2/Lonestar5: <br>';
echo '<br>';
echo '<font face="courier new"> wget https://raw.githubusercontent.com/ritua2/BOINCatTACC/master/stampede2-backend/advance-submit.sh </font> <br>';
echo '<br>';
echo 'You would need to change the permission on the script to make it an executable: <br>';
echo '<br>';
echo '<font face="courier new"> chmod +x advance-submit.sh </font>';
echo '<br>';
echo '<br>You can then run the script using the following command:<br>';
echo '<br>';
echo '<font face="courier new">./advance-submit.sh </font><br>';
echo '<br>';
echo 'You would need to respond to the questions prompted by script, and if your job meets the constraints for running through BOINC@TACC, it will be submitted to the BOINC server, else, a SLURM job script will be generated for you so that you can run the job on Stampede2/Lonestar5.';
echo '';
echo '<br><br><b>Please note:</b> you will not be able to login to Stampede2/Lonestar5 if you do not already have an existing project allocation on TACC resources. Please visit the following website to learn more about the process of requesting allocation on TACC resources: <a href="https://portal.tacc.utexas.edu/"> https://portal.tacc.utexas.edu/ </a>';
echo '';
echo '';
echo '<p><h3>'.tra('Video demonstration of the BOINC@TACC infrastructure:').'</h3></p>';
//iframe code from https://www.w3schools.com/html/tryit.asp?filename=tryhtml_youtubeiframe
echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/UH9mJjZstO4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
//End of Joshua's edit
page_tail();
//End of the edit by Thomas
?>
