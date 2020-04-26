<?php
/**
 * Created by PhpStorm.
 * User: liangkang
 * Date: 2019-02-10
 * Time: 21:20
 */

namespace QingXi\Tools\Utils\Crypts;


class MD5Utils
{

    /**
     * @param string $string
     * @return string
     */
    static public function encode(string $string){
        return md5($string);
    }


    /**
     * @param string $string
     * @return bool|string
     */
    static public function shortEncode(string $string){
        return substr(md5($string), 8, 16);
    }

}
