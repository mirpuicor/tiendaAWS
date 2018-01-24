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
  /*
    Para el contador del carrito en el header, habrá que mover el header y pintarlo
    después de que añada el producto a mi lista para que lo cuente
  */
  $carrito = new Carrito();
  $action ="";
  $id="";
  if(isset($_GET['action'])){
    $action=$_GET['action'];
  }
    switch ($action) {
      case "add":
        $id=$_GET['id'];
        $carrito->addItem($id, 1);
        break;
      case "delete":
        $id=$_GET['id'];
        $carrito->deleteItem($id);
        break;
      case "empty":
        $carrito->empty();
        break;
      }

?>

<script>
function checkDelete(){

	if (confirm("¿Seguro que desea borrar este producto?"))
		return true;
	else
		return false;
}
function checkDeleteCarrito(){

	if (confirm("¿Seguro que desea vaciar el carrito?"))
		return true;
	else
		return false;
}
</script>

  <div class="row carro">
    <h2 class='subtitle' style='margin:0'>Carrito de la compra</h2>
    <?php  echo $carrito->toHtml();?>
    <div id="paypal-button-container"></div>
    <?php if ($carrito->howMany() >0){?>
          <script src="https://www.paypalobjects.com/api/checkout.js"></script>
          <script>
              paypal.Button.render({

                  env: 'sandbox', // sandbox | production

                  // PayPal Client IDs - replace with your own
                  // Create a PayPal app: https://developer.paypal.com/developer/applications/create
                  client: {
                      sandbox:    'AURtFahgo3cuV-8J35gOhzh0AhTk36fnkHRkuGs-ZBiDoRdzd4NGvRDFFvzkCqmoU3puoZ3FOyS2zkDX',
                      production: '<insert production client id>'
                  },

                  // Show the buyer a 'Pay Now' button in the checkout flow
                  commit: true,

                  // payment() is called when the button is clicked
                  payment: function(data, actions) {

                      // Make a call to the REST api to create the payment
                      return actions.payment.create({
                          payment: {
                              transactions: [
                                  {
                                      amount: { total: '<?php echo $carrito->getTotal();?>', currency: 'EUR' }
                                  }
                              ]
                          }
                      });
                  },

                  // onAuthorize() is called when the buyer approves the payment
                  onAuthorize: function(data, actions) {

                      // Make a call to the REST api to execute the payment
                      return actions.payment.execute().then(function() {
                          window.alert('Pago realizado!');
                          document.location.href = 'gracias.php';
                      });
                  }

              }, '#paypal-button-container');

          </script>

    <?php }?>
  </div>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Detalle del producto</h4>
        </div>
        <div class="modal-body">
          <iframe src='#' width="100%" height="600px" frameborder=0 style='padding:8px'></iframe>
        </div>
      </div>
    </div>
  </div>
<?php
$bottomScripts = array();
$bottomScripts[] = "modalIframeProducto.js";

include("./include/footer.php");
?>
