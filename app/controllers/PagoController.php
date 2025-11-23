<?php
// app/controllers/PagoController.php

// Para una implementación real, instalarías el SDK de Stripe vía Composer
// require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../models/Factura.php';

class PagoController {

    public function procesar() {
        session_start();
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Paciente') {
            header("Location: index.php");
            exit;
        }

        $id_factura = filter_input(INPUT_GET, 'factura_id', FILTER_SANITIZE_NUMBER_INT);
        if (!$id_factura) {
            // Manejar error
            exit;
        }

        // 1. Obtener los detalles de la factura desde la BD
        $db = (new Database())->getConnection();
        $factura_model = new Factura($db);
        // Deberías crear un método "buscarPorId" en el modelo Factura
        // $factura = $factura_model->buscarPorId($id_factura);
        
        // --- LÓGICA DE INTEGRACIÓN CON STRIPE (EJEMPLO) ---
        
        // **Paso A: Configurar tu clave secreta de Stripe**
        // \Stripe\Stripe::setApiKey('sk_test_TU_CLAVE_SECRETA');

        // **Paso B: Crear una Sesión de Checkout**
        // Esto le dice a Stripe qué producto se está comprando, el precio y a dónde redirigir al usuario.
        /*
        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd', // O tu moneda local
                        'product_data' => [
                            'name' => 'Consulta Médica - Factura #' . $factura['id_factura'],
                        ],
                        'unit_amount' => $factura['monto_total'] * 100, // El monto debe estar en centavos
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost/sistema_citas/index.php?controller=Pago&action=exito',
                'cancel_url' => 'http://localhost/sistema_citas/index.php?controller=Pago&action=cancelado',
            ]);

            // **Paso C: Redirigir al usuario a la página de pago de Stripe**
            header("HTTP/1.1 303 See Other");
            header("Location: " . $session->url);

        } catch (Exception $e) {
            // Manejar el error de la API de Stripe
            echo 'Error: ' . $e->getMessage();
        }
        */

        // **Placeholder mientras no se integra Stripe**
        echo "Redirigiendo a la pasarela de pago para la factura #{$id_factura}... (Integración pendiente)";
    }

    public function exito() {
        // Lógica para cuando el pago es exitoso
        // Stripe redirige aquí. Aquí deberías verificar el estado de la sesión
        // y actualizar el estado de la factura en tu base de datos a 'Pagada'.
        echo "¡Gracias por su pago! Su transacción ha sido completada.";
    }

    public function cancelado() {
        // Lógica para cuando el usuario cancela el pago
        echo "El pago ha sido cancelado. Puede intentarlo de nuevo desde su perfil.";
    }
}
?>