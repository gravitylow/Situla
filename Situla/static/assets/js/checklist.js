function calc(max)
{
    total = 0;
    for (var i=1;i<=max;i++)
    {
        if(document.getElementById(i).checked) total+=1;
    }
    total = (total/max)*100;
    if (total == 100) { $("#button").fadeIn(); } else { $("#button").fadeOut(); }
    document.getElementById('progress').style.cssText = "width: "+total+"%;";
    $('#progress-text').text(Math.round(total)+'% compliance!');
}
$("#button").hide();
