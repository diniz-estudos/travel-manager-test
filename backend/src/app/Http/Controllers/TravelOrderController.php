<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelOrder;
use Illuminate\Support\Facades\Validator;

use App\Notifications\TravelOrderStatusUpdated;

use App\Http\Resources\TravelOrderCollection;

/**
 * @OA\Schema(
 *     schema="TravelOrder",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="solicitado"
 *     ),
 *     @OA\Property(
 *         property="destino",
 *         type="string",
 *         example="New York"
 *     ),
 *     @OA\Property(
 *         property="data_ida",
 *         type="string",
 *         format="date",
 *         example="2023-10-01"
 *     ),
 *     @OA\Property(
 *         property="data_volta",
 *         type="string",
 *         format="date",
 *         example="2023-10-10"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="john.doe@example.com")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-01T12:00:00Z"
 *     )
 * )
 */

class TravelOrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/travel-orders",
     *     summary="Get list of travel orders",
     *     tags={"Travel Orders"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="destino",
     *         in="query",
     *         description="Filter by destination",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="data_ida",
     *         in="query",
     *         description="Filter by start date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="data_volta",
     *         in="query",
     *         description="Filter by end date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TravelOrder")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function index(Request $request)
    {
        $status = $request->query('status');
        $destino = $request->query('destino');
        $data_ida = $request->query('data_ida');
        $data_volta = $request->query('data_volta');

        $perPage = env('PER_PAGE', 10);

        $orders = TravelOrder::query();

        if ($request->has('status') && !empty($request->status)) {
            $orders->where('status', $request->status);
        }

        if ($request->has('destino')) {
            $orders->where('destino', 'like', '%' . $request->destino . '%');
        }

        if ($request->has('data_ida') && $this->isValidDate($request->data_ida)) {
            $orders->where('data_ida', '>=', $request->data_ida);
        }

        if ($request->has('data_volta') && $this->isValidDate($request->data_volta)) {
            $orders->where('data_volta', '<=', $request->data_volta);
        }

        if ($request->has('data_ida') && $request->has('data_volta') && $this->isValidDate($request->data_ida) && $this->isValidDate($request->data_volta)) {
            $orders->whereBetween('data_ida', [$request->data_ida, $request->data_volta]);
        }

        $orders = $orders->with('user')->paginate($perPage);

        return TravelOrderCollection::collection($orders);
    }

    private function isValidDate($date)
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date);
    }
    /**
     * @OA\Post(
     *     path="/travel-orders",
     *     summary="Create a new travel order",
     *     tags={"Travel Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"destino", "data_ida", "data_volta"},
     *             @OA\Property(property="destino", type="string", example="New York"),
     *             @OA\Property(property="data_ida", type="string", format="date", example="2023-10-01"),
     *             @OA\Property(property="data_volta", type="string", format="date", example="2023-10-10"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Travel order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destino' => 'required|string',
            'data_ida' => 'required|date',
            'data_volta' => 'required|date|after:data_ida',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = TravelOrder::create([
            'user_id' => auth()->id(),
            'solicitante' => auth()->user()->name,
            'destino' => $request->destino,
            'data_ida' => $request->data_ida,
            'data_volta' => $request->data_volta,
            'status' => 'solicitado',
        ]);

        return new TravelOrderCollection($order);
    }

    /**
     * @OA\Get(
     *     path="/travel-orders/{id}",
     *     summary="Get a specific travel order by ID",
     *     tags={"Travel Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the travel order",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Travel order not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show($id)
    {
        // $order = TravelOrder::where('user_id', auth()->id())->findOrFail($id);
        $order = TravelOrder::findOrFail($id);
        return new TravelOrderCollection($order);
    }

    /**
     * @OA\Patch(
     *     path="/travel-orders/{id}/status",
     *     summary="Update the status of a travel order",
     *     tags={"Travel Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the travel order",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="aprovado", enum={"aprovado", "cancelado"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Cannot update your own order"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Travel order not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $order = TravelOrder::findOrFail($id);

        if ($order->user_id === auth()->id()) {
            return response()->json(['error' => 'Você não pode alterar o status do seu próprio pedido'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:aprovado,cancelado',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order->update(['status' => $request->status]);

        $userNotifiable = $order->user;

        $userNotifiable->notify(new TravelOrderStatusUpdated($order));

        return new TravelOrderCollection($order);
    }

    /**
     * @OA\Patch(
     *     path="/travel-orders/{id}/cancel",
     *     summary="Cancel a travel order",
     *     tags={"Travel Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the travel order",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Travel order canceled successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - Only approved orders can be canceled"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Travel order not found"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function cancel($id)
    {
        // $order = TravelOrder::where('user_id', auth()->id())->findOrFail($id);
        $order = TravelOrder::findOrFail($id);

        if ($order->status !== 'aprovado') {
            return response()->json(['error' => 'Só é possível cancelar pedidos aprovados'], 400);
        }

        $order->update(['status' => 'cancelado']);

        $order->user->notify(new TravelOrderStatusUpdated($order));

        return new TravelOrderCollection($order);
    }

    /**
     * @OA\Get(
     *     path="/travel-orders/notifications",
     *     summary="Get user's unread notifications",
     *     tags={"Travel Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#Notification")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->unreadNotifications;

        return response()->json($notifications);
    }

    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notificação marcada como lida']);
    }
}
