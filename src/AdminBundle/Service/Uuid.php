<?php

namespace AdminBundle\Service;


/**
 * Class Uuid
 * @package AdminBundle\Service
 */
class Uuid
{
    /**
     * @param string $prefix
     * @param int $length
     * @return bool|string
     */
    public static function generate($prefix = '', $length = 8)
    {
        return $prefix . substr(md5(uniqid(mt_rand(), true)), 0, $length);
    }
}