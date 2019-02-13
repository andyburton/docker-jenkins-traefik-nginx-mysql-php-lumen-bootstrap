<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\ExampleModel;

class ExampleServiceController extends AbstractController
{

    /**
     * Validation error codes
     */
    const VALIDATION_CODES = [
        'value' => [
            'required' => 1010,
            'unique' => 1011,
            'min' => 1012,
            'max' => 1013
        ],
        'search' => [
            'max' => 1020
        ],
        'start' => [
            'integer' => 1030,
            'min' => 1031
        ],
        'limit' => [
            'integer' => 1040,
            'min' => 1041,
            'max' => 1042,
        ]
    ];

    /**
     * Validate the request body
     *
     * @param Request $request
     * @param
     * @throws ValidationException
     */
    protected function validateRequest(Request $request, $id = null)
    {
        $this->validate($request, [
            'value' => [
                'required',
                'string',
                'min:3',
                'max:128'
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = ExampleModel::query();

        $this->applySearch($request, $query, 'value');
        $response = $this->applyPagination($request, $query, 100);

        return response()->json($response);

    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function read(Request $request, $id)
    {
        return response()->json(ExampleModel::findOrFail($id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validateRequest($request);
        return response()->json(ExampleModel::create($request->all()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validateRequest($request, $id);

        $filter = ExampleModel::findOrFail($id);
        $filter->update($request->all());

        return response()->json($filter);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        ExampleModel::destroy($id);
        return response(null);
    }

}