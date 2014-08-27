<?php

class tGetFiles extends PHPUnit_Framework_TestCase {
    
    /**
     * Test how sending null as a limit will behave
     */
    function testLimitNull () {
        $reply = $this->getFilesReply(array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array('limit' => null)
        ));
        $filesfrompost = json_decode($reply);
        $this->assertTrue(empty($filesfrompost->data));
    }
    
    /**
     * Test how sending null as a limit will behave
     */
    function testNoLimit() {
        $reply = $this->getFilesReply(array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array('limit' => false)
        ));
        $filesfrompost = json_decode($reply);
        $nfilesfrompost = count($filesfrompost->data);
        $nfiles = count(glob('dummyfiles/*.{png,jpg,jpeg,gif,tiff}', GLOB_BRACE));
        $this->assertEquals($nfiles, $nfilesfrompost);
    }
    
    /**
     * Test whether sending zero as the limit will return the correct number of files
     */
    function testLimitZero () {
        $reply = $this->getFilesReply(array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array('limit' => 0)
        ));
        $filesfrompost = json_decode($reply);
        $this->assertTrue(empty($filesfrompost->data));
    }
    
    /**
     * Test whether the user limit works
     * @param int $limit The amount of files to return
     */
    function testUserLimit ($limit = 10) {
        $reply = $this->getFilesReply(array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array('limit' => $limit)
        ));
        $filesfrompost = json_decode($reply);
        $nfilesfrompost = count($filesfrompost->data);
        $this->assertEquals($limit, $nfilesfrompost);
    }
    
    /**
     * Test whether the randomize parameter works
     * @param int $limit The amount of files to return
     */
    function testRandomize ($limit = 10) {
        $reply = $this->getFilesReply(array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array('limit' => $limit, 'randomize' => true)
        ));
        $filesfrompost = json_decode($reply);
        $numericallysequential = array_keys($filesfrompost->data) === range(0, count($filesfrompost->data) - 1);
        $this->assertFalse($numericallysequential);
    }
    
    /**
     * Test whether the startrow parameter works
     * @param int $startrow The amount of files to skip before returning the output
     */
    function testStartRow ($startrow = 30) {
        $reply = $this->getFilesReply(array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array('limit' => $startrow + 100, 'startAt' => $startrow)
        ));
        $filesfrompost = json_decode($reply);
        $files = glob('dummyfiles/*.{png,jpg,jpeg,gif,tiff}', GLOB_BRACE);
        $this->assertTrue($filesfrompost->data[$startrow] === $files[$startrow]);
    }
    
    /**
     * 
     * @param type $optsarray
     * @return mixed The result from a curl operation
     */
    private function getFilesReply($optsarray = array()) {
        $ch = curl_init("http://g.co/serverview/getFiles.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        foreach ($optsarray as $opt => $value) {
            // Set each of the options that have been supplied to us
            curl_setopt($ch, $opt, $value);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
}