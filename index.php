<?php
session_start();
$title = "Plantas el Caminàs -> Inicio";

include("./include/funciones.php");
$connect = connect_db();

$state="normal";
if(isset($_GET['state'])){
  $state=$_GET['state'];
}
//Cambios para GITHUB 
if ("normal" == $state)
 include("./include/header.php");
 else if("popup" == $state){
   $urlCanonical = $producto->getUrl();
   include("./include/header-popup.php");
   } else if("json" == $state){
     echo $producto->getJson();
     exit();
   }else if("exclusive"==$state){
   }

require './include/ElCaminas/Carrito.php';
require './include/ElCaminas/Productos.php';
require './include/ElCaminas/Producto.php';

use ElCaminas\Carrito;
use ElCaminas\Productos;
use ElCaminas\Producto;
$productos = new Productos();
?>

                <div class="row carousel-holder">

                    <div class="col-md-12">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                              <?php

                              $query = " SELECT * FROM productos WHERE carrusel IS NOT NULL LIMIT 3";

                              $statement = $connect->prepare($query);

                              $statement->execute();

                              $count = $statement->rowCount();
                              $i = 0;
                              if($count > 0){
                                $result = $statement->fetchAll();
                                foreach($result as $row){
                                  echo "<li data-target='#carousel-example-generic' data-slide-to='$i' class='" . (($i == 0) ? 'active': '') . "'></li>";
                                  $i++;
                                }
                              }
                              ?>
                            </ol>
                            <div class="carousel-inner">
                              <?php

                            	$statement->execute();

                            	$count = $statement->rowCount();
                              $i = 0;
                            	if($count > 0){
                            		$result = $statement->fetchAll();
                            		foreach($result as $row){
                                  echo "<div class='item " . (($i == 0) ? 'active': '') ."'>";
                                  echo "<a href='./producto.php?id=" . $row["id"] . "'><img class='slide-image' src='./basededatos/img/" . $row["carrusel"] . "'></a>";
                                  echo "</div>";
                                  $i++;
                            		}
                            	}
                              ?>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="row">
                  <h2 class='subtitle'>Destacados</h2>
                  <?php
                  foreach($productos->getDestacados() as $producto){
                     echo $producto->getThumbnailHtml();
                  }
                  ?>
                </div>
                <div class="row">
                  <h2 class='subtitle'>Novedades</h2>
                  <?php
                  foreach($productos->getNovedades() as $producto){
                     echo $producto->getThumbnailHtml();
                  }
                  ?>
                </div>
                <!--* Ventana modal -->
                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Detalle del producto</h4>
                        </div>
                        <div class="modal-body">
                          <div id='data-container'></div>
                        </div>
                      </div>
                    </div>
                  </div>
<?php
$bottomScripts = array();
$bottomScripts[] = "modalDomProducto.js";
if ("normal" == $state)
 include("./include/footer.php");
 else if("popup" == $state){
   $urlCanonical = $producto->getUrl();
   include("./include/footer-popup.php");
   } else if("json" == $state){
     echo $producto->getJson();
     exit();
   }
  ?>
