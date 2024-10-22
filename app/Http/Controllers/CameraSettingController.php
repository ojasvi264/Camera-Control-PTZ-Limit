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
        $ptzSetting = CameraSetting::first();
        return view('admin.camera-setting.index', compact('ptzSetting'));
    }

    public function list(){
        $ptzSettings = CameraSetting::simplePaginate(20);
        return view('admin.camera-setting.ptz_info', compact('ptzSettings'));
    }

    public function cameraInfo(){
        return view('admin.api.get_camera_info');
    }

    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'min_pan_limit' => 'required|numeric',
            'max_pan_limit' => 'required|numeric',
            'min_tilt_limit' => 'required|numeric',
            'max_tilt_limit' => 'required|numeric',
        ]);

        // Get user input for 1x zoom limits
        $minPanLimit1x = $request->input('min_pan_limit'); // e.g., -180
        $maxPanLimit1x = $request->input('max_pan_limit'); // e.g., 180
        $minTiltLimit1x = $request->input('min_tilt_limit'); // e.g., -180
        $maxTiltLimit1x = $request->input('max_tilt_limit'); // e.g., -50

        // Truncate old settings if any exist
        if (CameraSetting::exists()) {
            CameraSetting::truncate();
        }

        // Store the settings for 1x zoom (initial setup)
        CameraSetting::updateOrCreate(
            [
                'camera_id' => 1,
                'zoom_level' => 1, // 1x zoom
                'pan_limit_min' => $minPanLimit1x,
                'pan_limit_max' => $maxPanLimit1x,
                'tilt_limit_min' => $minTiltLimit1x,
                'tilt_limit_max' => $maxTiltLimit1x
            ]
        );

        // Loop to calculate and store limitations for zoom levels from 2x to 40x
        for ($zoomValue = 2; $zoomValue <= 9999; $zoomValue++) {
            // Calculate Zoom Level (X) using the formula
            $zoomLevelX = 1 + (($zoomValue - 1) / (9999 - 1)) * 39;

            // Adjust pan and tilt limits based on the zoom level (X)
            $adjustedMinPanLimit = round($minPanLimit1x / $zoomLevelX, 2); // Limits decrease as zoom level increases
            $adjustedMaxPanLimit = round($maxPanLimit1x / $zoomLevelX, 2);
            $adjustedMinTiltLimit = round($minTiltLimit1x / $zoomLevelX, 2);
            $adjustedMaxTiltLimit = round($maxTiltLimit1x / $zoomLevelX, 2);

            // Store the calculated limits for each zoom level (X)
            CameraSetting::updateOrCreate(
                [
                    'camera_id' => 1,
                    'zoom_level' => $zoomValue, // Save the zoom value for each level
                    'pan_limit_min' => $adjustedMinPanLimit,
                    'pan_limit_max' => $adjustedMaxPanLimit,
                    'tilt_limit_min' => $adjustedMinTiltLimit,
                    'tilt_limit_max' => $adjustedMaxTiltLimit
                ]
            );
        }

        // Return success response
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
// Get the response body as a string
        $responseBody = (string) $response->getBody();

        // Parse the response since it's not JSON but key-value pairs
        $cameraInfo = $this->parseKeyValueResponse($responseBody);

        // Check if the response is successful
        if ($response->getStatusCode() == 200) {
            // Return parsed response as JSON
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

    private function parseKeyValueResponse($responseBody)
    {
        $data = [];

        // Split the response by line
        $lines = explode("\n", $responseBody);

        // Iterate through each line and process key-value pairs
        foreach ($lines as $line) {
            // Check if the line contains a key-value pair
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2); // Split at the first '='
                $data[trim($key)] = trim($value); // Trim and store the key-value pair
            }
        }

        return $data;
    }
}
