<?php
/**
 * Created by PhpStorm.
 * User: liangkang
 * Date: 2019-06-26
 * Time: 18:12
 */

namespace QingXi\Tools\Utils\Binarys;


use QingXi\Tools\Utils\Arrays\ArrayUtils;
use QingXi\Tools\Utils\Reflections\ReflectionUtils;

class BeanUtils
{

    /**
     * @#title 用于查询的单条记录写入实体
     * @param array $data
     * @param string $entityClass
     * @return object|null
     * @throws \ReflectionException
     */
    static public function toObject(array $data, string $entityClass)
    {
        //
        if (ArrayUtils::isAssocArray($data)) {
            return null;
        }
        //
        return ReflectionUtils::beanCopy(ArrayUtils::keyToCamelCase($data), $entityClass);
    }


    /**
     * @#title 将LIST转成Collection<{$class}>
     * @param array $list
     * @param string $entityClass
     * @return Collection
     * @throws \ReflectionException
     */
    static public function toListObject(array $list, string $entityClass)
    {
        //判断传入数据的类型
        if (empty($list) || !ArrayUtils::isAssocArray($list)) {
            return [];
        }
        //
        $objList = [];
        //便利
        foreach ($list as $l) {
            $objList[] = ReflectionUtils::beanCopy(ArrayUtils::keyToCamelCase($l), $entityClass);
        }
        return $objList;
    }


    /**
     * @#title 对象copy, key 转转驼峰
     * @param array|string|object $source
     * @param string|object $target
     * @param array $exclude 排除的KEY
     * @param array $only 只复制的KEY
     * @return object
     * @throws \ReflectionException
     */
    static public function beanCopy($source, $target, array $exclude = [], array $only = [])
    {
        return ReflectionUtils::beanCopy($source, $target, $exclude, $only);
    }

}
