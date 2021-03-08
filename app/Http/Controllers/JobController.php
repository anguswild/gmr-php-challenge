<?php

namespace App\Http\Controllers;

use App\Job;
use App\Jobs\newJob;
use App\Jobs\getJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobController extends ApiController
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:new-job', ['only' => ['newJob']]);
        $this->middleware('permission:get-job', ['only' => ['getJob']]);
        $this->middleware('permission:check-job', ['only' => ['checkJob']]);
    }

    /**
     * @OA\Post(
     * path="/api/jobs/new",
     * summary="New Job",
     * description="ENDPOINT so that the client can create a new job.",
     * operationId="newJob",
     * tags={"jobs"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Priority and Command of the Job",
     *    @OA\JsonContent(
     *       required={"priority","command"},
     *       @OA\Property(property="priority", type="string", format="priority", example="low"),
     *       @OA\Property(property="command", type="string", format="command", example="2+2"),
     *    ),
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Job Dispatched",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="Job details"),
     *       @OA\Property(property="message", type="string", example="Dispatched job {id}")
     *        )
     *     ),
     * )
     */
    public function newJob(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'priority'    => 'required|exists:priorities,name',
            'command'    => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Validation Error'
            ];
            return response()->json($response, 400);
        }

        $priority = $request->input('priority');

        $job = Job::create([
            'submitter_id' => Auth::id(),
            'priority' => $priority,
            'status' => 'Dispatched',
        ]);
        Log::info('Dispatched job ' . $job->id);
        $command = $request->input('command');

        newJob::dispatch($job->id, $command)->delay(now()->addSeconds(5));


        $data = $job->only('id', 'priority', 'created_at');

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Dispatched job ' . $job->id
        ], 201);
    }
        /**
     * @OA\Get(
     * path="/api/jobs/get",
     * summary="Get Job for procesing",
     * description="ENDPOINT so that the Job Processor can get a Priority Accepted Job and execute his command",
     * operationId="getJob",
     * tags={"jobs"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=201,
     *    description="Job Processed",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="Job details"),
     *       @OA\Property(property="message", type="string", example="Processed job {id}")
     *        )
     *     ),
     * @OA\Response(
     *    response=202,
     *    description="No job to process",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="false"),
     *       @OA\Property(property="data", type="string", format="data", example="No Data"),
     *       @OA\Property(property="message", type="string", example="No job to process")
     *        )
     *     ),
     * )
     */

    public function getJob()
    {

        $job = Job::where('status', 'Accepted')->whereNull('processor_id')->orderByRaw('FIELD(priority,"high","low")')->first();
        if ($job) {
            Log::info('Processing job ' . $job->id);
            getJob::dispatch($job->id);

            $job->processor_id = Auth::id();
            $job->save();

            $data = $job->only('id', 'priority', 'updated_at');

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Processed job ' . $job->id
            ], 200);
        } 
        else {
            return response()->json([
                'success' => false,
                'data' => 'No data',
                'message' => 'No job to process'
            ], 202);
        }
    }
        /**
     * @OA\Get(
     * path="/api/jobs/{id}",
     * summary="Get Status of a Job with an specific ID",
     * description="ENDPOINT so that the Client can check the status of a Job",
     * operationId="getJob",
     * tags={"jobs"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="application/json"
     *          )
     *      ),
     * @OA\Parameter(
     *          name="Authorization",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string",
     *              default="Bearer <Token>"
     *          )
     *      ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Job Processed",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="true"),
     *       @OA\Property(property="data", type="string", format="data", example="Job details"),
     *       @OA\Property(property="message", type="string", example="Processed job {id}")
     *        )
     *     ),
     * @OA\Response(
     *    response=202,
     *    description="No job found",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", format="success", example="false"),
     *       @OA\Property(property="data", type="string", format="data", example="No Data"),
     *       @OA\Property(property="message", type="string", example="No job found")
     *        )
     *     ),
     * )
     */
    public function checkJob($job_id)
    {
        try{
            $job = Job::findOrFail($job_id);
            
        }
        catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'data' => "No Data",
                'message' => 'No job found'
            ], 202);
        }
        
        $data = $job;
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Current Status of Job ' . $job->id
            ], 200);
        
    }
}
