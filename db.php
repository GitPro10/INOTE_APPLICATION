<?php

// Connection to the database called INOTE

$conn =  mysqli_connect("localhost","root","","INOTE");
if (!$conn) {
  echo("
  <script>
  alert('Facing Errors While Connecting To The DB!');
</script> Simplified Error -->
".mysqli_connect_error());
  
}


?>