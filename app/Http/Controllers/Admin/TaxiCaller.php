<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MtoTrip;
use App\Trip;
use App\ErrorLog;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Log;

// use Illuminate\Support\Facades\Http;
class TaxiCaller extends Controller
{
    protected $apiKey = 'd71ed021e8d32c4828f57db784e4d60e';
    protected $companyId = "34794";
    protected $reportTemplateId = 1870;
    protected $client;
    protected $jwtToken;
    protected $reportToken;
    protected $records;
    protected $insertedRecordsNumber;
    public function init()
    {
        Log::info('scheduler working');
        $this->client = new Client([
            'base_uri' => 'https://api.taxicaller.net/',
            'verify' => false,
        ]);

        $this->jwtToken = $this->fetchJwtToken();
        if($this->jwtToken)
        {
            $this->reportToken = $this->createReportsToken();
            if($this->reportToken)
            {
                $this->records = $this->fetchRecords();
                if($this->records)
                {
                    try {
                        $this->insertedRecordsNumber = $this->insertRecords($this->records);
                    } catch (\Throwable $e) {
                        $errorLog = new ErrorLog();
                        $errorLog->type = "inserting trips";
                        $errorLog->message = "local error in inserting trips function  : ". $e->getMessage();
                        $errorLog->save();
                    }
                }
                else{
                    return "cant fetch records";
                }
            }
            else{
                return "reportToken null";
            }
            if( $this->insertedRecordsNumber == 0)
            {
                //log 0 trip inserted
                $errorLog = new ErrorLog();
                $errorLog->type = "zero new tirps";
                $errorLog->message = "0 trips inserted";
                $errorLog->save();
                return "insertedRecordsNumber == 0";
            }
            else{
                $errorLog = new ErrorLog();
                $errorLog->type = "success";
                $errorLog->message = $this->insertedRecordsNumber." trips inserted";
                $errorLog->save();
                return "success : ".$this->insertedRecordsNumber." trip was inserted !";
            }
        }
        else{
            return "jwtToken null";
        }
       

    }
    public function fetchJwtToken()
    {
        $endPoint = "AdminService/v1/jwt/for-key?key=".$this->apiKey."&sub=*";
        $headers = [
            // 'Authorization' => 'Bearer ' . $this->apiKey,
            // 'Accept' => 'application/json',
        ];
        try {
            $response = $this->client->request('GET', $endPoint, [
                'headers' => $headers,
            ]);
        } catch (GuzzleException $e) {
            $errorMessage = $e->getMessage();
            Log::error('Something wrong in TaxiCaller fetch data.' . $errorMessage);
            $errorLog = new ErrorLog();
            $errorLog->type = "Jwt Token";
            $errorLog->message ="cant fetch Jwt Token : ". $errorMessage;
            $errorLog->save();
            
            return null;
        }
        $response = json_decode($response->getBody());
        $jwtToken = $response->token;
        return $jwtToken;
    }

    ///
    public function createReportsToken()
    {
        $endPoint = "AdminService/v1/company/" . $this->companyId . "/reports/jwt";
        $headers = [
            'Authorization' => 'Bearer ' . $this->jwtToken,
            'Accept' => 'application/json',
        ];
        try {
            $response = $this->client->request('GET', $endPoint, [
                'headers' => $headers,
            ]);
        } catch (GuzzleException $e) {
            $errorMessage = $e->getMessage();
            Log::error('Something wrong in TaxiCaller fetch data.' . $errorMessage);
            $errorLog = new ErrorLog();
            $errorLog->type = "report Token";
            $errorLog->message ="cant create report Token : ".  $errorMessage;
            $errorLog->save();
            return null;
        }
        $response = json_decode($response->getBody());
        $reportToken = $response->token;
        // dd($reportToken);
        return $reportToken;
    }
    //fetch records
    public function fetchRecords()
    {
        //endpoint
        $endPoint = "/api/v1/reports/generate";
        //headers
        $headers = [
            'Authorization' => 'Bearer ' . $this->reportToken,
            'Accept' => 'application/json',
        ];
        //get the latest trip inserted
        $trip = Trip::orderBy('pickup_date', 'desc')
            ->limit(1)
            ->first();
        //extract the trip date in the correct format
        $start_time = DateTime::createFromFormat("Y-m-d H:i:s", $trip->pickup_date)->getTimestamp();
        $start_time = date('Y-m-d', $start_time) . "T00:00:00";
        //create the end date from the current date
        $end_time = date("Y-m-d") . "T" . date("H:i:s");
        //prepare the json payload
        $payload = [
            "company_id" => $this->companyId,
            "report_type" => "jobs",
            "output_format" => "json",
            "template_id" => $this->reportTemplateId,
            "search_query" => [
                "period" => [
                    "@type" => "custom",
                    "start" => $start_time,
                    "end" => $end_time,
                ],
            ],
        ];

        try {
            $response = $this->client->request('POST', $endPoint, [
                'json' => $payload,
                'headers' => $headers,
            ]);
        } catch (GuzzleException $e) {
            $errorMessage = $e->getMessage();
            Log::error('Something wrong in TaxiCaller fetch data.' . $errorMessage);
            $errorLog = new ErrorLog();
            $errorLog->message = "cant fetch records : ". $errorMessage;
            $errorLog->type = "fetching records";
            $errorLog->save();
            return null;
        }
        $response = json_decode($response->getBody());
        $rows = $response->rows;
        return $rows;
    }

