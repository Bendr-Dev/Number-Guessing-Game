// Grab DOM elements
const codeValue1 = document.getElementById("value1");
const codeValue2 = document.getElementById("value2");
const codeValue3 = document.getElementById("value3");
const codeValue4 = document.getElementById("value4");

const codeValue1Display = document.getElementById("v1-display");
const codeValue2Display = document.getElementById("v2-display");
const codeValue3Display = document.getElementById("v3-display");
const codeValue4Display = document.getElementById("v4-display");

const numPad = document.getElementById("num-pad");

const codeFormBtn = document.getElementById("submit-btn");

const codeValues = {
    "index": 0,
    "values": [codeValue1Display, codeValue2Display, 
               codeValue3Display, codeValue4Display]
};

/**
 * Adds event listeners to numpad buttons
 */
numPad.childNodes.forEach((childNode) => {
    if (childNode.innerHTML === " x ") {
        childNode.addEventListener("click", () => {
            removeCodeValue();
        });
    } else if (childNode.innerHTML === " &gt; ") {
        // TODO: check if values exist, attach values to form, send to php file
        childNode.addEventListener("click", () => {
            if(checkValues()) {
                addValuesToForm();
                submitValues();
            }
        });
    } else {
        childNode.addEventListener("click", () => {
            addCodeValue(childNode.innerHTML);
        });
    }
});


/**
 *  Adds next number to code
 */
const addCodeValue = (value) => {
    if (codeValues.index !== codeValues.values.length) {
        codeValues.values[codeValues.index].innerHTML = value;
        codeValues.index++
    }
};

/**
 * Removes last entered value
 */
const removeCodeValue = () => {
    if (codeValues.index !== 0) {
        codeValues.index--;
        codeValues.values[codeValues.index].innerHTML = "_";
    }
};

/**
 * Checks if values exist on all code values
 */
const checkValues = () => {
    return codeValues.values.every(value => {
        return value.innerHTML !== "_";
    });
};

/**
 * Inserts values onto form
 */
const addValuesToForm = () => {
    codeValues.values.forEach((value, index) => {
        document.getElementById(`value${index + 1}`).value = parseInt(value.innerHTML);
    });
};

/**
 * Sends code values to server file
 */
const submitValues = () => {
    codeFormBtn.click();
};