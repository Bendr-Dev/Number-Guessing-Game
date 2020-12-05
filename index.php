<?php
    // Variable declaration
    $prevEntries = array();
    $numEntriesLeft = 10;
    $secretCode = [5, 6, 8, 1];
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
    if (isset($_POST["restart"])) {
        $prevEntries = array();
        $numEntriesLeft = 10;
        $secretCode = [5, 6, 8, 0];
        $game = array("numEntriesLeft"=>$numEntriesLeft, "prevEntries"=>$prevEntries, "currentEntry"=>array());

        returnJsonResponse( $game ); // return response
    }


    // MOVE THIS TO SEPERATE PHP FILE
    /*
    *  Determine if code sent from user matches randomly generated code
    */
    if (isset($_POST["submit"])) {
        // Get data from client, update game
        $userCode = [intval($_POST["value1"]), intval($_POST["value2"]),  intval($_POST["value3"]),  intval($_POST["value4"])];
        --$game["numEntriesLeft"];
        array_push($prevEntries, $userCode);
        $codeResult = array("green"=>array(), "yellow"=>array(), "red"=> array());
        
        // Counts for duplicate numbers
        $secretCodeCount = array_count_values($secretCode);
        
        // Compares user input code with generated code one by one
        // First check - it matches with secret code number
        // Second check - does not match location-wise, but number is found in different location
        // Default - No match found
        for ($x = 0; $x < count($userCode); $x++) {
            if ($userCode[$x] === $secretCode[$x]) { // Matches                
                array_push($codeResult["green"], $userCode[$x]);
                --$secretCodeCount[$secretCode[$x]];
            } elseif ($userCode[$x] !== $secretCode[$x] && array_key_exists(strval($userCode[$x]), $secretCodeCount) && $secretCodeCount[strval($userCode[$x])] > 0) {
                array_push($codeResult["yellow"], $userCode[$x]);
                --$secretCodeCount[strval($userCode[$x])];
            } else {
                array_push($codeResult["red"], $userCode[$x]);
            }
        }

        $game["currentEntry"] = $codeResult; // Attaches the results to object
        
        returnJsonResponse( $game ); // Return response object
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