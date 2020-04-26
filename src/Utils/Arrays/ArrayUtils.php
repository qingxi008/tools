<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26
 * Time: 18:26
 */

namespace QingXi\Tools\Utils\Arrays;


use QingXi\Tools\Utils\Strings\StringUtils;

/**
 * 数组工具类
 * Class ArrayUtils
 * @package utils
 */
class ArrayUtils
{

    /**
     * @#title 验证是否索引数组(非关联数组)
     * @param array $array
     * @return bool
     */
    static public function isAssocArray(array $array): bool
    {
        $index = 0;
        foreach (array_keys($array) as $key) {
            if ($index++ != $key || !is_numeric($key)) return false;
        }
        return true;
    }


    /**
     * @#title 获取数组中的值
     * @param array $array
     * @param string $name
     * @param null $default
     * @param string $filters
     * @return mixed|null
     */
    static public function getVal(array $array, string $name, $default = null, string $filters = '')
    {
        if (isset($array[$name])) {
            $data = $array[$name];
            if (!empty($filters)) {
                $filterArr = explode(",", $filters);
                foreach ($filterArr as $filter) {
                    $data = call_user_func_array($filter, [$data]);
                }
            }
            return $data;
        } else {
            return $default;
        }
    }


    /**
     * 设定数组中的值
     * @param array $array
     * @param string $name
     * @param null $val
     * @param string $filters
     * @return bool
     */
    static public function setVal(array &$array, string $name, $val = null, string $filters = '')
    {
        $data = $val;
        if (!empty($filters)) {
            $filterArr = explode(",", $filters);
            foreach ($filterArr as $filter) {
                $data = call_user_func_array($filter, [$data]);
            }
        }
        $array[$name] = $data;
        return true;
    }

    //驼峰命名转下划线命名
    static public function keyToUnderScore(array $array)
    {
        $newArr = [];
        foreach ($array as $k => $v) {
            $newArr[StringUtils::toUnderScore($k)] = $v;
        }
        return $newArr;
    }

    //下划线命名到驼峰命名
    static public function keyToCamelCase(array $array)
    {
        $newArr = [];
        foreach ($array as $k => $v) {
            $newArr[StringUtils::toCamelCase($k)] = $v;
        }
        return $newArr;
    }


    /**
     * @#title 数组的KEY映射到新的数组，
     * @param array $data
     * @param array $columnMaps
     * @param bool $onlyMap : true : 只返回$columnMaps中的映射字段, false : 保持多余数据
     * @param bool $delRegxColumn 是否删除已匹配的字段
     * @return array
     */
    static public function arrayToMapArray(array $data, array $columnMaps = [], bool $onlyMap = true, bool $delRegxColumn = true)
    {
        if (is_array($columnMaps) && !empty($columnMaps)) {
            $newData = [];
            $oldData = [];
            foreach ($columnMaps as $tableColumn => $map) {
                if (is_numeric($tableColumn) || $tableColumn === $map) {
                    if (isset($data[$tableColumn])) {
                        $newData[$tableColumn] = $data[$tableColumn];
                    }
                    continue;
                }
                if (isset($data[$tableColumn])) {
                    $newData[$map] = $data[$tableColumn];
                }

                if ($delRegxColumn) {
                    $data[$tableColumn] = null;
                    unset($data[$tableColumn]);
                }
            }
            return $onlyMap ? array_merge($oldData, $newData) : array_merge($data, $oldData, $newData);
        } else {
            return $data;
        }
    }


    /**
     * @#title 将list(二维) 转成map
     * @param $array
     * @param string $key 需要提取的KEY
     * @return array
     */
    static public function listToMap($array, string $key)
    {
        $ret = [];
        foreach ($array as $a) {
            $ret[$a[$key]] = $a;
        }
        return $ret;
    }


    /**
     * @#title
     * @param $data
     * @param array $keys
     * @return array
     */
    static public function newKeysArray($data, $keys = [])
    {
        $newData = [];
        $isAssoc = static::isAssocArray($keys);
        foreach ($keys as $k => $v) {
            $ak = null;
            if ($isAssoc) {
                $ak = $v;
            } else {
                $ak = $k;
            }

            if (isset($data[$ak])) {
                $newData[$v] = $data[$ak];
            }

        }
        return $newData;
    }


    /**
     * @#title
     * @param array $array
     * @param string $key 比对的KEY
     * @param mixed $val 比对的值
     * @param string $getKey 需要提取值的KEY
     * @return null
     */
    static public function getKeyBy(array $array, string $key, $val, string $getKey)
    {
        foreach ($array as $item) {
            if (isset($item[$key]) && $item[$key] == $val) {
                return isset($item[$getKey]) ? $item[$getKey] : null;
            }
        }
        return null;
    }


    /**
     * @#title 根据数组某一个键的值转换成一个新的二维数组
     * @param array $array
     * @param string $key
     * @return array
     */
    static public function newArrayBykey(array $array, string $key)
    {
        $newArray = [];
        foreach ($array as $_array) {
            if (isset($_array[$key])) {
                $newArray[$_array[$key]][] = $_array;
            }
        }
        return $newArray;
    }

    /**
     * @#title 获取数组某一个键的值
     * @param array $array
     * @param string $key
     * @return array
     */
    static public function pluckByKey(array $array, string $key)
    {
        $newArray = [];
        foreach ($array as $_array) {
            if (isset($_array[$key])) {
                $newArray[] = $_array[$key];
            }
        }
        return $newArray;
    }

    /**
     * 计算数组某一个键的值之和
     * @param array $array
     * @param string $key
     * @return float|int
     */
    static public function sumByKey(array $array, string $key)
    {
        return array_sum(
            array_map(function ($val) use ($key) {
                return $val[$key];
            }, $array)
        );
    }


    /**
     * @param array $array
     * @param string $key
     * @return array
     */
    static public function delKey(array $array, string $key)
    {
        if (array_key_exists($key, $array)) {
            unset($array[$key]);
        }
        return $array;
    }

    /**
     * @#title
     * @param array $array
     * @param string[] $keys
     * @return array
     */
    static public function delKeys(array $array, array $keys)
    {
        foreach ($keys as $key) {
            $array = static::delKey($array, $key);
        }
        return $array;
    }
}
