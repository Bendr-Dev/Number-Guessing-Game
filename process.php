<?php
    // Make sure we open the session
    if(!isSessionActive()) {
        session_start();
    }

    if (isset($_POST["restart"])) {
        session_destroy();
        redirect();
    }

    /*
    *  Determine if code sent from user matches randomly generated code
    */
    if (isset($_POST["submit"])) {
        // Get data from client, update game
        $userCode = array(intval($_POST["value1"]), intval($_POST["value2"]),  intval($_POST["value3"]),  intval($_POST["value4"]));
        --$_SESSION["numEntriesLeft"];
        $codeResult = array("code"=>$userCode, "green"=>array(), "yellow"=>array(), "red"=> array());
        
        // Counts for duplicate numbers
        $secretCodeCount = array_count_values($_SESSION["secretCode"]);

        // Compares user input code with generated code one by one
        // First check - it matches with secret code number
        for ($x = 0; $x < count($userCode); $x++) {
            if ($userCode[$x] === $_SESSION["secretCode"][$x]) {             
                array_push($codeResult["green"], $userCode[$x]);
                --$secretCodeCount[strval($userCode[$x])];
                array_splice($userCode, $x, 1, -1); // Set value to -1 for second check
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

        if(count($codeResult["green"]) === 4) {
            $_SESSION["isSolved"] = true;
        }

        array_push($_SESSION["prevEntries"], $codeResult); // Attaches the results to object
        redirect();
    }

    /**
     *  Redirects back to index.php
     */
    function redirect() {
        header( "Location: /", true, 303);
        exit();
    }

    /**
     *  Checks session status
     */
    function isSessionActive() {
        return (session_status() === PHP_SESSION_ACTIVE);
    }

    /**
     * Destroys session
     */
    function destroySession() {
        if (isSessionActive()) {
            session_destroy();
            redirect();
        }
    }
?>