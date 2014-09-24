<?php

class Filestore {

    protected $filename = '';
    protected $is_csv;

    public function __construct($filename = '') {
        $this->filename = $filename;
        $this->is_csv = substr($this->filename, -3) == 'csv' ? TRUE : FALSE;
    }

    // CALL readCsv IF FILE IS CSV, readLines OTHERWISE
    public function read() {
        if ($this->is_csv) {
            return $this->readCsv();
        }
        else {
            return $this->readLines();
        }
    }

    // CALL writeCsv IF FILE IS CSV, writeLines OTHERWISE
    public function write($array) {
        if ($this->is_csv) {
            return $this->writeCsv($array);
        }
        else {
            return $this->writeLines($array);
        }
    }

    // RETURNS ARRAY OF LINES IN $this->filename
    private function readLines() {
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
    private function writeLines($array) {
        // OPEN FILE AND OVERWRITE CONTENTS
        $handle = fopen($this->filename, 'w');

        // LOOP THROUGH THE LINES AND WRITE EACH LINE TO FILE
        foreach ($array as $line) {
            fwrite($handle, $line . PHP_EOL);
        }
        fclose($handle);
    }

    // READS CONTENTS OF CSV $this->filename, RETURNS AN ARRAY
    private function readCsv() {
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
    private function writeCsv($array) {
        $handle = fopen($this->filename, 'w');

        foreach ($array as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);
    }
}