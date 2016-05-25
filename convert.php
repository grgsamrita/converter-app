<?php
	/* For CSV to JSON conversion */
	if(isset($_POST['submit-csv'])){
		$allowedExts = array('application/vnd.ms-excel','text/plain','text/csv');
		
		if (in_array($_FILES['csvtojson']['type'], $allowedExts))
  		{
	  		if ($_FILES["csvtojson"]["error"] > 0)
	    	{
	    		echo "Error: " . $_FILES["csvtojson"]["error"] . "<br>";
	    	}
	 	 	else
	    	{	    		
	    		$x = 1;
				$oldpath =  $_FILES['csvtojson']['tmp_name']; // directory of the file to be uploaded
						
				if (($handle = fopen($oldpath, 'r')) === false) {
   				 	die('Error opening file');
				}

				$headers = fgetcsv($handle, 1024, ',');
				$complete = array();

				while ($row = fgetcsv($handle, 1024, ',')) {
				    $complete[] = array_combine($headers, $row);

				}

				fclose($handle);

				$jsondata = json_encode($complete, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
				// echo $jsondata;
				
				$path = "files/json";	
				// if(!file_exists($path)){
			 //    	mkdir($path, 0777, true);
			 //    }    		
		    	$nowdate = date('Y_m_d_H_i_s');
				$filepath = $nowdate."_newjson.json";								
			
			    // $newpath = $path.'/'.$filepath; 
			    // $f = fopen($newpath, "w");
			    // fwrite($f, $jsondata);		    	
				file_put_contents($filepath, $jsondata);       
				
				      
				//create zip of both branches list
			    $files = array($oldpath, $filepath);
				$zipname = 'jsonfile.zip';
				$zip = new \ZipArchive;
				$zip->open($zipname, \ZipArchive::OVERWRITE);
				foreach ($files as $file) {
				  $zip->addFile($file);
				}
				$zip->close();

				unlink($oldpath);
				unlink($filepath);

				header('Content-Type: application/zip');
				header('Content-disposition: attachment; filename='.$zipname);
				header('Content-Length: ' . filesize($zipname));
				readfile($zipname); 

				// fclose($f);

	    	}
  		}
  		
  		else{
  			echo 'Invalid file type.';
  		}
  	

	}

	/* For JSON to CSV conversion */
	if(isset($_POST['submit-json'])){
		
	    $jsonFilename =  $_FILES['jsontocsv']['tmp_name']; // directory of the file to be uploaded
						
		if (fopen($jsonFilename, 'r') === false) {
   				die('Error opening file');
		}  				

		$json = file_get_contents($jsonFilename);
		$json = json_decode($json, true);
		if($json === null){
			echo 'Its not json file';
		}
		else{
			// $dir = 'files/csv';	 
			// if(!file_exists($dir)){
			// 	mkdir($dir, 0777, true);
			// }   

			// Will use $csv to build our CSV content
			$csv = array();
			// Need to define the special column
			
			$maxSubData = 0;
			foreach ($json as $id => $data)
			{
			    $maxSubData = max(array(count($data), $maxSubData ));
			}

			// Headers for CSV file
			// Headers Start
			$headers = array();
			foreach ($json[0] as $key => $value)
			{
			 
			    $headers[] = $key;
			    
			}
			$csv[] = '"' . implode('","', $headers) . '"'."\n";
			// Headers End

			// Now the rows
			// Rows Start
			foreach ($json as $data)
			{
			    $fieldValues = array();
			    foreach ($data as $key => $value )
			    {
			    	$fieldValues[] = htmlspecialchars($value);
			       
			    }

			    $csv[] = '"' . implode('","', $fieldValues) . '"'."\n";
			}
			// Rows End

			$finalCSV = implode("\n", $csv);
			// print $finalCSV;

			$datenow = date('Y_m_d_H_i_s');
			$filepath2 = $datenow.'_newcsv.csv';
			// $f = fopen($dir.'/'.$filepath2, 'a+');
			// fputcsv($f, $csv);
			    
			file_put_contents($filepath2, $csv);
			
			fclose($f);

			//create zip of both branches list
		    $csv_files = array($jsonFilename, $filepath2);
			$csvzip = 'csvfile.zip';
			$zip_csv = new \ZipArchive;
			$zip_csv->open($csvzip, \ZipArchive::OVERWRITE);
			foreach ($csv_files as $file) {
			  $zip_csv->addFile($file);
			}
			$zip_csv->close();

			unlink($jsonFilename);
			unlink($filepath2);

			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename='.$csvzip);
			header('Content-Length: ' . filesize($csvzip));
			readfile($csvzip);

			
		}	
	}

?>