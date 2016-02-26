<?php
namespace QPay\API\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use QPay\Core\Geolocation;
use QPay\Core\Transaction;
use QPay\Fraud\FraudCheckException;

class TransactionController extends Controller
{
    /**
     * Display a list of transactions
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $transactions = $this->_getTransactions();
            return $this->_transactionListResponse($transactions);
        } catch (\Exception $e) {
            return $this->_errorResponse($e->getMessage());
        }
    }

    /**
     * Store a new transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|regex:/^\d+(\.\d+)?$/',
            'merchant' => 'required',
            'address' => 'required',
            'zip' => 'required|regex:/^\d{5}$/'
        ]);
        if ($validator->fails()) {
            $invalid_fields = $validator->errors()->keys();
            $response = [
                'status' => 'failed_validation',
                'errors' => $invalid_fields
            ];
            return Response::json($response, 400);
        }

        $location = Geolocation::where('zip', '=', $request->input('zip'))->first();
        // todo DRY up the multiple responses
        if(!$location) {
            $response = [
                'status' => 'failed_validation',
                'errors' => ['zip']
            ];
            return Response::json($response, 400);
        }

        $attributes = $request->all();
        $attributes['timestamp'] = date("Y-m-d H:i:s");
        $transaction = null;
        try {
            $transaction = Transaction::create($attributes);
        } catch (FraudCheckException $e) {
            $response = [
                "status" => "failed_fraud",
                "errors" => $e->getErrors()
            ];
            return Response::json($response, 400);
        } catch (Exception $e) {
            return $this->_errorResponse($e->getMessage());
        }

        // todo: simulate payment submission here

        $status = 200;
        $response = [
            'transaction' => $this->_responsifyTransaction($transaction),
            'status' => 'ok'
        ];
        return Response::json($response, $status);
    }

    /**
     * Returns a list of all transactions
     *
     * @return mixed
     */
    private function _getTransactions()
    {
        return Transaction::orderBy('id', 'asc')->get()->all();
    }

    /**
     * Returns a response with a list of transactions
     *
     * @param $transactions
     * @return Response
     */
    private function _transactionListResponse($transactions)
    {

        $transactions = $this->_responsifyTransactions($transactions);

        $response = [
            'transactions' => $transactions,
            'status' => 'ok'
        ];

        return Response::json($response, 200);

    }

    /**
     * Returns an error response
     *
     * @param $message
     * @return mixed
     */
    private function _errorResponse($message)
    {
        $response = [
            'status' => 'error',
            'error' => $message
        ];

        return Response::json($response, 500);

    }

    /**
     * Turns an array of Transaction objects into something suitable for our API response.
     *
     * @param $transactions array
     * @return array
     */
    private function _responsifyTransactions($transactions)
    {
        return array_map(function($transaction) {
            return $this->_responsifyTransaction($transaction);
        }, $transactions);
    }

    /**
     * Returns an associative array describing a transaction.
     * Used to compose the output of our API.
     *
     * @param $transaction Transaction
     * @return array
     */
    protected function _responsifyTransaction($transaction)
    {
        return [
            'id' => $transaction->id,
            'amount' => $transaction->amount,
            'merchant' => $transaction->merchant,
            'address' => $transaction->address,
            'city' => $transaction->geolocation->city,
            'state' => $transaction->geolocation->state,
            'zip' => $transaction->geolocation->zip,
            'timestamp' => $transaction->timestamp
        ];
    }
}
