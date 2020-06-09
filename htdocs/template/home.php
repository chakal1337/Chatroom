<input class='form-control' id='message' type='text'>
<button class='btn btn-primary' onclick='sendmsg()'>Send</button>
<div id='erroralert'></div>
<div id='loginresponse'>
</div>

<script>
 function sendmsg() {
  let message = $('#message').val();
  $.post( "api.php", { message: message, submit: "message"})
  .done(function( data ) {
    $('#erroralert').html(data);
	$('#message').val("");
  });
 }
 function refresherr() {
  $('#erroralert').html("");	 
 }
 function refreshmsg() {
  $.post( "api.php", { submit: "retreive"})
  .done(function( data ) {
    $('#loginresponse').html(data);
  });
 }
 setInterval(refreshmsg, 1000);
 setInterval(refresherr, 2000);
</script>