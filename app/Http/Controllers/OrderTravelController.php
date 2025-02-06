<?php

namespace App\Http\Controllers;

use App\Http\Requests\Travel\OrderTravelIndexRequest;
use App\Http\Requests\Travel\OrderTravelShowRequest;
use App\Http\Requests\Travel\OrderTravelStoreRequest;
use App\Http\Requests\Travel\OrderTravelUpdateRequest;
use App\Services\OrderTravelService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderTravelController extends Controller
{
    public function __construct(protected OrderTravelService $travelService)
    {
    }

    /**
     * @OA\Get(
     *     path="/order/travels",
     *     summary="Obtém os pedidos de viagem",
     *     tags={"Pedidos de Viagem"},
     *     @OA\Parameter(
     *        name="order_travel_status_id",
     *        in="query",
     *        description="Filtra pelo identificador de status do pedido.",
     *        example=1,
     *        required=false,
     *     @OA\Schema(
     *         type="integer",
     *         enum={"1 - Solicitado, 2 - Aprovado, 3 - Cancelado"},
     *         example=1
     *      ),
     *        style="form"
     *     ),
     *     @OA\Parameter(
     *          name="departure_date",
     *          in="query",
     *          description="Filtra pela data de inicio da viagem.",
     *          required=false,
     *          example="2025-02-05",
     *          @OA\Schema(type="date"),
     *          style="form"
     *       ),
     *       @OA\Parameter(
     *           name="return_date",
     *           in="query",
     *           description="Filtra pela data de retorno da viagem.",
     *           required=false,
     *           example="2025-02-10",
     *           @OA\Schema(type="date"),
     *           style="form"
     *        ),
     *        @OA\Parameter(
     *            name="destination",
     *            in="query",
     *            description="Filtra pelo destino.",
     *            required=false,
     *            example="Belo Horizonte",
     *            @OA\Schema(type="string"),
     *            style="form"
     *      ),
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         description="Define se o retorno será com paginação.",
     *         required=false,
     *         @OA\Schema(type="boolean"),
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Quantidade de registros por pagina.",
     *          required=false,
     *          @OA\Schema(type="int", default="15"),
     *          style="form"
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of orders"
     *     )
     * )
     */
    public function index(OrderTravelIndexRequest $request): JsonResponse
    {
        //TODO
        //Obter identificador do usuario apos autenticação
        $request['user_id'] = 1;
        return response()->json($this->travelService->getListOfTravels($request->validated()));
    }

    /**
     * @OA\Post(
     *  path="/order/travels",
     *  summary="Cria um pedido de viagem",
     *  tags={"Pedidos de Viagem"},
     *  @OA\RequestBody( description="Dados necessários para criar um pedido de viagem", required=true,
     *  @OA\JsonContent(ref="#/components/schemas/OrderTravelStoreRequest")),
     *      @OA\Response(
     *          response=200,
     *          description="Solicitação de viagem criada com sucesso.",
     *          @OA\JsonContent(
     *              type="object",
     *                  @OA\Property(property="message", type="string", example="Solicitação de viagem criada com sucesso."),
     *                  @OA\Property(property="data", type="object",
     *                  @OA\Property(property="name_applicant", type="string", example="Testesolicitandte"),
     *                  @OA\Property(property="destination", type="string", example="Belo Horizonte"),
     *                  @OA\Property(property="departure_date", type="string", format="date-time", example="2025-02-06 13:00:00"),
     *                  @OA\Property(property="return_date", type="string", format="date-time", example="2025-02-06 12:00:00"),
     *                  @OA\Property(property="user_id", type="integer", example=1)
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Ação não permitida para o usuário.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Você não tem permissão para realizar esta ação.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Solicitação inválida - dados fornecidos não são válidos.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="errors", type="object",
     *                   @OA\Property(property="name_applicant", type="array",
     *                       @OA\Items(type="string", example="O campo name applicant é obrigatório.")
     *                  ),
     *              ),
     *          ),
     *     ),
     * )
     */
    public function store(OrderTravelStoreRequest $request): JsonResponse
    {
        return response()->json([
            'message' => "Solicitação de viagem criada com sucesso",
            'data' => $this->travelService->create($request->validated())
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *  path="/order/travels/{id}",
     *  tags={"Pedidos de Viagem"},
     *  summary="Atualiza o status de um pedido",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Identificador do pedido",
     *          example="1",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          style="form"
     *      ),
     *       @OA\Parameter(
     *          name="order_travel_status_id",
     *          in="query",
     *          description="Identificador de status do pedido.",
     *          example="2",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              enum={2, 3},
     *              example=3
     *          ),
     *         style="form"
     *      ),
     *      @OA\Response(response=200, description="Produto atualizado com sucesso."),
     *      @OA\Response(response=401, description="Não autenticado."),
     *      @OA\Response(response=422, description="Conteúdo não processável."),
     *      @OA\Response(response=500, description="Erro interno do servidor."),
     *  ),
     * */
    public function update(OrderTravelUpdateRequest $request): JsonResponse
    {
        return response()->json($this->travelService->updateStatusId($request->validated()));
    }

    /**
     * @OA\Get(
     *  path="/order/travels/{id}",
     *  tags={"Pedidos de Viagem"},
     *  summary="Obtém um pedido pelo seu identificador",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Identificador do pedido",
     *          example="1",
     *          required=true,
     *          @OA\Schema(type="integer"),
     *          style="form"
     *      ),
     *      @OA\Response(response=200, description="Produto atualizado com sucesso."),
     *      @OA\Response(response=401, description="Não autenticado."),
     *      @OA\Response(response=422, description="Conteúdo não processável."),
     *      @OA\Response(response=500, description="Erro interno do servidor."),
     *  ),
     * */
    public function show(OrderTravelShowRequest $request): JsonResponse
    {
        return response()->json($this->travelService->findTravel($request->id));
    }
}
