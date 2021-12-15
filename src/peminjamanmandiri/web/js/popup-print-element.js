// Function to print element
// From http://stackoverflow.com/questions/2255291/print-the-contents-of-a-div
function PrintElem(elem)
{
    Popup($(elem).html());
}

function Popup(data) 
{
    var mywindow = window.open('');
    mywindow.document.write('<html><head><title>my div</title>');
    /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
    mywindow.document.write('</head><body>');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10

    // delay 0.1 s
    setTimeout(function(){ mywindow.print(); mywindow.close(); }, 1000);

    return true;
}