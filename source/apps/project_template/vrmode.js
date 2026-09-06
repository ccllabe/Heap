var canvas = document.getElementById('myCanvas');
var ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

function draw() { //vr模式 圓形
   var centerX = canvas.width / 2;
   var centerY = canvas.height / 2;
   var radius = centerY;
   ctx.beginPath();
   ctx.moveTo(0, 0);
   ctx.lineTo(centerX, 0);
   ctx.arc(centerX, centerY, radius, Math.PI * 1.5, Math.PI * 0.5, true);
   ctx.lineTo(0, canvas.height);
   ctx.fillStyle = "rgba(0,0,0,.8)";
   ctx.fill();
   ctx.closePath();
   ctx.beginPath();
   ctx.moveTo(canvas.width, 0);
   ctx.lineTo(centerX, 0);
   ctx.arc(centerX, centerY, radius, Math.PI * 1.5, Math.PI * 0.5, false);
   ctx.lineTo(canvas.width, canvas.height);
   ctx.lineTo(canvas.width, 0);
   ctx.fillStyle = "rgba(0,0,0,.8)";
   ctx.fill();
   ctx.closePath();
   ctx.beginPath();
   ctx.moveTo(centerX, centerY);
   ctx.fillStyle = "rgba(0,0,0,.8)";
   ctx.arc(centerX, centerY, radius, -0.85 * Math.PI, -0.87 * Math.PI, true);
   ctx.closePath();
   ctx.fill();
};

function resizeCanvas() {
   canvas.width = window.innerWidth;
   canvas.height = window.innerHeight;
   draw();
}
$('#side-nav-toggle').click(function() {
   $(this).parent().toggleClass('width');
   $(this).children().toggleClass('fas fa-angle-double-left').toggleClass('fas fa-angle-double-right');
});
$('#side-nav ul li div').click(function() {
   $(this).parent().toggleClass('selected');
});
//$('#focal-length-list').hasClass('selected'){
   
//}



$(document).ready(function() {
   $(window).resize(function() {
      $('#mapid').height(window.innerHeight);
      resizeCanvas();
   });
});
