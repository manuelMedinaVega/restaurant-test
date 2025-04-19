<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTableRequest;
use App\Http\Requests\Api\V1\UpdateTableRequest;
use App\Http\Resources\V1\TableResource;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TableResource::collection(Table::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTableRequest $request)
    {
        return new TableResource(Table::create($request->mappedAttributes()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        return new TableResource($table);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, Table $table)
    {
        $table->update($request->mappedAttributes());
        return new TableResource($table);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return $this->ok('Table successfully deleted');
    }
}
