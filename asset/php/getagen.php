<?php

  if(isset($_POST['nama'])){
    $nama = $_POST['nama'];

    echo json_encode('[{nama: $nama}, {nama: mal}]');
  }
  else{
    echo "error";
  }

?>
