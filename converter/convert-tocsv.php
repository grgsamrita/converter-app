<?php
/* For JSON to CSV conversion */
	if(isset($_POST['submit-json'])){
		if(empty($_FILES['jsontocsv']['name'])){
			echo "<p style='color:red; margin-top:15px; text-align:center'>File cannot be empty.</p>";
		}
		else{
		
		    $jsonFilename =  $_FILES['jsontocsv']['tmp_name']; // directory of the file to be uploaded

			$json = file_get_contents($jsonFilename);
			$json = json_decode($json, true);
			if($json === null){
				echo "<p style='color:red; margin-top:15px; text-align:center'>Its not json file.</p>";
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
				
				// fclose($f);

				//create zip of both branches list
				$jsonFile = $_FILES['jsontocsv']['name'];
			    $csv_files = array($jsonFile, $filepath2);
				$csvzip = 'csvfile.zip';
				$zip_csv = new ZipArchive;
				$zip_csv->open($csvzip, ZIPARCHIVE::OVERWRITE);
				foreach ($csv_files as $file) {
				  $zip_csv->addFile($file);
				}
				$zip_csv->close();

				unlink($jsonFile);
				unlink($filepath2);

				// header('Content-Type: application/zip');
				header('Content-disposition: attachment; filename='.$csvzip);
				header('Content-Length: ' . filesize($csvzip));
				readfile($csvzip);

			}
		}	
	}
	
?>