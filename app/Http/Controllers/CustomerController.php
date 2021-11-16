<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomersCollection;
use App\Http\Resources\CustomerShowResource;
use App\Models\Customer;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CustomersCollection
     */
    public function index()
    {
        return CustomersCollection::make(Customer::paginate(25));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function show($id)
    {
        try {
            $customer = Customer::find($id);
            if (!$customer) {
                return response()->json(['error' => 'Not found'], 404);
            }
            $resource = new CustomerShowResource($customer);
            $resource->withoutWrapping();
            return $resource;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
