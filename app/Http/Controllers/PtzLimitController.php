<?php

namespace App\Http\Controllers;

use App\Enum\ZoomValue;
use App\Models\CameraSetting;
use GuzzleHttp\Client;


class PtzLimitController extends Controller
{
    public function limitPTZ(){
        $cameraIP = "192.168.128.153";
        $username = "saver";
        $password = "5aver5aver";

        $client = new Client();

//         Make the request with Digest Authentication to get the current PTZ info.
        $response = $client->request('GET', "http://$cameraIP/axis-cgi/com/ptz.cgi?query=position", [
            'auth' => [$username, $password, 'digest'] // Use Digest Auth
        ]);
        $responseBody = (string) $response->getBody();
        $ptzInfo = $this->parseResponseToJson($responseBody);

//        $ptzInfo = [
//                'zoom' => 257,
//        ];
        foreach (getZoomValues() as $zoom){
            if ($ptzInfo['zoom'] == $zoom['value']);{
                $ptLimits = CameraSetting::where('zoom_level', $zoom['zoom'])->first();
                $maxPanLimit = $ptLimits->pan_limit_max;
                $minPanLimit = $ptLimits->pan_limit_min;
                $minTiltLimit = $ptLimits->tilt_limit_min;
                $maxTiltLimit = $ptLimits->tilt_limit_max;

                $response = $client->request('GET', "http://$cameraIP/axis-cgi/param.cgi?action=update&root.PTZ.Limit.L1.MaxPan=$maxPanLimit&root.PTZ.Limit.L1.MinPan=$minPanLimit&root.PTZ.Limit.L1.MinTilt=$minTiltLimit&root.PTZ.Limit.L1.MaxTilt=$maxTiltLimit", [
                    'auth' => [$username, $password, 'digest'] // Use Digest Auth
                ]);

                $status = $response->getStatusCode();

                if ($status === 200){
                    dd("Pan Limit Changed Successfully. MinPan: $minPanLimit, MaxPan: $maxPanLimit, MinTilt: $minTiltLimit, MaxTilt: $maxTiltLimit");
                } else {
                    dd("Failed to change pan limit. Status code: $status");
                }

            }
        }
    }

    private function parseResponseToJson($responseBody)
    {
        return array_reduce(
            explode("\n", trim($responseBody)),
            function ($acc, $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', trim($line), 2);
                    $acc[$key] = $value;
                }
                return $acc;
            },
            []
        );
    }
}
