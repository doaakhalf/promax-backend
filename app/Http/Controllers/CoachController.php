<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coaches = Coach::with('user')->get()->map(function($coach) {
            $user = $coach->user;
            $coachData = $coach->toArray();
            $userData = $user ? $user->toArray() : [];
            
            // Remove the nested user object and user_id from coach data
            unset($coachData['user'], $coachData['user_id']);
            
            // Merge user data into coach data
            return array_merge($coachData, $userData);
        });

        return response()->json([
            'message' => 'All coaches with their profiles',
            'coaches' => $coaches,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
