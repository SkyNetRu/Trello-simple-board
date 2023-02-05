<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BoardResource;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BoardController extends BaseController
{
    public function columns ()
    {
        $columns = Column::with('cards')->get();
        return BoardResource::collection($columns);
    }

    public function updateColumns (Request $request) {
        $validated = $request->validate([
            'columns' => 'required|array'
        ]);

        foreach ($validated['columns'] as $column) {
            Card::upsert($column['cards'], ['id'], ['column_id', 'order']);
        }
    }

    public function addColumn (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $column = Column::create($validated);
        return new BoardResource($column);
    }

    public function deleteColumn (Column $column) {
        $column->delete();
    }

    public function addCard (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'column_id' => 'required|integer',
        ]);

        $maxOrder = Card::select('order')->where('column_id', $validated['column_id'])->orderBy('order', 'desc')->first();
        $validated['order'] = $maxOrder ? $maxOrder->order + 1 : 0;
        $card = Card::create($validated);

        return new BoardResource($card);
    }
}
