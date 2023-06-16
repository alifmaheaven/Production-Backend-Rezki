<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $current_page = $request->query('current_page', 1);
        $data = new Banner;

        // Apply filters
        $fillable_column = (new Banner())->getFillable();
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
        $field_banners = $request->only((new Banner())->getFillable());
        if ($request->file('file_banner')) {
            $file_banner = $request->file('file_banner');
            $original_name = $file_banner->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_banner = $file_banner->storeAs('public/banner', $new_file_name);
            $banner_url = Storage::url($path_of_file_banner);
            $field_banners['url'] = $banner_url;
        }
        $data = Banner::create($field_banners);
        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = new Banner();
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
        $data = Banner::find($id);
        $field_banners = $request->only((new Banner())->getFillable());
        if ($request->file('file_banner')) {
            $file_banner = $request->file('file_banner');
            $original_name = $file_banner->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_banner = $file_banner->storeAs('public/banner', $new_file_name);
            $banner_url = Storage::url($path_of_file_banner);
            $field_banners['url'] = $banner_url;
        }
        $data->update($field_banners);
        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }


    public function destroy($id)
    {
        $data = Banner::find($id);
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
