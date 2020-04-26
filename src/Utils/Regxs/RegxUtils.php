<?php
/**
 * Created by PhpStorm.
 * User: liangkang
 * Date: 2019-02-08
 * Time: 21:11
 */

namespace QingXi\Tools\Utils\Regxs;

/**
 * @#title 正则工具
 * Class RegxUtils
 * @package utils
 */
class RegxUtils
{

    /**
     * @#title 数组匹配
     * @param string[] $regxArr
     * @param string $source
     * @return bool
     */
    static public function matchArray(array $regxArr, string $source){
        foreach($regxArr as $regx){
            if(preg_match($regx, $source)){
                return true;
            }
        }
        return false;
    }

}
