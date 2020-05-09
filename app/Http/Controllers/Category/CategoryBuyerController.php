<?php

namespace App\Http\Controllers\Category;

use App\Buyer;
use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Facade\FlareClient\Api;
use Illuminate\Http\Request;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        //
        $buyers = $category->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();

        return response()->json(['data' => $buyers], 200);
        //  return $this->showAll($sellers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
