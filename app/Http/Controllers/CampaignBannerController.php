<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\CampaignBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignBannerController extends Controller
{
    public function index(Request $request)
    {
        $current_page = $request->query('current_page', 1);
        $data = new CampaignBanner;

        // Apply filters
        $fillable_column = (new CampaignBanner())->getFillable();
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
        $id_campaign = $request->id_campaign;
        $data = [];

         // create banner
         if ($request->file('file_banner')) {
            $array_banner_name = $request->banner_name;
            $array_file_banner = $request->file('file_banner');
            for ($i = 0; $i < count($array_banner_name); $i++) {
                $banner_name = $array_banner_name[$i];
                $file_banner = $array_file_banner[$i];
                $path_of_file_banner = $file_banner->store('public/banner');
                $banner_url = Storage::url($path_of_file_banner);
                $field_banner['name'] = $banner_name;
                $field_banner['url'] = $banner_url;
                $banner = Banner::create($field_banner);

                // create campaign_banner
                $field_campaign_banner = [
                    'id_campaign' => $id_campaign,
                    'id_banner' => $banner->id,
                ];
                $data[$i] = CampaignBanner::create($field_campaign_banner);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = new CampaignBanner();
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
        $data = CampaignBanner::find($id);
        $data->update($request->only((new CampaignBanner())->getFillable()));
        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data,
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }


    public function destroy($id)
    {
        $data = CampaignBanner::find($id);
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
