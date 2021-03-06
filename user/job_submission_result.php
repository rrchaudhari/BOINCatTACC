<?php
// This file is part of BOINC.
// http://boinc.berkeley.edu
// Copyright (C) 2016 University of California
//
// BOINC is free software; you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License
// as published by the Free Software Foundation,
// either version 3 of the License, or (at your option) any later version.
//
// BOINC is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with BOINC.  If not, see <http://www.gnu.org/licenses/>.

require_once("../inc/util.inc");
require_once("../inc/red_keys.inc");

page_head(null, null, null, null, null, "Job Submission Result");

if (!isset($_SESSION))
{
    session_start(); 
}

if(strpos($_SERVER['HTTP_REFERER'], 'job_submission') !== false){
	//All information needed
	$organizationKey = get_ok("TACC");
	$token = "";
	$reefIP = "";
	$reefKey = "";
	$inputFile = NULL;
	$fileName = "";
	$fileUploaded = "3";//"1" -> a file uploaded, "2" -> no file uploaded, "3"-> file doesn't meet the requirement
	$commandLine = $_POST["theCommandLine"];
	$commandLine = str_replace("\n", "", $commandLine);
	$commandLine = str_replace("\r", "", $commandLine);
	$dockerImg = "";
	$theCustom = "";
	$theTopics = json_encode (json_decode ("{}"));

	if($_POST["dockerList"] != "none"){
		$dockerImg = $_POST["dockerList"];
		$theCustom = 'No';
	}
	if($_POST["dockerFileName"] != "none"){
		$dockerImg = $_POST["dockerFileName"];
		$theCustom = 'Yes';
	}
	$dockerImg = str_replace("\n", "", $dockerImg);
	$dockerImg = str_replace("\r", "", $dockerImg);
	$boincApp = "boinc2docker";
	if($dockerImg == "carlosred/gpu:cuda"){
		$boincApp = "adtdp";	
	}



	//Check if a job topic is included 
	if(isset($_POST['nonMidasTopic'])){

		$providedTopics = str_replace("\n", "", $_POST['nonMidasTopic']);
		$providedTopics = str_replace("\n", "", $providedTopics);
		$providedTopics = trim($providedTopics);

		if(strlen($providedTopics) !== 0){

			// Topics have been provided
			$theTopics = $providedTopics;
		} else {
			// No topic provided
			$theTopics = "";
		}
	}

	//Check if a file is uploaded or not and if so, check whether it meets the requirement or not
	if(isset($_FILES['file'])){
		$inputFile = $_FILES["file"];
		$fileName =  $_FILES["file"]["name"];
		$fileUploaded = "1";
	} 
	else if(!isset($_FILES['file'])){//No file uploaded
		$fileUploaded = "2";
	}

	if($fileUploaded == "3"){
		echo '<center><h1>'.tra('Sorry, your file was not uploaded.').'</center></h1>
				<center><h3>'.tra('Make sure to follow the requirements mentioned on Job Submission page for the input file').'</h3></center>';
		echo '<center><a href="./job_submission.php" style="font-weight:bold;" class="btn btn-success" role="button">Go Back to Job Submission Page</a></center>';
	} else {

		//Get the user's token
		/*Taken from Thomas' job history codes*/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://0.0.0.0:5078/boincserver/v2/api/user_tokens/".$_SESSION['user']."/".$organizationKey);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);

		if (curl_errno($ch) || strpos($result, 'NOT') !== false) {//Token retreiving was unsuccessful
			echo '<center><h1>'.tra('Sorry, your file was not uploaded.').'</center></h1>';
			if(strpos($result, 'NOT') !== false){
				echo '<center><h3>'.tra($result).'</h3></center>';
			} else {
				echo '<center><h3>'.tra('Make sure to follow the requirements mentioned on Job Submission page').'</h3></center>';
			}
			echo '<center><a href="./job_submission.php" style="font-weight: bold;" class="btn btn-success" role="button">Go Back to Job Submission Page</a></center>';
			curl_close($ch);
		} else {//Token retreiving was successful
			$tokenlist=explode(",", $result);
			$token = $tokenlist[0];

			curl_close($ch);
			
			//Set up a json
			$tempData = array('Token'=> $token, 'Boapp'=>$boincApp, 'Files'=> array($fileName),'Image'=> $dockerImg,'Custom'=> $theCustom,'Command'=> $commandLine, 'topics'=>$theTopics, 'Username'=>$_SESSION['user']);
			$theJson = json_encode($tempData, JSON_UNESCAPED_SLASHES);

			//Input File Uploading
			if($fileUploaded == "1"){
				//Get the reed key and reef IP
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://0.0.0.0:6071/boincserver/v2/api/env/reef");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($ch);
				$result = json_decode($result, true);
				$reefKey = $result["Reef_Key"];
				$reefIP = $result["Reef_IP"];
				curl_close ($ch);

				/*//https://www.w3schools.com/Php/php_file_upload.asp
				$target_dir = "/home/boincadm/project/api/sandbox_files/DIR_".$token."/";
				$target_file = $target_dir . basename($fileName);
				if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {*/
				
				// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
				
				//Move the input file to its corresponding reef directory
				if (function_exists('curl_file_create')) {
		  			$cFile = curl_file_create($inputFile['tmp_name']);
				} else { // 
		  			$cFile = '@' . realpath($inputFile['tmp_name']);
				}
				$ch = curl_init();
				$url = "http://".$reefIP.":2000/reef/upload/".$reefKey."/".$token; 
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' =>$cFile,'filename'=>$fileName));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Removes the Reef message	
				$result = curl_exec($ch);

				if (curl_errno($ch) || strpos($result, 'INVALID') !== false) {
					curl_close ($ch);
					echo '<center><h1>'.tra('Sorry, your file was not uploaded.').'</center></h1>';
					if(strpos($result, 'INVALID') !== false){
						echo '<center><h3>'.tra($result).'</h3></center>';
					} else {
						echo '<center><h3>'.tra('Make sure to follow the requirements mentioned on Job Submission page for the input file').'</h3></center>';
					}
					echo '<center><a href="./job_submission.php" style="font-weight:bold;" class="btn btn-success" role="button">Go Back to Job Submission Page</a></center>';
					$fileUploaded = "3";
				}
				curl_close ($ch);
			}

			//Call the JSON API to send all necessary information about the job
			if($fileUploaded != "3"){
				// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://0.0.0.0:6035/boincserver/v2/api/process_web_jobs");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $theJson);
				curl_setopt($ch, CURLOPT_POST, 1);
				$headers = array();
				$headers[] = "Content-Type: application/json";
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch);

				//Check whether calling json API was successful or not			
				if (curl_errno($ch) || strpos($result, 'INVALID') !== false) {
					echo '<center><h1>'.tra('Sorry, your file was not uploaded.').'</center></h1>';
					if(strpos($result, 'INVALID') !== false){
						echo '<center><h3>'.tra($result).'</h3></center>';
					} else {
						echo '<center><h3>'.tra('Make sure to follow the requirements mentioned on Job Submission page for the input file').'</h3></center>';
					}
					echo '<center><a href="./job_submission.php" style="font-weight:bold;" class="btn btn-success" role="button">Go Back to Job Submission Page</a></center>';					
				} else {
					echo '<center><h1>'.tra('Congratulations, your file was uploaded.').'</center></h1>';
					echo '<h2><center>'.tra($result).'</center></h2>';
					echo '<center><a href="./job_submission.php" style="font-weight:bold" class="btn btn-success" role="button">Go Back to Job Submission Page</a></center>';
				}
			
				curl_close ($ch);
			}
		}
	}
} else {
	echo "<script>window.location.replace('./login_as_a_researcher_form.php');</script>";
}

page_tail();

?>