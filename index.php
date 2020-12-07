<?php
    if(!isSessionActive()) {
        session_start();
    }

    include("client.html");
    
    if (!isset($_SESSION["secretCode"])) {
        $_SESSION["prevEntries"] = array();
        $_SESSION["numEntriesLeft"] = 10;
        $_SESSION["secretCode"] = generateRandomValues();
    }

    if ($_SESSION["numEntriesLeft"] === 0 || isset($_POST["restart"])) {
        session_destroy();
        exit();
    }

    /**
    *  Generates array of 4 random values spanning from 0 through 9
    */
    function generateRandomValues() {
        return array(rand(0,9), rand(0,9), rand(0,9), rand(0,9));
    }

    echo json_encode($_SESSION["secretCode"]);
    /*
    *  Determine if code sent from user matches randomly generated code
    */
    if (isset($_POST["submit"])) {
        // Get data from client, update game
        $userCode = array(intval($_POST["value1"]), intval($_POST["value2"]),  intval($_POST["value3"]),  intval($_POST["value4"]));
        --$_SESSION["numEntriesLeft"];
        $codeResult = array("code"=>$userCode, "entriesLeft"=>$_SESSION["numEntriesLeft"], "green"=>array(), "yellow"=>array(), "red"=> array());
        $secondLoopCode = $userCode;
        
        // Counts for duplicate numbers
        $secretCodeCount = array_count_values($_SESSION["secretCode"]);

        // Compares user input code with generated code one by one
        // First check - it matches with secret code number
        for ($x = 0; $x < count($userCode); $x++) {
            if ($userCode[$x] === $_SESSION["secretCode"][$x]) {             
                array_push($codeResult["green"], $userCode[$x]);
                --$secretCodeCount[strval($userCode[$x])];
                array_splice($userCode, $x, 1, -1); // Set value to NULL for second check
            }
        }

        // Second check - does not match location-wise, but number is found in different location
        // Default - No match found
        for($x = 0; $x < count($userCode); $x++) {
            if($userCode[$x] !== -1) {
                if (array_key_exists(strval($userCode[$x]), $secretCodeCount) && $secretCodeCount[strval($userCode[$x])] > 0) {
                    array_push($codeResult["yellow"], $userCode[$x]);
                    --$secretCodeCount[strval($userCode[$x])];
                } else {
                    array_push($codeResult["red"], $userCode[$x]);
                }
            }
        }
        array_push($_SESSION["prevEntries"], $codeResult); // Attaches the results to object
        returnJsonResponse($_SESSION["prevEntries"]);
    }

    /**
    *  Returns data encoded in JSON format
    */
    function returnJsonResponse( $data ) {
        // return PHP object as JSON
        echo json_encode( $data );
    }

    /**
     *  Checks session status
     */
    function isSessionActive() {
        return (session_status() === PHP_SESSION_ACTIVE);
    }
?>