    public function insertRecords($trips)
    {
        $i = 0;
        foreach ($trips as $trip) {
            $tripExist = Trip::where('job_id', "Like", $trip->job_id)
                ->first();
            if (!$tripExist) {
                $trip = (array) $trip;
                $tripCreated = new MtoTrip();
                $tripCreated->vehicle = $trip['vehicle'];
                $tripCreated->job_id = $trip['job_id'];
                if (strlen($trip['last_name']) > 0) {
                    $tripCreated->badge_id = substr($trip['last_name'], -4)[0] == '0' ? substr($trip['last_name'], -3) : substr($trip['last_name'], -4);
                }

                $tripCreated->customer_name = $trip['client_name'];
                $tripCreated->pickup = $trip['pickup_adress'];
                $tripCreated->dropoff = $trip['dropoff_address'];
                $tripCreated->fare = number_format($trip['fare'] * 100, 2, '.', '');
                $tripCreated->tip = number_format($trip['tip'] * 100, 2, '.', '');
                $tripCreated->total = number_format($trip['total'] * 100, 2, '.', '');
                $tripCreated->account = $trip['account'];
                $tripCreated->po = $trip['po'];
                if (DateTime::createFromFormat("d/m/Y H:i", $trip['pickup_date']) == false) {
                    $tripCreated->pickup_date = DateTime::createFromFormat("d/m/Y H:i", "01/01/2000 00:00")->getTimestamp();
                } else {
                    $tripCreated->pickup_date = DateTime::createFromFormat("d/m/Y H:i", $trip['pickup_date'])->getTimestamp();
                }

                if (DateTime::createFromFormat("d/m/Y H:i", $trip['dropoff_date'])->getTimestamp() == false) {
                    $tripCreated->dropoff__date = DateTime::createFromFormat("d/m/Y H:i", "01/01/2000 00:00")->getTimestamp();
                } else {
                    $tripCreated->dropoff__date = DateTime::createFromFormat("d/m/Y H:i", $trip['dropoff_date'])->getTimestamp();
                }
                $tripCreated->storeThisTrip();
                $i++;
            }
        }

        return $i;

    }


    public function deleteInsertedTrips()
    {
        $trips = file_get_contents(__DIR__ . "/trips.json");

        $trips = json_decode($trips, true);

        $trips = $trips['rows'];
        $errors = [];
        // dd($trips);
        $nbdeleted_trips = 0;
        foreach ($trips as $trip) {
            $toDeleteTrips = Trip::where("job_id", "Like", $trip["job_id"])
                ->get();
            foreach ($toDeleteTrips as $toDeleteTrip) {
                $toDeleteTrip->delete();
                $nbdeleted_trips++;
            }

        }
        return "number of trips  deleted " . $nbdeleted_trips;

    }
    public function deleteTripsOfMonth()
    {
        $trips = Trip::where('date', '>=', "2020-04-10")
        ->get();
        $nbdeleted_trips = 0;
        foreach ($trips as $trip) {
            $trip->delete();
            $nbdeleted_trips++;
        }

        return "number of trips  deleted " . $nbdeleted_trips;

    }
    public function parseCSVtoJson()
    {
        ini_set("auto_detect_line_endings", true);

        $file=__DIR__ . "/data.csv";
        $csv= file_get_contents($file);
        $rows =  explode("\n", $csv);
        foreach ($rows as  $row) {
            $fields = explode(";", $row);
            $structuredRow['name']= $fields[0];
            $structuredRow['coords']= array(
                (float)$fields[1],
                (float)$fields[2]
            );
            $finalArray[]= $structuredRow;
        }
        $json = json_encode($finalArray);
        print_r($json);

    }
    
}
