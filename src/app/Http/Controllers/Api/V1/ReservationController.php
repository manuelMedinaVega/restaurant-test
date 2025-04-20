<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreReservationRequest;
use App\Http\Resources\V1\ReservationResource;
use App\Models\Reservation;

/**
 * @OA\Tag(
 *     name="Reservations",
 *     description="API Endpoints for managing reservations"
 * )
 */
class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/reservations",
     *     summary="List all reservations",
     *     tags={"Reservations"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of reservations",
     *
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function index()
    {
        return ReservationResource::collection(Reservation::with(['client', 'table'])->paginate());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reservations",
     *     summary="Create a new reservation",
     *     tags={"Reservations"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"data"},
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 required={"attributes", "relationships"},
     *                 @OA\Property(
     *                     property="attributes",
     *                     type="object",
     *                     required={"date", "time", "number_of_people"},
     *                     @OA\Property(property="date", type="string", format="date"),
     *                     @OA\Property(property="time", type="string", example="18:30"),
     *                     @OA\Property(property="number_of_people", type="integer", minimum=1)
     *                 ),
     *                 @OA\Property(
     *                     property="relationships",
     *                     type="object",
     *                     required={"client", "table"},
     *                     @OA\Property(
     *                         property="client",
     *                         type="object",
     *                         @OA\Property(
     *                             property="data",
     *                             type="object",
     *                             required={"id"},
     *                             @OA\Property(property="id", type="integer")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="table",
     *                         type="object",
     *                         @OA\Property(
     *                             property="data",
     *                             type="object",
     *                             required={"id"},
     *                             @OA\Property(property="id", type="integer")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Reservation created successfully"
     *     )
     * )
     */
    public function store(StoreReservationRequest $request)
    {
        $reservation = Reservation::create($request->mappedAttributes());
        $reservation->load(['client', 'table']);

        return new ReservationResource($reservation);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reservations/{id}",
     *     summary="Get reservation by ID",
     *     tags={"Reservations"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reservation data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['client', 'table']);

        return new ReservationResource($reservation);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reservations/{id}",
     *     summary="Update a reservation",
     *     tags={"Reservations"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"data"},
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 required={"attributes", "relationships"},
     *                 @OA\Property(
     *                     property="attributes",
     *                     type="object",
     *                     required={"date", "time", "number_of_people"},
     *                     @OA\Property(property="date", type="string", format="date"),
     *                     @OA\Property(property="time", type="string", example="18:30"),
     *                     @OA\Property(property="number_of_people", type="integer", minimum=1)
     *                 ),
     *                 @OA\Property(
     *                     property="relationships",
     *                     type="object",
     *                     required={"client", "table"},
     *                     @OA\Property(
     *                         property="client",
     *                         type="object",
     *                         @OA\Property(
     *                             property="data",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="table",
     *                         type="object",
     *                         @OA\Property(
     *                             property="data",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reservation updated successfully"
     *     )
     * )
     */
    public function update(StoreReservationRequest $request, Reservation $reservation)
    {
        $reservation->update($request->mappedAttributes());
        $reservation->load(['client', 'table']);

        return new ReservationResource($reservation);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reservations/{id}",
     *     summary="Delete a reservation",
     *     tags={"Reservations"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Reservation deleted successfully"
     *     )
     * )
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return $this->ok('Reservation successfully deleted');
    }
}
