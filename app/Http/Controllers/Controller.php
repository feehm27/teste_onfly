<?php

namespace App\Http\Controllers;

/**
 * @OA\Server(
 *    url="/api/v1"
 * ),
 * @OA\Info(
 *      title="Onfly - Pedido de Viagens",
 *      version="1.0",
 *      description="Serviço que gerencia os pedidos de viagens dos clientes"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Utilize um token Bearer para autenticação nas rotas protegidas da API"
 * ),
 */
abstract class Controller
{
    //
}
