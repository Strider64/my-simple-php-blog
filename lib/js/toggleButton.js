var maindiv1 = document.getElementById('maindiv1');
var maindiv2 = document.getElementById('maindiv2');

if (maindiv1) {
    maindiv1.className +=  ' hideBoxes';
}

if (maindiv2) {
    maindiv2.className += ' hideBoxes';
}


/** 
 * @param eid, Id of the element to change.
 * @param myclass, Class name to toggle.
 **/
function toggleClass(eid, myclass, bid, oforms = 'Open Login / Register', cforms = 'Close Login / Register') {
    var theEle = document.getElementById(eid);
    var myButton = document.getElementById(bid);
    var eClass = theEle.className;


    if (eClass.indexOf(myclass) >= 0) {
        // we already have this element hidden so remove the class.
        theEle.className = eClass.replace(myclass, '');
        myButton.childNodes[0].nodeValue = cforms;
    } else {
        // add the class. 
        theEle.className += ' ' + myclass;
        myButton.childNodes[0].nodeValue = oforms;
    }
}