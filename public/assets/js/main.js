document.querySelector('.togglemenu').addEventListener('click', function(e) {
  var left = document.querySelector('.sidebar').style.left;

  if(left == '0px') document.querySelector('.sidebar').style.left = '-250px';
  else document.querySelector('.sidebar').style.left = '0px'

})
