<?php
	/* For CSV to JSON conversion */
	if(isset($_POST['submit-csv'])){
		$allowedExts = array('application/vnd.ms-excel','text/plain','text/csv');
		if(empty($_FILES['csvtojson']['name'])){
			echo "<p style='color:red; margin-top:15px; text-align:center'>File cannot be empty.</p>";
		}else{
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

					$header = NULL;
	    			$complete = array();   
			        while (($row = fgetcsv($handle, 0, ',')) !== FALSE)
			        {
			            if(!$header)
			            {
			                $header = array();
			                foreach ($row as $val)
			                {
			                    $header_raw[] = $val;
			                    $hcounts = array_count_values($header_raw);
			                    $header[] = $hcounts[$val]>1?$val.$hcounts[$val]:$val;
			                }
			            }
			            else
			            {
			                $complete[] = array_combine($header, $row);
			            }
			        }
			        fclose($handle);  	

					$jsondata = json_encode($complete, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
					// echo $jsondata;				
					// $path = "files/json";	
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
					$oldfile = $_FILES['csvtojson']['name'];
				    $files = array($oldfile, $filepath);
					$zipname = 'jsonfile.zip';
					$zip = new ZipArchive;
					$zip->open($zipname, ZIPARCHIVE::OVERWRITE);
					foreach ($files as $file) {
					  $zip->addFile($file);
					}
					$zip->close();

					unlink($oldfile);
					unlink($filepath);

					header('Content-Type: application/zip');
					header('Content-disposition: attachment; filename='.$zipname);
					header('Content-Length: ' . filesize($zipname));
					readfile($zipname); 

					// fclose($f);

		    	}
	  		}
	  		
	  		else{
	  			echo "<p style='color:red; margin-top:15px; text-align:center'>Its not csv file.</p>";
	  		}
  	
		}
	}

	
?>