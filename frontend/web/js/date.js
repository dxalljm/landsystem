function showLeftTime()
{
    var date = new Date();
    var Y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    var H = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
    var i = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
    var s = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
    $('#currentTime').html(""+Y+"年"+m+"月"+d+"日 "+H+":"+i+":"+s+"");
}
setInterval(showLeftTime, 1000);
