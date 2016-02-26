<?php
namespace QPay\Fraud\Plugins;
require __DIR__ . '/../../vendor/autoload.php'; // todo: service provider

use ErrorException;
use Exception;
use Jacobemerick\KMeans\KMeans;

/**
 * Class ClusteringPlugin
 *
 * An abstract class defining a plugin which uses the magic of K-Means clustering.
 *
 * @package QPay\Fraud\Plugins
 */
abstract class ClusteringPlugin {

    protected function _getClusters($data) {
        $kmeans = new KMeans($data);
        try {
            $kmeans->cluster(2); // cluster into two sets
            return $kmeans->getClusteredData();
        } catch (ErrorException $e) {
            throw new Exception("Error occurred during KMeans clustering: " . $e->getMessage() . "\n");
        }
    }

    /**
     * Returns true if item is in a small outlying cluster (and therefore suspicious)
     *
     * @param $item
     * @param $data
     * @param int $scale Magic number used to tweak the algo
     * @return bool
     */
    protected function _isOutlier($item, $data, $scale = 8) {
        $clusters = $this->_getClusters($data);
        if(!count($clusters)) {
            return false;
        }
        $item_cluster_no = -1;
        $point_count = 0;
        foreach($clusters as $cluster_no => $points) {
            foreach($points as $point) {
                $point_count++;
                if(!array_diff($point, $item)) {
                    $item_cluster_no = $cluster_no;
                }
            }
        }

        $item_cluster_size = count($clusters[$item_cluster_no]);
        $min_cluster_size = max($point_count / $scale, 1);
        return $item_cluster_size < $min_cluster_size;
    }
}