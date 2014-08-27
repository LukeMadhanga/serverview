<?php

cGetFiles::getFromDir();

class cGetFiles {
    
    /**
     * An array holding the paths that we allow to be viewed
     * @var array 
     */
    private static $alloweddirs = array('dummyfiles');
    
    /**
     * The directory from where we will get the files (without the leading or trailing slashes
     * @var string 
     */
    private static $directory = 'dummyfiles';
    
    /**
     * A comma-separated list of extensions for the files that we want to retrieve
     * @var string 
     */
    private static $extensions = 'png,jpg,jpeg,gif,tiff';
    
    /**
     * Get all of the files from the specified directory that end with one of the extensions defined self::$extensions
     * @throws Exception
     */
    static function getFromDir() {
        if (!in_array(self::$directory, self::$alloweddirs)) {
            // The user has been cheeky and attempted to access somewhere where they're not allowed
            throw new Exception('Attempted to access a restricted directory: ' . self::$directory);
        }
        $startat = (int) filter_input(INPUT_POST, 'startat');
        $limit = (int) filter_input(INPUT_POST, 'limit');
        $randomize = filter_input(INPUT_POST, 'randomize');
        $files = glob(self::$directory . '/*.{' . self::$extensions . '}', GLOB_BRACE);
        $output = array_slice($files, $startat, $limit);
        if ($randomize) {
            // The user wants us to randomize the output
            shuffle($output);
        }
        self::ajaxExit('OK', $output);
    }
    
    /**
     * Send a JSON-encoded response from an Ajax call and exit
     * @param $result string Message to return to the browser, or false to return data only
     * @param $data mixed Any additional data to return
     */
    private static function ajaxExit($result, $data = null) {
        header('Content-Type:text/json; charset=utf-8');
        header("HTTP/1.0 200");
        $response = array('result' => $result);
        if (!empty($data)) {
            $response['data'] = $data;
        }
        print json_encode($response);
        exit;
    }
    
}