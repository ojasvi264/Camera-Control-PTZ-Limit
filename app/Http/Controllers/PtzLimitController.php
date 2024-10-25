<?php

namespace App\Http\Controllers;

use App\Enum\ZoomValue;
use GuzzleHttp\Client;


class PtzLimitController extends Controller
{
    public function limitPTZ(){

        $minPan1x = -90;
        $maxPan1x = 180;
        $minTilt1x = -45;
        $maxTilt1x = 0;
        $cameraIP = "192.168.128.153";
        $username = "saver";
        $password = "5aver5aver";

        $client = new Client();

        // Make the request with Digest Authentication
        $response = $client->request('GET', "http://$cameraIP/axis-cgi/com/ptz.cgi?query=position", [
            'auth' => [$username, $password, 'digest'] // Use Digest Auth
        ]);
        $responseBody = (string) $response->getBody();
        $ptzInfo = $this->parseResponseToJson($responseBody);


        $limits = getPanTiltLimits();
        $matchingLimit = null;

        foreach (ZoomValue::cases() as $limit) {
            if ($ptzInfo['zoom'] == $limit->value) {
                $matchingLimit = $limit;
                break;
            }
        }
//        dd($matchingLimit);

        if ($matchingLimit){
            $minPanLimit = $minPan1x + ($limits[$matchingLimit->name]['panMin']);
            $maxPanLimit = $maxPan1x + ($limits[$matchingLimit->name]['panMax']);

            $minTiltLimit = $minTilt1x + ($limits[$matchingLimit->name]['tiltMin']);
            $maxTiltLimit = $maxTilt1x + ($limits[$matchingLimit->name]['tiltMax']);


            // Adjust the pan limits to stay within -180 to 180
            $minPanLimit = round(getAdjustedPanLimit($minPanLimit));
            $maxPanLimit = round(getAdjustedPanLimit($maxPanLimit));

            $minTiltLimit = round(getAdjustedTiltLimit($minTiltLimit));
            $maxTiltLimit = round(getAdjustedTiltLimit($maxTiltLimit));


            $response = $client->request('GET', "http://$cameraIP/axis-cgi/param.cgi?action=update&root.PTZ.Limit.L1.MaxPan=$maxPanLimit&root.PTZ.Limit.L1.MinPan=$minPanLimit&root.PTZ.Limit.L1.MinTilt=$minTiltLimit&root.PTZ.Limit.L1.MaxTilt=$maxTiltLimit", [
                'auth' => [$username, $password, 'digest'] // Use Digest Auth
            ]);

            $status = $response->getStatusCode();

            if ($status === 200){
                dd("Pan Limit Changed Successfully. MinPan: $minPanLimit, MaxPan: $maxPanLimit, MinTilt: $minTiltLimit, MaxTilt: $maxTiltLimit");
            } else {
                dd("Failed to change pan limit. Status code: $status");
            }

        } else{
            dd("Zoom Level not Matched. Please check again.");
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
