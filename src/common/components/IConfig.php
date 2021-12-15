<?php
/**
 * @file    Config.php
 * @date    26/8/2015
 * @time    3:04 AM
 * @author  Henry <alvin_vna@yahoo.com>
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license
 */

namespace common\components;
/**
 * @author Henry <alvin_vna@yahoo.com>
 */
interface IConfig
{
    /**
     * Get configuration variable
     *
     * @param $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null);
    /**
     * Returns all parameters
     *
     * @return array
     */
    public function getAll();
    /**
     * Sets configuration variable
     *
     * @param $name
     * @param mixed $value
     * @return mixed
     */
    public function set($name, $value = null);
    /**
     * Delete parameter
     *
     * @param $name
     * @return mixed
     */
    public function delete($name);
    /**
     * Deletes everything
     *
     * @return mixed
     */
    public function deleteAll();
}