<?php
namespace QPay\Fraud;

use Exception;

/**
 * Class Engine
 *
 * The Fraud Engine is responsible for checking transactions against a number of plugins.
 *
 * @package QPay\Fraud
 */
class Engine
{
    const PLUGIN_NAMESPACE = '\\QPay\\Fraud\\Plugins\\';
    protected $plugins = ['Location'];

    public function check($transaction)
    {
        $errors = [];
        foreach ($this->plugins as $plugin_class) {

            $plugin_class = self::PLUGIN_NAMESPACE . $plugin_class;
            $plugin = new $plugin_class();
            try {
                $plugin->check($transaction);

            } catch (FraudPluginException $e) {
                $errors[] = $e->getMessage();
            }
        }
        if (!empty($errors)) {
            throw new FraudCheckException('Potentially fraudulent transaction', $errors);
        }
    }
}

class FraudPluginException extends Exception
{
}

class FraudCheckException extends Exception
{
    protected $errors = [];

    public function __construct($message, $errors)
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}