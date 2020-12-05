<?php
    include('index.php');

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
?>