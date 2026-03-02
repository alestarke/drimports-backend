<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;

class ClientsController extends Controller
{
    public function index () {
        $clients = Client::whereNull('deleted_at')->paginate(15);

        return response()->json($clients);
    }

    public function store (CreateClientRequest $request) {
        try {
            $data = $request->validated();

            $client = Client::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to create client'], 422);
        }
    }

    public function show (Client $client) {
        return response()->json($client);
    }

    public function update (UpdateClientRequest $request, Client $client) {
        try {
            $data = $request->validated();

            $client->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully',
                'data' => $client
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update client'], 422);
        }
    }

    public function destroy (Client $client) {
        try {
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to delete client'], 422);
        }
    }
}
