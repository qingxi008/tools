<?php
/**
 * Created by PhpStorm.
 * User: liangkang
 * Date: 2019-02-10
 * Time: 22:08
 */

namespace QingXi\Tools\Utils\Strings;


class StringUtils
{

    /**
     * @#title 驼峰命名转下划线命名
     * @param string|null $str
     * @return string
     */
    static public function toUnderScore(?string $str)
    {
        return strtolower(preg_replace('/([A-Z])([A-Z])/', "$1" . '_' . "$2", preg_replace('/([a-z])([A-Z])/', "$1" . '_' . "$2", $str)));
    }

    /**
     * @#title 下划线命名到小驼峰命名
     * @param string|null $str
     * @return string
     */
    static public function toCamelCase(?string $str)
    {
        $array = explode('_', $str);
        $result = $array[0];
        $len = count($array);
        if ($len > 1) {
            for ($i = 1; $i < $len; $i++) {
                $result .= ucfirst($array[$i]);
            }
        }
        return $result;
    }


    /**
     * @#title 下划线命名到大驼峰命名
     * @param string|null $str
     * @return string
     */
    static public function toBigCamelCase(?string $str)
    {
        return ucfirst(static::toCamelCase($str));
    }


    /**
     * @#title 验证是否空字符串(全空格也为空字符串)
     * @param string|null $str
     * @return bool
     */
    static public function isBlank(?string $str)
    {
        return is_null($str) || trim($str) === '';
    }

    /**
     * @#title 验证是否不为空字符串(全空格也为空字符串)
     * @param string|null $str
     * @return bool
     */
    static public function isNotBlank(?string $str)
    {
        return !static::isBlank($str);
    }
}
