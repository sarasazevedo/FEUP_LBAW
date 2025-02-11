<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/restaurants/search",
     *     summary="Search for restaurants",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="username", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="image", type="string", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function search(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('q');
    
        // Search for restaurants by name, username, or email using a case-insensitive match
        $restaurants = Restaurant::join('user', 'restaurant.id', '=', 'user.id')
            ->where('user.name', 'ILIKE', "%{$query}%")
            ->orWhere('user.username', 'ILIKE', "%{$query}%")
            ->orWhere('user.email', 'ILIKE', "%{$query}%")
            ->get(['restaurant.id', 'user.name', 'user.username', 'user.email', 'user.image']);
    
        // Return the search results as a JSON response
        return response()->json($restaurants);
    }
}