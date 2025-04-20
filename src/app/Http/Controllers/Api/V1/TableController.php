<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTableRequest;
use App\Http\Requests\Api\V1\UpdateTableRequest;
use App\Http\Resources\V1\TableResource;
use App\Models\Table;

/**
 * @OA\Tag(
 *     name="Tables",
 *     description="API Endpoints for managing tables"
 * )
 */
class TableController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/tables",
     *     summary="List all tables",
     *     tags={"Tables"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of tables",
     *
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function index()
    {
        return TableResource::collection(Table::paginate());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tables",
     *     summary="Create a new table",
     *     tags={"Tables"},
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
     *                     required={"name", "capacity"},
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="capacity", type="integer"),
     *                     @OA\Property(property="location", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Table created successfully"
     *     )
     * )
     */
    public function store(StoreTableRequest $request)
    {
        return new TableResource(Table::create($request->mappedAttributes()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tables/{id}",
     *     summary="Get table by ID",
     *     tags={"Tables"},
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
     *         description="Table data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Table not found"
     *     )
     * )
     */
    public function show(Table $table)
    {
        return new TableResource($table);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tables/{id}",
     *     summary="Update a table",
     *     tags={"Tables"},
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
     *                     @OA\Property(property="capacity", type="integer"),
     *                     @OA\Property(property="location", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Table updated successfully"
     *     )
     * )
     */
    public function update(UpdateTableRequest $request, Table $table)
    {
        $table->update($request->mappedAttributes());

        return new TableResource($table);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tables/{id}",
     *     summary="Delete a table",
     *     tags={"Tables"},
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
     *         description="Table deleted successfully"
     *     )
     * )
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return $this->ok('Table successfully deleted');
    }
}
