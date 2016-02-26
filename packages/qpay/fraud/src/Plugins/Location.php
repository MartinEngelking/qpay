<?php
namespace QPay\Fraud\Plugins;

use Illuminate\Support\Facades\DB;
use QPay\Fraud\FraudPluginException;

/**
 * Class Location
 *
 * I spent the least time here, having simply run out... This and its sister class, Amount, could use some work.

 * @package QPay\Fraud\Plugins
 */
class Location extends ClusteringPlugin {

    public function check($transaction) {
        list($item, $locations) = $this->_getLocations($transaction);
        if($this->_isOutlier($item, $locations)) {
            throw new FraudPluginException("Unusual location");
        }
    }

    /**
     * Returns an array with two items:
     * 1: the current transaction's geolocation (lat/long)
     * 2: an array of all geolocations (lat/long)
     * @param $transaction
     * @return array
     */
    private function _getLocations($transaction)
    {
        $locations = DB::table('transactions')
            ->join('geolocations', 'transactions.zip', '=', 'geolocations.zip')
            ->select('geolocations.lat', 'geolocations.long')
            ->get();

        // We don't want stdobj or associative arrays
        foreach ($locations as &$location) {
            $new_location = [];
            foreach($location as $key => $value) {
                $new_location[] = floatval($value);
            }
            $location = $new_location;
            // todo; nicer implementation
        }

        $item = [
           floatval($transaction->geolocation->lat),
           floatval($transaction->geolocation->long)
        ];
        $locations[] = $item;

        return [$item, $locations];
    }

}
