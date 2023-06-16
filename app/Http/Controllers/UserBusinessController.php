<?php

namespace App\Http\Controllers;

use App\Models\UserBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserBusinessController extends Controller
{
    public function index(Request $request)
    {
        $current_page = $request->query('current_page', 1);
        $data = new UserBusiness;

        // Apply filters
        $fillable_column = (new UserBusiness())->getFillable();
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
        $data = UserBusiness::create($request->only((new UserBusiness())->getFillable()));
        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function show(Request $request, $id)
    {
        $field_user_business = $request->only((new UserBusiness())->getFillable());
        if ($request->file('file_certificate')) {
            $file_certificate = $request->file('file_certificate');
            $original_name = $file_certificate->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_certificate = $file_certificate->storeAs('public/certificate', $new_file_name);
            $certificate_url = Storage::url($path_of_file_certificate);
            $field_user_business['certificate_url'] = $certificate_url;
        }
        $data = new UserBusiness($field_user_business);
        // Include related data
        if ($request->query('include')) {
            $includes = $request->query('include');
            foreach ($includes as $include) {
                $data = $data->with($include);
            }
        }

        $data = $data->find($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = UserBusiness::find($id);
        $field_user_business = $request->only((new UserBusiness())->getFillable());
        if ($request->file('file_certificate')) {
            $file_certificate = $request->file('file_certificate');
            $original_name = $file_certificate->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_certificate = $file_certificate->storeAs('public/certificate', $new_file_name);
            $certificate_url = Storage::url($path_of_file_certificate);
            $field_user_business['certificate_url'] = $certificate_url;
        }
        $data->update($field_user_business);
        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }


    public function destroy($id)
    {
        $data = UserBusiness::find($id);
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
