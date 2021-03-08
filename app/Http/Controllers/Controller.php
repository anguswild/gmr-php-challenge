<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
        /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Giant Monkey Robot Programming Challenge",
     *      description="PHP APIREST for the Giant Monkey Robot PHP Challenge",
     *      @OA\Contact(
     *          email="patricio.quezada05@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     *      * @OA\OpenApi(
     *   security={
     *     {
     *       "oauth2": {"read:oauth2"},
     *     }
     *   }
     * )
     *
     *
     * @OA\Tag(
     *     name="auth",
     *     description="API Endpoints for authentication"
     * )
     * @OA\Tag(
     *     name="jobs",
     *     description="API Endpoints for jobs management"
     * )
     * 
     * 
     * */

}
