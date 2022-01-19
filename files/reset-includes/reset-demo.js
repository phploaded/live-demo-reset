// You must edit this line to suit your domain path to the folder in which "reset_includes" folder exists
demo_reset_domain = 'http://your-demo-url-here.com/';


var jQueryResetFound = false;
function initJQuery() {

//if the jQuery object isn't available
if (typeof(jQuery) == 'undefined') {
if (! jQueryResetFound) {
jQueryResetFound = true;
document.write("<scr" + "ipt type=\"text/javascript\" src=\""+demo_reset_domain+"reset-includes/jquery-3.6.0.min.js\"></scr" + "ipt>");
}
setTimeout("initJQuery()", 50);
} else {
$(function() {
check_demo_reset();
});
}}
initJQuery();



function check_demo_reset(){
if($('#reset_demo_widget').length==0){
$('body').append('<div id="reset_demo_widget"><div class="reset_timer_text">Demo will reset in :</div>\
<div class="reset_timer_time">--:--:--</div>\
<div class="reset_demo_credit">get on <a target="_blank" href="http://phploaded.com/project/live-demo-reset.html">phploaded.com</a></div></div>');
}

$.get( demo_reset_domain+"reset-includes/functions.php?method=check_time", function( data ) {
if(isNaN(data)){
alert('Server responded :'+data);
}
var left_time = parseInt(data);
if(left_time>0){

setTimeout(function(){
display_timer(left_time-1);
}, 1000);

setTimeout("reload_page()", 1000*(left_time+1)); // a little late timeout to make sure old page expires.
} else {
reload_page();
}

});
}

function display_timer(left_time){
$('#reset_demo_widget .reset_timer_time').html(reset_secondsToHms(left_time));
setTimeout(function(){
display_timer(left_time-1);
}, 1000);
}


function reload_page(){
var xhtml = '<div class="reset_demo_popup">\
<div class="reset_demo_popup_bg"></div>\
<div class="reset_demo_popup_data"><h2>Old demo is expired. Please wait while we reset the demo.</h2>\
<div class="reset-loading"></div>\
<p>Reset process may perform following changes :</p><ul>\
<li>Delete all files of this demo and copy fresh files from a backup source.</li>\
<li>Empty the database and import all MySQL data from provided phpMyAdmin SQL dump file.</li>\
<li>Destroy the PHP session.</li>\
<li>Delete some spicific cookies.</li>\
</ul>\
</div>\
</div>';
$('body').append(xhtml);
$('#reset_demo_widget').hide();

$.get(demo_reset_domain+"reset-includes/functions.php?method=check_time", function( data ) {
if(isNaN(data)){
alert('Server responded :'+data);
}
setTimeout("reset_reload_final_func()", 3000);
}); 
}


function reset_reload_final_func(){
document.location.href=document.location.href;
}



function reset_secondsToHms(d) {
d = Number(d);
var h = Math.floor(d / 3600);
var m = Math.floor(d % 3600 / 60);
var s = Math.floor(d % 3600 % 60);
return ((h > 0 ? h + ":" + (m < 10 ? "0" : "") : "") + m + ":" + (s < 10 ? "0" : "") + s);
}