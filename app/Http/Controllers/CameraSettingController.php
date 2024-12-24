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
            'min_pan_limit' => 'required|numeric|min:-180',
            'max_pan_limit' => 'required|numeric|max:180',
            'min_tilt_limit' => 'required|numeric|min:-90',
            'max_tilt_limit' => 'required|numeric|max:20',
        ]);

        $sensorWidth = 6.3; //sensor width of camera
        $sensorHeight = 4.7;  //sensor height of camera
        $initialFocalLength = 3.9; //focal length of camera
        $hfovat1x = 70.7; //horizontal field of view at 1x.
        $vfovat1x = 43.5; //vertical field of view at 1x.

        // Get user input for 1x zoom limits
        $minPanLimit1x = $request->input('min_pan_limit'); // e.g., -90
        $maxPanLimit1x = $request->input('max_pan_limit'); // e.g., 180
        $minTiltLimit1x = $request->input('min_tilt_limit'); // e.g., -90
        $maxTiltLimit1x = $request->input('max_tilt_limit'); // e.g., 20

        // Truncate old settings if any exist
        if (CameraSetting::exists()) {
            CameraSetting::truncate();
        }

        // Store the settings for 1x zoom (initial setup)
        CameraSetting::updateOrCreate(
            [
                'camera_id' => 1,
                'zoom_level' => 1, // 1x zoom
                'hfov_left_right' => 0,
                'vfov_up_down' => 0,
                'pan_limit_min' => $minPanLimit1x,
                'pan_limit_max' => $maxPanLimit1x,
                'tilt_limit_min' => $minTiltLimit1x,
                'tilt_limit_max' => $maxTiltLimit1x
            ]
        );

        // Fetch zoom steps from the API
//        $cameraIP = "192.168.128.153";
//        $username = "saver";
//        $password = "5aver5aver";
//
//        $client = new Client();
//
//        // Make the request with Digest Authentication
//        $response = $client->request('GET', "http://$cameraIP/axis-cgi/com/ptz.cgi?query=attributes&format=json", [
//            'auth' => [$username, $password, 'digest'] // Use Digest Auth
//        ]);
//        $responseBody = (string) $response->getBody();
//        $zoomSteps = json_decode($responseBody)->{'Camera 1'}->zoomSteps;

        for($i = 2; $i <= 12; $i++) {
            $focalLength = $i * $initialFocalLength;

            //Calculating the horizontal and vertical field of view at each zoom level from 1 to 12.
            $hfov = round(2 * rad2deg(atan($sensorWidth / (2 * $focalLength))), 2);
            $vfov = round(2 * rad2deg(atan($sensorHeight / (2 * $focalLength))), 2);

            //FOV for left right and up down.
            $hfovDifferenceLeftRight = ($hfovat1x - $hfov)/2;
            $vfovDifferenceUpDown = ($vfovat1x - $vfov)/2;

            //FOV difference from 1x zoom.
            $minPanLimit = $minPanLimit1x - $hfovDifferenceLeftRight;
            $maxPanLimit = $maxPanLimit1x + $hfovDifferenceLeftRight;

            $minTiltLimit = $minTiltLimit1x - $vfovDifferenceUpDown;
            $maxTiltLimit = $maxTiltLimit1x + $vfovDifferenceUpDown;

            // Adjust the pan limits to stay within -180 to 180
            $adjustedMinPanLimit = round(getAdjustedPanLimit($minPanLimit), 2);
            $adjustedMaxPanLimit = round(getAdjustedPanLimit($maxPanLimit), 2);

            // Adjust the tilt limits to stay within -90 to 20
            $adjustedMinTiltLimit = round(getAdjustedTiltLimit($minTiltLimit),2);
            $adjustedMaxTiltLimit = round(getAdjustedTiltLimit($maxTiltLimit), 2);

            CameraSetting::updateOrCreate(
                [
                    'camera_id' => 1,
                    'zoom_level' => $i, // Save the zoom value for each level
                    'hfov_left_right' => $hfovDifferenceLeftRight,
                    'vfov_up_down' => $vfovDifferenceUpDown,
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
