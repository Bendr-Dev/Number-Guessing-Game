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

    /**
    *  Generates array of 4 random values spanning from 0 through 9
    */
    function generateRandomValues() {
        return array(rand(0,9), rand(0,9), rand(0,9), rand(0,9));
    }

    /**
     *  Checks session status
     */
    function isSessionActive() {
        return (session_status() === PHP_SESSION_ACTIVE);
    }
?>

<script type="text/javascript">
    const prevResults = <?php echo json_encode($_SESSION["prevEntries"], JSON_HEX_TAG); ?>;
    const entriesLeft = <?php echo json_encode($_SESSION["numEntriesLeft"], JSON_HEX_TAG); ?>;
    const entries = document.getElementById("entries");

    const reducer = (accumulator, currentValue) => accumulator + ` ${currentValue}`;
    const entriesLeftDisplay = document.createElement("div");
    const prevEntries = document.createElement("div");
    entriesLeftDisplay.innerHTML = `Entries Left: ${entriesLeft}`;
    entries.appendChild(entriesLeftDisplay);
    prevResults.forEach((result) => {
        let parentElement = document.createElement("div");
        let childCodeElement = document.createElement("div");
        childCodeElement.innerHTML = `${result["code"]}:`;
        parentElement.appendChild(childCodeElement);

        let childGreenElement = document.createElement("div");
        childGreenElement.innerHTML = result["green"].length > 0 ? result["green"].reduce(reducer) : "";
        childGreenElement.classList.add("green");
        parentElement.appendChild(childGreenElement);

        let childYellowElement = document.createElement("div");
        childYellowElement.innerHTML = result["yellow"].length > 0 ? result["yellow"].reduce(reducer) : "";
        childYellowElement.classList.add("yellow");
        parentElement.appendChild(childYellowElement);

        let childRedElement = document.createElement("div");
        childRedElement.innerHTML = result["red"].length > 0 ? result["red"].reduce(reducer) : "";
        childRedElement.classList.add("red");
        parentElement.appendChild(childRedElement);

        prevEntries.appendChild(parentElement);
    });
    entries.appendChild(prevEntries);
</script>