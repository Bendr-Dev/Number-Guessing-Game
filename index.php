<?php
    // Variable declaration
    $prevEntries = array();
    $numEntriesLeft = 10;
    $secretCode = generateRandomValues();
    $game = array("numEntriesLeft"=>$numEntriesLeft, "prevEntries"=>$prevEntries, "currentEntry"=>array());
    
    /*
    *  Generates array of 4 random values spanning from 0 through 9
    */
    function generateRandomValues() {
        return array(rand(0,9), rand(0,9), rand(0,9), rand(0,9));
    }

    /*
    *  Initializes / resets game by assigning variables
    */
    if (isset($_POST["submit"])) {
        $prevEntries = array();
        $numEntriesLeft = 10;
        $secretCode = generateRandomValues();
        $game = array("numEntriesLeft"=>$numEntriesLeft, "prevEntries"=>$prevEntries, "currentEntry"=>array());

        returnJsonResponse( $game ); // return response
    }

    /*
    *  Returns data encoded in JSON format
    */
    function returnJsonResponse( $data ) {
        // Clean up header and re-instantiate content-type
        header_remove();
        header('Content-type: application/json');

        // return PHP object as JSON
        echo json_encode( $data );
    }
?>