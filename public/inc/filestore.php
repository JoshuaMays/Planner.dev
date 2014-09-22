<?php

class Filestore {
    
    public $filename = '';
    
    public function __construct($filename = '') {
        $this->filename = $filename;
    }
    
    // RETURNS ARRAY OF LINES IN $this->filename
    public function readLines() {
        $lines = [];
        
        if (filesize($this->filename) > 0) {
            // OPEN FILE, SAVE CONTENT OF FILE TO STRING $content
            $handle = fopen($this->filename, 'r');
            $content = trim(fread($handle, filesize($this->filename)));
            fclose($handle);
            
            // POPULATE $lines ARRAY FROM STRING $content
            $lines = explode(PHP_EOL, $content);
        }
        return $lines;
    }
    
    // WRITES EACH ELEMENT IN $array TO A NEW LINE IN $this->filename
    public function writeLines($array) {
        // OPEN FILE AND OVERWRITE CONTENTS
        $handle = fopen($this->filename, 'w');
        
        // LOOP THROUGH THE LINES AND WRITE EACH LINE TO FILE
        foreach ($array as $line) {
            fwrite($handle, $line . PHP_EOL);
        }
        fclose($handle);
    }
    
    // READS CONTENTS OF CSV $this->filename, RETURNS AN ARRAY
    public function readCsv() {
        $csvRows = [];
        // OPEN CSV FILE, SAVE LINES OF CONTENT TO $line
        $handle = fopen($this->filename, 'r');
        // KEEP GRABBING ROWS OF CONTENT UNTIL END OF FILE
        while (!feof($handle)) {
            $line = fgetcsv($handle);
            if (!empty($line)) {
                // POPULATE $csvRows WITH $line DATA
                $csvRows[] = $line;
            }
        }
        fclose($handle);
        return $csvRows;
    }
    
    // WRITES CONTENTS OF $array TO CSV $this->filename
    public function writeCsv($array) {
        $handle = fopen($this->filename, 'w');
        
        foreach ($array as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);
    }

}