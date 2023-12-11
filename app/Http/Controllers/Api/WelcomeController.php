<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      x={
 *          "logo": {
 *              "url": "https://via.placeholder.com/190x90.png?text=1vault"
 *          }
 *      },
 *      title="1vault Api",
 *      description="1vault api documentation",
 *
 *      @OA\Contact(
 *          email="dev@1vault.africa"
 *      ),
 *
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     ),
 *
 *     @OA\PathItem(
 *       path="/"
 *     )
 * )
 */
class WelcomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pet/{petId}",
     *     summary="Find pet by ID",
     *     description="Returns a single pet",
     *     operationId="getPetById",
     *     tags={"pet"},
     *
     *     @OA\Parameter(
     *         description="ID of pet to return",
     *         in="path",
     *         name="petId",
     *         required=true,
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Pet not found"
     *     ),
     * )
     */
    public function __invoke()
    {
        return 'Api services for 1vault africa';
    }
}
