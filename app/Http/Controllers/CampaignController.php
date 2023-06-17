<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Campaign;
use App\Models\CampaignBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $current_page = $request->query('current_page', 1);
        $data = new Campaign;

        // Apply filters
        $fillable_column = (new Campaign())->getFillable();
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
        // create campaign
        $field_campaign = $request->only((new Campaign())->getFillable());
        $field_campaign['id_user'] = $request->user()->id;
        if ($request->file('file_prospektus')) {
            $file_prospektus = $request->file('file_prospektus');
            $original_name = $file_prospektus->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_prospektus = $file_prospektus->storeAs('public/prospektus', $new_file_name);
            $prospektus_url = Storage::url($path_of_file_prospektus);
            $field_campaign['prospektus_url'] = $prospektus_url;
        }
        $data = Campaign::create($field_campaign);

        // create banner
        if ($request->file('file_banner')) {
            $array_banner_name = $request->banner_name;
            $array_file_banner = $request->file('file_banner');
            for ($i = 0; $i < count($array_file_banner); $i++) {
                $banner_name = isset($array_banner_name[$i]) ? $array_banner_name[$i] : null;
                $file_banner = $array_file_banner[$i];
                $original_name = $file_banner->getClientOriginalName();
                $timestamp = now()->timestamp;
                $new_file_name = $timestamp . '_' . $original_name;
                $path_of_file_banner = $file_banner->storeAs('public/banner', $new_file_name);
                $banner_url = Storage::url($path_of_file_banner);
                $field_banner['name'] = $banner_name ? $banner_name : $original_name;
                $field_banner['url'] = $banner_url;
                $banner = Banner::create($field_banner);

                // create campaign_banner
                $field_campaign_banner = [
                    'id_campaign' => $data->id,
                    'id_banner' => $banner->id,
                ];
                $campaign_banner = CampaignBanner::create($field_campaign_banner);
            }
        }

        // add logical here
        // TODO: add logical here

        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = new Campaign();
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
        $data = Campaign::find($id);
        $field_campaign['id_user'] = null;
        unset($field_campaign['id_user']);
        if ($request->file('file_prospektus')) {
            $file_prospektus = $request->file('file_prospektus');
            $original_name = $file_prospektus->getClientOriginalName();
            $timestamp = now()->timestamp;
            $new_file_name = $timestamp . '_' . $original_name;
            $path_of_file_prospektus = $file_prospektus->storeAs('public/prospektus', $new_file_name);
            $prospektus_url = Storage::url($path_of_file_prospektus);
            $field_campaign['prospektus_url'] = $prospektus_url;
        }
        $data->update($field_campaign);

        // add logical here
        // TODO: add logical here

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function destroy($id)
    {
        $data = Campaign::find($id);
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
