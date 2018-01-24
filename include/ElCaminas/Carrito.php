<?php

namespace ElCaminas;
use \PDO;
use \ElCaminas\Producto;
class Carrito
{
    protected $connect;
    /** Sin parámetros. Sólo crea la variable de sesión
    */
    public function __construct()
    {
        global $connect;
        $this->connect = $connect;
        if (!isset($_SESSION['carrito'])){
            $_SESSION['carrito'] = array();
        }

    }
    public function addItem($id, $cantidad){
        $_SESSION['carrito'][$id] = $cantidad;
    }
    public function deleteItem($id){
      unset($_SESSION['carrito'][$id]);
    }
    public function empty(){
      unset($_SESSION['carrito']);
      self::__construct();
    }
    public function howMany(){
      return count($_SESSION['carrito']);
    }
    public function getTotal(){
      $total=0;
      if($this->howMany()>0){
        foreach ($_SESSION['carrito'] as $key => $cantidad) {
          $producto=new Producto($key);
          $subtotal=$producto->getPrecioReal()*$cantidad;
          $total=$total+$subtotal;
        }
      }
      return number_format($total , 2, '.', ' ') ;
    }
    public function toHtml(){
      //NO USAR, de momento
      $str = <<<heredoc
      <table class="table">
        <thead> <tr> <th>#</th> <th>Producto</th> <th>Cantidad</th> <th>Precio</th> <th>Total</th> <th>Eliminar</th> </tr> </thead>
        <tbody>
heredoc;
      if ($this->howMany() > 0){
        $i = 0;
        $total=0;
        foreach($_SESSION['carrito'] as $key => $cantidad){
          $producto = new Producto($key);
          $i++;
          $subtotal = $producto->getPrecioReal() * $cantidad;

          $subtotalTexto = number_format($subtotal , 2, ',', ' ') ;

          $str .=  "<tr><th scope='row'>$i</th><td><a href='" .  $producto->getUrl() . "'>" . $producto->getNombre() . "</a>";
          $str .=  "<a class='open-modal' title='Haga clic para ver el detalle del producto' href='".$producto->getUrl() ."'>";
          $str .= "<span style='color:#000' class='fa fa-external-link'></span></a></td><td>$cantidad</td>";
          $str .= "<td>" .  $producto->getPrecioReal() ." €</td><td>$subtotalTexto €</td>";
          $str .= " <td><a href='carro.php?action=delete&id=".$producto->getId()."' class='delete' onclick='return checkDelete()'>X</a></td></tr>";
          $total=$total+$subtotal;
          $totalTexto = number_format($total , 2, ',', ' ');
        }
        $str .= "<tr><th>TOTAL:</th><td></td><td></td><td></td><td>$totalTexto €</td>";
        $str .=  "<td><a href='carro.php?action=empty' onclick='return checkDeleteCarrito()' class='btn btn-danger'>Vaciar Carrito</a></td></tr>";

        if(isset($_GET['redirect'])){
          $redir=$_GET['redirect'];
          $str .= "<tr><th></th><td></td><td></td><td></td><td></td>";
          $str .=  "<td><a href='$redir' class='btn btn-primary'>Seguir comprando</a></td></tr>";
        }
        else{
          $str .= "<tr><th></th><td></td><td></td><td></td><td></td>";
          $str .=  "<td><a href='index.php' class='btn btn-primary'>Volver a Inicio</a></td></tr>";

        }
      }
      $str .= <<<heredoc
        </tbody>
      </table>

heredoc;
      return $str;
    }
}
