<?php

namespace App\Http\Controllers;

use App\Infrastructure\Kafka\Producer;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $kafkaProducer = new Producer();
        $message = $kafkaProducer::produce("hello","world");
        // $kafkaProducer->produce("test", "Hello World");
        return ["message" => $message];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
