document.getElementById('register').className = ' hideForm'; // Hide Form at Start:
document.getElementById('login').className =  ' hideForm'; // Hide Form at Start:
document.getElementById('logout').className =  ' hideForm'; // Hide Form at Start:
document.getElementById('mySimpleBlogForm').className = ' hideForm'; // Hide Form at Start:

/** 
 * @param eid, Id of the element to change.
 * @param myclass, Class name to toggle.
 **/
function toggleClass(eid, myclass, bid, btnText='Blog') {
    var theEle = document.getElementById(eid);
    var eClass = theEle.className;
    var buttonText = btnText;
    var myButton = document.getElementById(bid);

    if (eClass.indexOf(myclass) >= 0) {
        // we already have this element hidden so remove the class.
        theEle.className = eClass.replace(myclass, '');
        myButton.childNodes[0].nodeValue = "Hide " + buttonText + " Form";
    } else {
        // add the class. 
        theEle.className += ' ' + myclass;
        myButton.childNodes[0].nodeValue = "Show " + buttonText + " Form";
    }
}
