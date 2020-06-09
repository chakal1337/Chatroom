<input class='form-control' type='text' id='username' placeholder='username'>
<input class='form-control' type='password' id='password' placeholder='password'>
<button class='btn btn-primary' onclick='sendregister()'>Register</button>
<div id='loginresponse'></div>

<script>
 function sendregister() {
  let username = $('#username').val();
  let password = $('#password').val();
  $.post( "api.php", { username: username, password: password, submit: "register"})
  .done(function( data ) {
    $('#loginresponse').html(data);
  });
 }

</script>