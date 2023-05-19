<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActive;
use App\Models\UserBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $current_page = $request->query('current_page', 1);
        $data = new User();

        // Apply filters
        $fillable_column = (new User())->getFillable();
        foreach ($fillable_column as $column) {
            if ($request->query($column)) {
                $data = $data->where($column, 'like', '%' . $request->query($column) . '%');
            }
        }

        // Include related data
        if ($request->query('include')) {
            $includes = $request->query('include');
            foreach ($includes as $include) {
                $data = $data->with($include);
            }
        }

        // Apply is_active condition and paginate
        $data = $data->where('is_deleted', false)->paginate(10, ['*'], 'page', $current_page);

        return response()->json([
            'status' => 'success',
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'total_records' => $data->total(),
            ],
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'full_name' => '',
            'gender' => 'required|in:M,F',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'id_card' => 'required|string',
            'tax_registration_number' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'employment_status' => 'required|string',
            'authorization_level' => 'required|in:1,2,3',
            'business_certificate' => '',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $user_active = UserActive::create([
            'phone_number' => false,
            'email' => false,
            'id_card' => false,
            'tax_registration_number' => false,
        ]);

        $user_bank = UserBank::create([
            'bank_name' => '',
            'bank_account_number' => '',
            'bank_account_name' => '',
        ]);


        $data = User::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'id_card' => $request->id_card,
            'tax_registration_number' => $request->tax_registration_number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'employment_status' => $request->employment_status,
            'authorization_level' => $request->authorization_level,
            'business_certificate' => $request->business_certificate,
            'id_user_active' => $user_active->id,
            'id_user_bank' => $user_bank->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function show($id)
    {
        $data = User::find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'full_name' => '',
            'gender' => 'required|in:M,F',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'id_card' => 'required|string',
            'tax_registration_number' => 'required|string',
            'email' => 'required|email|unique:users',
            'employment_status' => 'required|string',
            'authorization_level' => 'required|in:1,2,3',
            'business_certificate' => '',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $data = User::find($id);
        $data->name = $request->name;
        $data->date_of_birth = $request->date_of_birth;
        $data->full_name = $request->full_name;
        $data->gender = $request->gender;
        $data->address = $request->address;
        $data->phone_number = $request->phone_number;
        $data->id_card = $request->id_card;
        $data->tax_registration_number = $request->tax_registration_number;
        $data->email = $request->email;
        $data->employment_status = $request->employment_status;
        $data->authorization_level = $request->authorization_level;
        $data->business_certificate = $request->business_certificate;
        $data->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }


    public function destroy($id)
    {
        $data = User::find($id);
        $data->is_deleted = true;
        $data->save();
        // $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }
}
