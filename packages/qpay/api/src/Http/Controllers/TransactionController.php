<?php
namespace QPay\API\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{
    /**
     * Display a list of transactions
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 200;
        $response = [
            'status' => 'ok'
        ];
        return Response::json($response, $status);
    }

    /**
     * Store a new transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = 200;
        $response = [
            'status' => 'ok'
        ];
        return Response::json($response, $status);
    }
}
