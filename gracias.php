<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  $title = "Plantas el Caminàs -> ";
  include("./include/header.php");
  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Producto.php';
  require './include/ElCaminas/Productos.php';
  use ElCaminas\Carrito;
  $carrito=new Carrito();
  if($carrito ->howMany()>0){
    $carrito ->empty();
  }
?>
<h1>Gracias por confiar en nosotros.</h1>
<a href="index.php" class="btn btn-success">Volver al inicio</a>
<?php
include("./include/footer.php");
?>
