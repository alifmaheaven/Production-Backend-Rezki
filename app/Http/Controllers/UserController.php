<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActive;
use App\Models\UserBank;
use App\Models\UserBusiness;
use App\Models\UserHeir;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            're_password' => 'required|string|same:password',
            'authorization_level' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        // Create user active
        $user_active = UserActive::create();

        // // Create user bank
        $field_user_bank = $request->only((new UserBank)->getFillable());
        $user_bank = UserBank::create($field_user_bank);

        // Create user business
        $field_user_bussiness = $request->only((new UserBusiness)->getFillable());
        $field_user_bussiness['name'] = $request->user_business_name;
        $field_user_bussiness['address'] = $request->user_business_address;
        if ($request->file('file_certificate')) {
            $file_certificate = $request->file('file_certificate');
            $path_of_file_certificate = $file_certificate->store('public/certificate');
            $certificate_url = Storage::url($path_of_file_certificate);
            $field_user_bussiness['certificate_url'] = $certificate_url;
        }
        $user_bussiness = UserBusiness::create($field_user_bussiness);

        // // Create user heir
        $field_user_heir = $request->only((new UserHeir)->getFillable());
        $field_user_heir['name'] = $request->user_heir_name;
        $field_user_heir['relationship'] = $request->user_heir_relationship;
        $field_user_heir['phone_number'] = $request->user_heir_phone_number;
        $field_user_heir['address'] = $request->user_heir_address;
        $user_heir = UserHeir::create($field_user_heir);

        // Create user image
        $field_user_image = $request->only((new UserImage)->getFillable());
        if ($request->file('file_id_card')) {
            $file_id_card = $request->file('file_id_card');
            $path_of_file_id_card = $file_id_card->store('public/id_card');
            $id_card_url = Storage::url($path_of_file_id_card);
            $field_user_image['id_card_url'] = $id_card_url;
        }
        if ($request->file('file_id_card_with_face')) {
            $file_id_card_with_face = $request->file('file_id_card_with_face');
            $path_of_file_id_card_with_face = $file_id_card_with_face->store('public/id_card_with_face');
            $id_card_with_face_url = Storage::url($path_of_file_id_card_with_face);
            $field_user_image['id_card_with_face_url'] = $id_card_with_face_url;
        }
        if ($request->file('file_selfie')) {
            $file_selfie = $request->file('file_selfie');
            $path_of_file_selfie = $file_selfie->store('public/selfie');
            $selfie_url = Storage::url($path_of_file_selfie);
            $field_user_image['selfie_url'] = $selfie_url;
        }
        $user_image = UserImage::create($field_user_image);

        // // Create user
        $field_user = $request->only((new User)->getFillable());
        $field_user['password'] = bcrypt($request->password);
        $field_user['id_user_active'] = $user_active->id;
        $field_user['id_user_bank'] = $user_bank->id;
        $field_user['id_user_business'] = $user_bussiness->id;
        $field_user['id_user_heir'] = $user_heir->id;
        $field_user['id_user_image'] = $user_image->id;
        $data = User::create($field_user);

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
        // update user
        $data = User::find($id);

        $field_user = $request->only((new User)->getFillable());
        $field_user['password'] = null;
        unset($field_user['password']);
        $data->update($field_user);

        // update user bank
        $field_user_bank = $request->only((new UserBank)->getFillable());
        $data->user_bank->update($field_user_bank);

        // update user business
        $field_user_bussiness = $request->only((new UserBusiness)->getFillable());
        if($request->user_business_name){
            $field_user_bussiness['name'] = $request->user_business_name;
        }
        if($request->user_business_address){
            $field_user_bussiness['address'] = $request->user_business_address;
        }
        if ($request->file('file_certificate')) {
            $file_certificate = $request->file('file_certificate');
            $path_of_file_certificate = $file_certificate->store('public/certificate');
            $certificate_url = Storage::url($path_of_file_certificate);
            $field_user_bussiness['certificate_url'] = $certificate_url;
        }
        $data->user_business->update($field_user_bussiness);

        // Update user heir
        $field_user_heir = $request->only((new UserHeir)->getFillable());
        if($request->user_heir_name){
            $field_user_heir['name'] = $request->user_heir_name;
        }
        if($request->user_heir_relationship){
            $field_user_heir['relationship'] = $request->user_heir_relationship;
        }
        if($request->user_heir_phone_number){
            $field_user_heir['phone_number'] = $request->user_heir_phone_number;
        }
        if($request->user_heir_address){
            $field_user_heir['address'] = $request->user_heir_address;
        }

        $data->user_heir->update($field_user_heir);

        // update user image
        $field_user_image = $request->only((new UserImage)->getFillable());
        if ($request->file('file_id_card')) {
            $file_id_card = $request->file('file_id_card');
            $path_of_file_id_card = $file_id_card->store('public/id_card');
            $id_card_url = Storage::url($path_of_file_id_card);
            $field_user_image['id_card_url'] = $id_card_url;
        }
        if ($request->file('file_id_card_with_face')) {
            $file_id_card_with_face = $request->file('file_id_card_with_face');
            $path_of_file_id_card_with_face = $file_id_card_with_face->store('public/id_card_with_face');
            $id_card_with_face_url = Storage::url($path_of_file_id_card_with_face);
            $field_user_image['id_card_with_face_url'] = $id_card_with_face_url;
        }
        if ($request->file('file_selfie')) {
            $file_selfie = $request->file('file_selfie');
            $path_of_file_selfie = $file_selfie->store('public/selfie');
            $selfie_url = Storage::url($path_of_file_selfie);
            $field_user_image['selfie_url'] = $selfie_url;
        }
        $data->user_image->update($field_user_image);

        return response()->json([
            'status' => 'success',
            'message' => 'Data update successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function change_password_user(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string',
            're_password' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $data = User::find($id);

        $currentPassword = $request->input('current_password');
        if (!Hash::check($currentPassword, $data->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect.',
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $field_user = $request->only(['password']);
        $field_user['password'] = bcrypt($request->password);
        $data->update($field_user);

        return response()->json([
            'status' => 'success',
            'message' => 'Data update successfully',
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
