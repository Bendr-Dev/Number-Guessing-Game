<?php
    // Variable declaration
    $prevEntries;
    $numEntriesLeft;
    $secretCode;
    $game;

    /*
     *  Generates array of 4 random values spanning from 0 through 9
     */
    function generateRandomValues() {
        return array(rand(0,9), rand(0,9), rand(0,9), rand(0,9));
    }

    /*
     *  Initializes / resets game by assigning variables
     */
    if (isset($_POST["reset"])) {
        $prevEntries = array();
        $numEntriesLeft = 10;
        $secretCode = generateRandomValues();
        $game = array("numEntriesLeft"=>$numEntriesLeft, "prevEntries"=>$prevEntries, "currentEntry"=>array());

        returnJsonResponse( $game ); // return response
    }

    /*
     *  Determine if code sent from user matches randomly generated code
     */
    if (isset($_POST["submit"])) {
        // Get data from client, update game
        $userCode = $_POST['submit'];
        $numEntriesLeft -= 1;
        $prevEntries.array_push($userCode);
        $codeResult = array("green"=>array(), "yellow"=>array(), "red"=> array());
        
        // Counts for duplicate numbers
        $secretCodeCount = array_count_values($secretCode);
        
        // Compares user input code with generated code one by one
        // First check - it matches with secret code number
        // Second check - does not match location-wise, but number is found in different location
        // Default - No match found
        for ($x = 0; $x < count($userCode); $x++) {
            if ($userCode[$x] === $secretCode[$x]) { // Matches
                $codeResult["green"].array_push($userCode[$x]);
                $secretCodeCount["${$secretCode[$x]}"] -= 1;
            } elseif ($userCode[$x] !== $secretCode[$x] && $secretCodeCount["${$secretCode[$x]}"] > 0) {
                $codeResult["yellow"].array_push($userCode[$x]);
                $secretCodeCount["${$secretCode[$x]}"] -= 1;
            } else {
                $codeResult["red"].array_push($userCode[$x]);
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

        // Exit script
        exit();
    }
?>