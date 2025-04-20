<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreClientRequest;
use App\Http\Requests\Api\V1\UpdateClientRequest;
use App\Http\Resources\V1\ClientResource;
use App\Models\Client;

/**
 * @OA\Tag(
 *     name="Clients",
 *     description="API Endpoints for managing clients"
 * )
 */
class ClientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/clients",
     *     summary="List all clients",
     *     tags={"Clients"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of clients",
     *
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function index()
    {
        return ClientResource::collection(Client::paginate());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/clients",
     *     summary="Create a new client",
     *     tags={"Clients"},
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
     *                 @OA\Property(
     *                     property="attributes",
     *                     type="object",
     *                     required={"name", "email"},
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="email", type="string", format="email"),
     *                     @OA\Property(property="phone", type="string"),
     *                     @OA\Property(property="address", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Client created successfully"
     *     )
     * )
     */
    public function store(StoreClientRequest $request)
    {
        return new ClientResource(Client::create($request->mappedAttributes()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/clients/{id}",
     *     summary="Get client by ID",
     *     tags={"Clients"},
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
     *         description="Client data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Client not found"
     *     )
     * )
     */
    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/clients/{id}",
     *     summary="Update a client",
     *     tags={"Clients"},
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
     *                 @OA\Property(
     *                     property="attributes",
     *                     type="object",
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="email", type="string", format="email"),
     *                     @OA\Property(property="phone", type="string"),
     *                     @OA\Property(property="address", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Client updated successfully"
     *     )
     * )
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->mappedAttributes());

        return new ClientResource($client);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/clients/{id}",
     *     summary="Delete a client",
     *     tags={"Clients"},
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
     *         description="Client deleted successfully"
     *     )
     * )
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return $this->ok('Client successfully deleted');
    }
}
