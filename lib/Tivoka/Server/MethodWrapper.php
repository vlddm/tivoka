<?php
/**
 * @package Tivoka
 * @author Marcel Klehr <mklehr@gmx.net>
 * @copyright (c) 2011, Marcel Klehr
 */

namespace Tivoka\Server;
use Tivoka\Exception;

/**
 * MethodWrapper for implementing anonymous objects on the fly
 * @package Tivoka
 */
class MethodWrapper
{
    /**
     * @var array The list of callbacks
     */
    private $methods;

    /**
     * Registers a server method
     *
     * @param string $name The name of the method to provide (already existing methods with the same name will be overridden)
     * @param callback $method The callback
     * @returns bool FALSE if no valid callback has been given
     */
    public function register($name, $method)
    {
        if(!is_callable($method)) return FALSE;

        $this->methods[$name] = $method;
        return TRUE;
    }

    /**
     * Returns TRUE if the method with the given name is registered and a valid callback
     *
     * @param callback $method The name of the method to check
     * @returns bool
     */
    public function exist($method)
    {
        if(!is_array($this->methods))return FALSE;
        if(is_callable($this->methods[$method]))return TRUE;
    }

    /**
     * Invokes the requested method
     */
    public function __call($method,$args)
    {
        if(!$this->exist($method)){
            $args[0]->error(-32601); return;
        }
        $prc = $args[0];
        return call_user_func_array($this->methods[$method],array($prc));
    }
}
?>