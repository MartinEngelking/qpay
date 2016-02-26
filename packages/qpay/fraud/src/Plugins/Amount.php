<?php
namespace QPay\Fraud\Plugins;

use Illuminate\Support\Facades\DB;
use QPay\Fraud\FraudPluginException;

/**
 * Class Amount
 *
 * I spent the least time here, having simply run out... This and its sister class, Location, could use some work.
 *
 *
 * @package QPay\Fraud\Plugins
 */
class Amount extends ClusteringPlugin {

    public function check($transaction) {
        list($item, $amounts) = $this->_getAmounts($transaction);
        if($this->_isOutlier($item, $amounts)) {
            throw new FraudPluginException("Unusual purchase amount");
        }
    }

    /**
     * Returns an array with two items:
     * 1: the current transaction's amount
     * 2: an array of all amounts
     * @param $transaction
     * @return array
     */
    private function _getAmounts($transaction)
    {
        $amounts = DB::table('transactions')
            ->select('amount')
            ->get();

        // We don't want stdobj or associative arrays
        foreach ($amounts as &$row) {
            $row = [floatval($row->amount)];
        }

        $item = [
           floatval($transaction->amount),
        ];
        $amounts[] = $item;

        return [$item, $amounts];
    }

}
