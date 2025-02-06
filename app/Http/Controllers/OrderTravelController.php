<?php

namespace App\Http\Controllers;

use App\Http\Requests\Travel\OrderTravelIndexRequest;
use App\Http\Requests\Travel\OrderTravelShowRequest;
use App\Http\Requests\Travel\OrderTravelStoreRequest;
use App\Http\Requests\Travel\OrderTravelUpdateRequest;
use App\Services\OrderTravelService;
use Exception;
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
     *         enum={1,2,3},
     *         example="1"
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
     *       @OA\Response(
     *          response=200,
     *          description="Lista de viagens com paginação.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="current_page", type="integer", example=1),
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name_applicant", type="string", example="Teste de solicitante"),
     *                      @OA\Property(property="destination", type="string", example="Belo Horizonte"),
     *                      @OA\Property(property="departure_date", type="string", format="date-time", example="2025-02-06 13:00:00"),
     *                      @OA\Property(property="return_date", type="string", format="date-time", example="2025-02-06 12:00:00"),
     *                      @OA\Property(property="user_id", type="integer", example=1)
     *                  )
     *              ),
     *          ),
     *       ),
     *       @OA\Response(
     *           response=403,
     *           description="Ação não permitida para o usuário.",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Você não tem permissão para realizar esta ação.")
     *           )
     *       ),
     *  )
     */
    public function index(OrderTravelIndexRequest $request): JsonResponse
    {
        try {
            $filters = (object)$request->validated();
            return response()->json($this->travelService->getTravelsWithFilters($filters));

        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *  path="/order/travels",
     *  summary="Cria um pedido de viagem",
     *  tags={"Pedidos de Viagem"},
     *  @OA\RequestBody( description="Dados necessários para criar um pedido de viagem", required=true,
     *  @OA\JsonContent(ref="#/components/schemas/OrderTravelStoreRequest")),
     *      @OA\Response(
     *          response=201,
     *          description="Solicitação de viagem criada com sucesso.",
     *          @OA\JsonContent(
     *              type="object",
     *                  @OA\Property(property="message", type="string", example="Solicitação de viagem criada com sucesso."),
     *                  @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="name_applicant", type="string", example="Teste de solicitante"),
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
     *     @OA\Response(
     *           response=500,
     *           description="Erro inesperado ao criar pedido de viagem.",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="error", type="string", example="Erro inesperado ao criar pedido de viagem.")
     *           )
     *       ),
     * )
     */
    public function store(OrderTravelStoreRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'message' => "Solicitação de viagem criada com sucesso.",
                'data' => $this->travelService->createTravel($request->validated())
            ], Response::HTTP_CREATED);

        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
     *
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Status do pedido alterado com sucesso.",
     *           @OA\JsonContent(
     *               type="object",
     *                   @OA\Property(property="message", type="string", example="Status do pedido alterado com sucesso."),
     *                   @OA\Property(property="data", type="object",
     *                   @OA\Property(property="id", type="integer", example="1"),
     *                   @OA\Property(property="order_travel_status_id", type="integer", example="3"),
     *                   @OA\Property(property="name_applicant", type="string", example="Teste de solicitante"),
     *                   @OA\Property(property="destination", type="string", example="Belo Horizonte"),
     *                   @OA\Property(property="departure_date", type="string", format="date-time", example="2025-02-06 13:00:00"),
     *                   @OA\Property(property="return_date", type="string", format="date-time", example="2025-02-06 12:00:00"),
     *                   @OA\Property(property="user_id", type="integer", example=1)
     *               )
     *           ),
     *       ),
     *      @OA\Response(
     *           response=403,
     *           description="Ação não permitida para o usuário.",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Você não tem permissão para realizar esta ação.")
     *           )
     *       ),
     *      @OA\Response(
     *           response=422,
     *           description="Solicitação inválida - dados fornecidos não são válidos.",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="errors", type="object",
     *                    @OA\Property(property="order_travel_status_id", type="array",
     *                        @OA\Items(type="string", example="O campo order travel status id deve ser igual a 2 (Aprovado) ou 3 (Cancelado).")
     *                   ),
     *               ),
     *           ),
     *      ),
     *      @OA\Response(
     *             response=404,
     *             description="Pedido de viagem não encontrado. Verifique se o ID está correto.",
     *             @OA\JsonContent(
     *                 type="object",
     *                 @OA\Property(property="error", type="string", example="Pedido de viagem não encontrado. Verifique se o ID está correto.")
     *             )
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Erro inesperado ao atualizar o status do pedido.",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="error", type="string", example="Não foi possível atualizar o status da viagem. Por favor, entre em contato com o suporte.")
     *            )
     *        ),
     *  )
     * /
     * */
    public function update(OrderTravelUpdateRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'message' => "Status do pedido alterado com sucesso.",
                'data' => $this->travelService->updateTravelStatus($request->validated())
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
     *           @OA\Response(
     *            response=200,
     *            description="Pedido de viagem encontrado com sucesso.",
     *            @OA\JsonContent(
     *                type="object",
     *                    @OA\Property(property="message", type="string", example="Pedido de viagem encontrado com sucesso."),
     *                    @OA\Property(property="data", type="object",
     *                    @OA\Property(property="id", type="integer", example="1"),
     *                    @OA\Property(property="order_travel_status_id", type="integer", example="3"),
     *                    @OA\Property(property="name_applicant", type="string", example="Teste de solicitante"),
     *                    @OA\Property(property="destination", type="string", example="Belo Horizonte"),
     *                    @OA\Property(property="departure_date", type="string", format="date-time", example="2025-02-06 13:00:00"),
     *                    @OA\Property(property="return_date", type="string", format="date-time", example="2025-02-06 12:00:00"),
     *                    @OA\Property(property="user_id", type="integer", example=1)
     *                )
     *            ),
     *        ),
     *       @OA\Response(
     *            response=403,
     *            description="Ação não permitida para o usuário.",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="Você não tem permissão para realizar esta ação.")
     *            )
     *        ),
     *       @OA\Response(
     *              response=404,
     *              description="Pedido de viagem não encontrado. Verifique se o ID está correto",
     *              @OA\JsonContent(
     *                  type="object",
     *                  @OA\Property(property="error", type="string", example="Pedido de viagem não encontrado. Verifique se o ID está correto.")
     *              )
     *       ),
     *       @OA\Response(
     *             response=500,
     *             description="Erro inesperado ao buscar os dados do pedido.",
     *             @OA\JsonContent(
     *                 type="object",
     *                 @OA\Property(property="error", type="string", example="Erro inesperado ao buscar os dados do pedido.")
     *             )
     *         ),
     *  ),
     * */
    public function show(OrderTravelShowRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'message' => "Pedido de viagem encontrado com sucesso.",
                'data' => $this->travelService->findById($request->id)
            ]);

        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
