<?php
namespace QPay\Core;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a single transaction.
 *
 * This exercise assumes a single-user, single-card environment, which helps keep things really simple.
 * 
 * @package QPay\Core
 */
class Transaction extends Model
{
    protected $table = 'transactions';
    public $timestamps = false;

    // Allow mass filling of these attributes (helps us when processing forms)
    protected $fillable = ['amount', 'merchant', 'address', 'city', 'state', 'zip', 'timestamp'];

    // Each transaction has one Geolocation, which gives the city, state, county, lat/long, etc.
    public function geolocation()
    {
        return $this->hasOne('QPay\Core\Geolocation', 'zip', 'zip');
    }
}
