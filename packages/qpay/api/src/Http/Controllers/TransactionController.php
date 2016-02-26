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
        $status = 200;
        $response = [
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

        return Response::json($response, 500);

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
     * @param $transaction \QPay\Core\Transaction
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
