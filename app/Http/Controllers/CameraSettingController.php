<?php

namespace App\Http\Controllers;

use App\Models\CameraSetting;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CameraSettingController extends Controller
{
    public function index(){
        return view('admin.camera-setting.index');
    }

    public function store(Request $request){
        $zoomLevel = $request->input('zoom_level', 1);
        $minPanLimit = $request->input('min_pan_limit');
        $maxPanLimit = $request->input('max_pan_limit');
        $minTiltLimit = $request->input('min_tilt_limit');
        $maxTiltLimit = $request->input('max_tilt_limit');
        $cameraId = 'your_camera_id'; // Dynamically set this based on the camera

        // Store the 1x zoom limitation
        CameraSetting::updateOrCreate(
            ['camera_id' => $cameraId, 'zoom_level' => $zoomLevel],
            [
                'min_pan_limit' => $minPanLimit,
                'max_pan_limit' => $maxPanLimit,
                'min_tilt_limit' => $minTiltLimit,
                'max_tilt_limit' => $maxTiltLimit
            ]
        );

        // Calculate and store limitations for higher zoom levels
        for ($i = 2; $i <= 40; $i++) {
            CameraSetting::updateOrCreate(
                ['camera_id' => $cameraId, 'zoom_level' => $i],
                [
                    'min_pan_limit' => $minPanLimit / $i,
                    'max_pan_limit' => $maxPanLimit / $i,
                    'min_tilt_limit' => $minTiltLimit / $i,
                    'max_tilt_limit' => $maxTiltLimit / $i
                ]
            );
        }
    return view('camera-setting.ptz_info');
    }

    public function getCameraInfo(){
        dd("here");
        $cameraIP = "192.168.128.153";
        $username = "saver";
        $password = "5aver5aver";

        $response = Http::withBasicAuth($username, $password)->get("http://192.168.128.153/api/v1/camera/info");

        if ($response->successful()){
            $cameraInfo = $response->json();
            return response()->json($cameraInfo);
        }
        return response()->json(['error' => 'Failed to retrieve camera info'], 500);
    }
}
