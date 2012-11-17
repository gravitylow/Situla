function countChar(val)
{
    var len = val.value.length;
    $('#charNum').text(3000 - len);
    if (len >= 3000) 
    {
        val.value = val.value.substring(0, 3000);
    }
};
