<?php

namespace App\Http\Controllers;

use App\Models\CameraSetting;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CameraSettingController extends Controller
{
    public function index(){
        return view('admin.camera-setting.index');
    }

    public function list(){
        $ptzSettings = CameraSetting::all();
        dd($ptzSettings);
    }

    public function store(Request $request) {
        $request->validate([
            'min_pan_limit' => 'required|numeric',
            'max_pan_limit' => 'required|numeric',
            'min_tilt_limit' => 'required|numeric',
            'max_tilt_limit' => 'required|numeric',
        ]);

        $zoomLevel = $request->input('zoom_level');
        $minPanLimit = $request->input('min_pan_limit');
        $maxPanLimit = $request->input('max_pan_limit');
        $minTiltLimit = $request->input('min_tilt_limit');
        $maxTiltLimit = $request->input('max_tilt_limit');

        // Store the 1x zoom limitation
        CameraSetting::updateOrCreate(
            [
                'camera_id' => 1,
                'zoom_level' => $zoomLevel,
                'pan_limit_min' => $minPanLimit,
                'pan_limit_max' => $maxPanLimit,
                'tilt_limit_min' => $minTiltLimit,
                'tilt_limit_max' => $maxTiltLimit
            ]
        );

        // Calculate and store limitations for higher zoom levels
        for ($i = 2; $i <= 40; $i++) {
            // Calculate the pan and tilt limits based on the zoom factor
            $adjustedMinPanLimit = $minPanLimit / $i;
            $adjustedMaxPanLimit = $maxPanLimit / $i;
            $adjustedMinTiltLimit = $minTiltLimit / $i;
            $adjustedMaxTiltLimit = $maxTiltLimit / $i;

            // Use updateOrCreate to store the settings in the database
            CameraSetting::updateOrCreate(
                [
                    'camera_id' => 1,
                    'zoom_level' => $i,
                    'pan_limit_min' => $adjustedMinPanLimit,
                    'pan_limit_max' => $adjustedMaxPanLimit,
                    'tilt_limit_min' => $adjustedMinTiltLimit,
                    'tilt_limit_max' => $adjustedMaxTiltLimit
                ]
            );
        }

        // Return a response
        return redirect()->route('admin.ptz_setting.list')->with('success', 'Camera settings saved successfully!');
    }

    public function getCameraInfo(){
        $cameraIP = "192.168.128.153";
        $username = "saver";
        $password = "5aver5aver";

        $client = new Client();

        // Make the request with Digest Authentication
        $response = $client->request('GET', "http://$cameraIP/axis-cgi/param.cgi?action=list", [
            'auth' => [$username, $password, 'digest'] // Use Digest Auth
        ]);
        $responseBody = (string) $response->getBody();

        dd($responseBody);

        // Check if the response is successful
        if ($response->getStatusCode() == 200) {
            // Decode JSON or handle the response data
            $cameraInfo = json_decode($response->getBody(), true);
            return response()->json($cameraInfo);
        }

        // Log the response if it's not successful
        Log::info('Camera API Response', [
            'status' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
        ]);

        // Return error if request fails
        return response()->json([
            'error' => 'Failed to retrieve camera info',
            'status_code' => $response->getStatusCode(),
            'response_body' => $response->getBody()->getContents(),
        ], 500);
    }

    public function updatePTZInfo(){
        $cameraIP = "192.168.128.153";
        $username = "saver";
        $password = "5aver5aver";

        $newParams = [
            'root.PTZ.Limit.L1.MaxPan' => 100,
            'root.PTZ.Limit.L1.MaxTilt' => 0,
        ];
        $queryString = http_build_query($newParams);

        $client = new Client();

        try {
            // Send the POST request to update the PTZ parameters with Digest authentication
            $response = $client->request('POST', "http://$cameraIP/axis-cgi/param.cgi?action=update&$queryString", [
                'auth' => [$username, $password, 'digest'] // Set digest auth
            ]);

            // Check if the response is successful
            if ($response->getStatusCode() === 200) {
                echo "PTZ parameters updated successfully!";
            } else {
                echo "Failed to update PTZ parameters.";
            }
        } catch (RequestException $e) {
            // Handle exceptions, such as connection errors or non-200 responses
            echo "Error: " . $e->getMessage();
        }
    }
}
