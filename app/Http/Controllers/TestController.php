<?php

namespace App\Http\Controllers;

use App\Infrastructure\Domain\Repository\UserRepository;
use App\Infrastructure\Kafka\Producer;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        //
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = $this->userRepository->get();

        $message = Producer::produce("laravel", "Hello world");

        return ["message" => $users];
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
