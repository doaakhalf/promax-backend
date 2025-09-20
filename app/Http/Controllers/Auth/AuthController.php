<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteAthleteProfileRequest;
use App\Http\Requests\CompleteCoachProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Athlete;
use App\Models\Certificate;
use App\Models\Coach;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Traits\HandlesTokenAuthentication;

class AuthController extends Controller
{
    use HandlesTokenAuthentication;
    /**
     * Register a new user (athlete or coach)
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */

     /**
 * Handle user login
 *
 * @param Request $request
 * @return JsonResponse
 */
public function login(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    // Revoke existing tokens
    $user->tokens()->delete();

    // Create new token
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user->only(['id', 'email', 'role_id', 'status']),
    ]);
}
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create the user with minimal required fields
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => Role::where('name', $request->user_type)->first()->id,
                'status' => 'incomplete', // Will be updated during profile completion
            ]);


            // Generate token for authentication
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'message' => 'Registration successful. Please complete your profile.',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete athlete profile
     *
     * @param CompleteAthleteProfileRequest $request
     * @return JsonResponse
     */
    public function completeAthleteProfile(CompleteAthleteProfileRequest $request): JsonResponse
    {
        $image_url = null;
        $inbody_url = null;
        try {
            $user = $request->user();
            
            if ($user->role->name !== 'athlete') {
                return response()->json([
                    'message' => 'Only athletes can complete this profile',
                ], 403);
            }

            // Update user basic info
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'status' => 'pending'
            ]);
            if ($request->hasFile('photo')) {
               
                $path = $request->file('photo')->move(public_path('images/athletes'), $request->file('photo')->getClientOriginalName());
                $image_url = asset('images/athletes/' . $request->file('photo')->getClientOriginalName());
               
            }
            if ($request->hasFile('inbody_file')) {
                
                $path = $request->file('inbody_file')->move(public_path('images/athletes/inbody_files'), $request->file('inbody_file')->getClientOriginalName());
                $inbody_url = asset('images/athletes/inbody_files/' . $request->file('inbody_file')->getClientOriginalName());
               
            }
            // Update athlete profile
            $user->athleteProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => $request->gender,
                    'weight' => $request->weight,
                    'training_frequency' => $request->training_frequency,
                    'photo' => $image_url,
                    'inbody_file' => $inbody_url,

                ]
            );

            return response()->json([
                'message' => 'Athlete profile completed successfully',
                'user' => $user->load('athleteProfile'),
                'profile_complete' => true,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to complete athlete profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete coach profile and determine coach type based on sport
     *
     * @param CompleteCoachProfileRequest $request
     * @return JsonResponse
     */
    public function completeCoachProfile(CompleteCoachProfileRequest $request)
    {
    
       
        try {
            $authResult = $this->getAuthenticatedUser($request);
            
            if (!$authResult['success']) {
                return response()->json([
                    'message' => $authResult['error']['message'],
                ], $authResult['error']['code']);
            }
            
            $user = $authResult['user'];
            
            if (!$this->userHasRole($user, ['coach', 'gym_coach'])) {
                return $this->unauthorizedResponse('Only coaches can complete this profile');
            }


            // Determine coach type based on is_gym_sport
            $coachType = $user->role->name === 'gym_coach' ? 'gym_coach' : 'normal';
            
           

            // Update user basic info
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'status' => 'pending'
            ]);

            if ($request->hasFile('photo')) {
               
                $path = $request->file('photo')->move(public_path('images/coaches'), $request->file('photo')->getClientOriginalName());
                $url = asset('images/coaches/' . $request->file('photo')->getClientOriginalName());
               
            }

            // Update or create coach profile
            $coach = $user->coachProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'sport' => $request->sport,
                    'type' => $coachType,
                    'experience_years' => $request->experience_years,
                    'training_experience' => $request->training_experience,
                    'introduction' => $request->introduction,
                    'motivation' => $request->motivation,
                    'headline' => $request->headline,
                    'video_url' => $request->video_url,
                    'monthly_price_egp' => $request->monthly_price_egp,
                    'instapay_link' => $request->instapay_link,
                    'best_record' => $request->best_record,
                    'photo' => $url??null,
                   
                ]
            );
  

            // Add certifications if provided
            if ($request->has('certifications')) {
                // Delete existing certificates to avoid duplicates
                $coach->certificates()->delete();
                
                $certifications = collect($request->certifications)->map(function ($cert) use ($coach) {
                    return [
                        'coach_id' => $coach->id,
                        'certificate_name' => $cert['name'],
                        'issuing_organization' => $cert['issuing_organization'],
                        'year_obtained' => $cert['year_obtained'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
                
                // Use insert for better performance with multiple records
                if (!empty($certifications)) {
                    DB::table('certificates')->insert($certifications);
                }
            }

            return response()->json([
                'message' => 'Coach profile completed successfully',
                'user' => $user->load(['coachProfile', 'coachProfile.certificates']),
                'profile_complete' => true,
                'coach_type' => $coachType,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to complete coach profile',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
