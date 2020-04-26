<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10
 * Time: 18:37
 */

namespace QingXi\Tools\Utils\Reflections;


use QingXi\Tools\Utils\Arrays\ArrayUtils;
use QingXi\Tools\Utils\Strings\StringUtils;
use QingXi\Tools\Utils\Validates\ValidateUtils;

/**
 * 反射工具类
 * Class ReflectionUtils
 * @package utils
 */
class ReflectionUtils
{

    /**
     * @#name const的变量
     * @var string
     */
    const DEF_KEY = 'key';


    /**
     * @#name const的变量值
     * @var string
     */
    const DEF_VALUE = 'val';

    /**
     * @#title const的注释名
     * @var string
     */
    const DOCUMENT_NAME = 'title';

//=========== 数组转 对象时，是否必传值 =========
    /**
     * @#name 必填字段
     * @var string
     */
    const DEF_REQUIRE = 'require';


    /**
     * @#name 不必填字段
     * @var string
     */
    const DEF_NOT_REQUIRE = 'notRequire';

//------^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^


//=========== 数组转 对象时，是否必传值 =========
    /**
     * @#name 允许转成JSON
     * @var string
     */
    const DEF_JSON_SHOW = 'toJson';


    /**
     * @#name 不允许转JSON
     * @var string
     */
    const DEF_JSON_NOT_SHOW = 'notJson';

//------^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

//=========== 数组转 对象时，是否必传值 =========

    /**
     * @#name 必需要验证的符号
     * @var string
     */
    const DEF_MUST_VALIDATE_SYMBOL = '\\$\\^';


    /**
     * @#name 存在则验证的符号，empty为false
     * @var string
     */
    const DEF_EXISTS_VALIDATE_SYMBOL = '\\-\\^';


//------^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^


    /**
     * @#name 实例化对象
     * @var array
     */
    static private $reflectionInstance = [
        'ReflectionClass' => [],
    ];

    /**
     * @#name 对应类的常量数组
     * @var array
     */
    static private $classConstantList = [];

    /**
     * @#name 获取类的所有常量
     * @param string $className
     * @return array
     * @throws \ReflectionException
     */
    static public function getClassConstants(string $className)
    {
        return self::getReflectionClassInstance($className)->getConstants();
    }


    /**
     * @#name 获取类的常量
     * @param string $className
     * @param string $key
     * @return mixed
     * @throws \ReflectionException
     */
    static public function getClassConstant(string $className, string $key)
    {
        return self::getReflectionClassInstance($className)->getConstant($key);
    }


    /**
     * @#name 获取类的常量注释
     * @param string $className
     * @param string $text
     * @param null $func
     * @return bool|mixed|string
     * @throws \ReflectionException
     */
    static public function getClassConstantDocument(string $className, string $text, $func = null)
    {
        $reflectionConstantDocument = self::getReflectionClassInstance($className)->getReflectionConstant($text);
        if (empty($reflectionConstantDocument)) {
            throw new \RuntimeException("未获取到常量属性");
        }
        $doc = $reflectionConstantDocument->getDocComment();
        if (is_null($func)) {
            return $doc;
        } elseif (is_string($func) || $func instanceof \Closure) {
            return call_user_func_array($func, [$doc]);
        } else {
            throw new \RuntimeException("方法:[$func]异常");
        }
    }


    /**
     * @#name 获取备注
     * @param string $className
     * @return bool|string
     * @throws \ReflectionException
     */
    static public function getClassDocument(string $className)
    {
        return self::getReflectionClassInstance($className)->getDocComment();
    }


    /**
     * @param string $className
     * @return \ReflectionProperty[]
     * @throws \ReflectionException
     */
    static public function getClassProperties(string $className)
    {
        return static::getReflectionClassInstance($className)->getProperties();

    }


    /**
     * @#name 获取反射类实例
     * @param string $className
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    static private function getReflectionClassInstance(string $className)
    {
        if (!isset(self::$reflectionInstance['ReflectionClass'][$className]) || !self::$reflectionInstance['ReflectionClass'][$className] instanceof \ReflectionClass) {
            self::$reflectionInstance['ReflectionClass'][$className] = new \ReflectionClass($className);
        }
        return self::$reflectionInstance['ReflectionClass'][$className];
    }


    /**
     * @#name 获取反射类实例
     * @param $obj
     * @param bool $new
     * @return \ReflectionObject
     */
    static public function getReflectionObjectInstance($obj, bool $new = true)
    {
        if (!is_object($obj)) {
            user_error('obj must be a object!');
        }
        if (!$new) {
            if (!isset(self::$reflectionInstance['ReflectionObj'][get_class($obj)])) {
                self::$reflectionInstance['ReflectionClass'][get_class($obj)] = new \ReflectionObject($obj);
            }
            return self::$reflectionInstance['ReflectionClass'][get_class($obj)];
        } else {
            return new \ReflectionObject($obj);
        }

    }

    /**
     * @#name 获取类的常量数组
     * @param string $className
     * @return array
     * @throws \ReflectionException
     */
    static public function getClassConstantList(string $className)
    {
        if (!isset(self::$classConstantList[$className])) {
            $data = self::getClassConstants($className);
            if (empty($data)) {
                return [];
            }

            $list = [];
            foreach ($data as $key => $val) {
                $da = self::getClassConstantDocument($className, $key, function ($text) use ($key, $val) {
                    if (preg_match_all('/@#?([^\s\n]*)\s+([^\r\n]+)/', $text, $arr)) {
                        $d = [self::DEF_KEY => $key, self::DEF_VALUE => $val];
                        for ($i = 0; $i < count($arr[0]); ++$i) {
                            $keyName = $arr[1][$i];
                            if (isset($d[$keyName])) {
                                user_error($keyName . " 重复定义!");
                            }
                            $aValue = $arr[2][$i];
                            $d[$keyName] = $aValue;
                        }
                        return $d;
                    }
                    return [];
                });

                if (!empty($da)) {
                    $list[] = $da;
                }
            }
            self::$classConstantList[$className] = $list;
        }
        return self::$classConstantList[$className];
    }


    /**
     * @#name 验证const的变量值是否存在
     * @param string $className
     * @param string $val
     * @return bool
     * @throws \ReflectionException
     */
    static public function hasConstsVal(string $className, string $val)
    {
        $list = self::getClassConstantList($className);
        if (empty($list)) {
            return false;
        } else {
            $valList = array_column($list, self::DEF_VALUE);
            return in_array($val, $valList);
        }
    }


    /**
     * @#name 通过const的val获取变量值名
     * @param string $className
     * @param string $val
     * @param string $valueName
     * @return null
     * @throws \ReflectionException
     */
    static public function getInfoByConstsVal(string $className, string $val, string $valueName = self::DEF_VALUE)
    {
        return static::getInfoByConstsKeyVal($className, $valueName, $val, null);
    }

    /**
     * @#name 通过const的key 的 val 获取数据
     * @param string $className
     * @param string $key
     * @param string $val
     * @param string|null $valKey
     * @return null
     * @throws \ReflectionException
     */
    static public function getInfoByConstsKeyVal(string $className, string $key, $val, ?string $valKey = null)
    {
        $list = self::getClassConstantList($className);
        if (empty($list)) {
            return null;
        } else {
            foreach ($list as $l) {
                if ($l[$key] == $val) {
                    if (is_null($valKey)) {
                        return $l;
                    } else {
                        return isset($l[$valKey]) ? $l[$valKey] : null;
                    }

                }
            }
            return null;
        }
    }

    /**
     * @#name 获取类属性注释
     * @param string|object $classNameOrObj
     * @param string $text
     * @return mixed
     * @throws \ReflectionException
     */
    static public function getReflectionPropertyDocument($classNameOrObj, string $text, $func = null)
    {
        if (is_string($classNameOrObj)) {
            $reflection = self::getReflectionClassInstance($classNameOrObj);
        } elseif (is_object($classNameOrObj)) {
            $reflection = self::getReflectionObjectInstance($classNameOrObj);
        } else {
            throw new \RuntimeException("classNameOrObj must be a class name or object");
        }
        $reflectionProperty = $reflection->getProperty($text);
        if (empty($reflectionProperty)) {
            throw new \RuntimeException("未获取到属性");
        }
        $doc = $reflectionProperty->getDocComment();
        if (is_null($func)) {
            return $doc;
        } elseif (is_string($func) || $func instanceof \Closure) {
            return call_user_func_array($func, [$doc]);
        } else {
            throw new \RuntimeException("方法:[$func]异常");
        }
    }


    /**
     * 数组映射到对象
     * @param array $data
     * @param string $className
     * @param bool $must
     * @return object
     * @throws \ReflectionException
     */
    static public function arrayToObj(array $data, string $className = '', bool $must = false)
    {
        return self::beanCopy($data, $className, $must);
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
        /**
         * @var \Reflection
         */
        if (is_object($target)) {
            $targetReflectionObject = self::getReflectionObjectInstance($target);
            $targetObj = $target;
        } elseif (is_string($target)) {
            $targetReflectionObject = self::getReflectionClassInstance($target);
            $targetObj = $targetReflectionObject->newInstance();
        } else {
            user_error('target must be a class string or Object!');
        }

        if (is_object($source)) {
            $sourceArr = self::beanToArray($source);
        } elseif (is_array($source)) {
            $sourceArr = $source;
        } else {
            user_error('source must be a array or object|');
        }
        //格式化设定的key
        $excludeKeyFlag = !empty($exclude);

        if ($excludeKeyFlag) {
            $excludeKeys = [];
            foreach ($exclude as $i => $key) {
                $excludeKeys[$i] = StringUtils::toCamelCase($key);
            }
        }
        $onlyKeyFlag = !empty($only);
        if ($onlyKeyFlag) {
            $onlyKeys = [];
            foreach ($only as $i => $key) {
                $onlyKeys[$i] = StringUtils::toCamelCase($key);
            }
        }

        //遍历数据
        foreach ($sourceArr as $key => $val) {
            //验证目标类是否有些写入方法
            $property = StringUtils::toCamelCase($key);
            $setMethod = 'set' . StringUtils::toBigCamelCase($key);
            if (method_exists($targetObj, $setMethod)) {
                //验证是否黑名单
                if ($excludeKeyFlag) {
                    if (in_array($property, $excludeKeys)) {
                        continue;
                    }
                }
                //验证是否是白名单
                if ($onlyKeyFlag) {
                    if (!in_array($property, $onlyKeys)) {
                        continue;
                    }
                }

                /**
                 * @var \ReflectionParameter[] $reflectionParameters
                 */
                $reflectionParameters = $targetReflectionObject->getMethod($setMethod)->getParameters() ?: [];
                if (count($reflectionParameters) < 1) {
                    continue;
                }


                //
                if (!is_null($val)) {

                    //默认只传入一个值
                    $parameter = $reflectionParameters[0];
                    $value = $val;
                    //验证填充内容是否为对象
                    if ($parameter->getClass()) {
                        //内容为数组
                        if (is_array($val)) {
                            $value = self::beanCopy($val, $parameter->getClass()->getName());
                        }
//                    } elseif($parameter->getType()->getName() === 'array'){
////                        //验证是否对象数组 TODO 后期优化
//                    } else {
//                        $value = $sourceArr[$property];
                    }

                    if (!is_null($value)) {
                        //类型转换
                        switch ($parameter->getType()) {
                            case 'string':
                                if (is_string($value) || is_int($value) || is_bool($value)) {
                                    $setValue = strval($value);
                                } elseif (is_array($value)) {
                                    if (empty($value)) {
                                        $setValue = '';
                                    } elseif (ArrayUtils::isAssocArray($value)) {
                                        $setValue = implode(",", $value);
                                    } else {
                                        $setValue = json_encode($value);
                                    }
                                }
                                break;

                            case 'float':
                                $setValue = is_numeric($value) ? $value : floatval($value);
                                break;

                            case 'int':
                                $setValue = is_numeric($value) ? $value : intval($value);
                                break;

                            case 'array':
                                $setValue = is_array($value) ? $value : [$value];
                                break;

                            default:
                                $setValue = $value;
                                break;
                        }
                        //== 'string')
                        call_user_func([$targetObj, $setMethod], $setValue);
                    }
                }
            }
        }
        return $targetObj;
    }


    /**
     * @#title 通过get方法取出数据
     * @param $obj
     * @param bool $forceReflectFlag 是否强制使用类属性
     * @return array
     */
    static public function beanToArray($obj, bool $forceReflectFlag = false)
    {
        if (!is_object($obj)) {
            user_error('beanToArray must be a object!');
        }

        //检测是否有toArray方法
        if (!$forceReflectFlag && method_exists($obj, 'toArray')) {
            return call_user_func([$obj, 'toArray']);
        }

        $reflectObj = self::getReflectionObjectInstance($obj);

        $array = [];

        //实例
        $methods = $reflectObj->getMethods(\ReflectionProperty::IS_PUBLIC);

        foreach ($methods as $method) {
            if (preg_match(' /^get(\w*)/ u', $method->getName(), $arr)) {
                $array[lcfirst($arr[1])] = $method->invoke($obj);
//                $array[ucfirst($arr[1])] = call_user_func([$obj, $arr[0]]);
            }
        }
        return $array;

    }

    /**
     * 数组list映射到对象list
     * @param array $data
     * @param string $className
     * @param bool $must
     * @return array
     * @throws \ReflectionException
     */
    static public function arrayToListObj(array $data, string $className = '', bool $must = false)
    {
        $list = [];
        if (!ArrayUtils::isAssocArray($data)) {
            throw new \RuntimeException("arrayToListObj 只支持 list");
        }
        foreach ($data as $d) {
            $list[] = self::beanCopy($d, $className, $must);
        }
        return $list;
    }


    /**
     * @#title 数组[list]映射到对象[list]
     * @param array $data
     * @param string $className
     * @param bool $must
     * @return array|object
     * @throws \ReflectionException
     */
    static public function arrayToListOrObj(array $data, string $className = '', bool $must = false)
    {
        if (ArrayUtils::isAssocArray($data)) {
            $list = [];
            foreach ($data as $d) {
                $list[] = self::arrayToObj($d, $className, $must);
            }
            return $list;
        } else {
            return self::arrayToObj($data, $className, $must);
        }

    }


    /**
     * @#title 对象检验
     * @param $obj
     * @return array|bool
     * @throws \ReflectionException
     */
    static public function validate($obj)
    {
        if (!is_object($obj)) {
            throw new \RuntimeException("validate@obj must be a object");
        }

        $reflectionObject = self::getReflectionObjectInstance($obj);
        //实例
        $methods = $reflectionObject->getMethods(\ReflectionProperty::IS_PUBLIC);

        $errMsg = [];

        foreach ($methods as $method) {
//            //验证是否为内容字段
            if (preg_match(' /^get([A - Z_][0 - 9a - zA - Z_]*)$/', $method->getName(), $arr)) {
                //
                $property = lcfirst($arr[1]);
                $propertyDocument = self::getReflectionPropertyDocument($obj, $property);
                $propertyValidateFilters = self::_getDocValidateFilter($propertyDocument);
                if (empty($propertyValidateFilters)) {
                    continue;
                }
                //
                $propertyValue = $method->invoke($obj);

                if (isset($propertyValidateFilters['must']) && !ValidateUtils::eachValid($propertyValidateFilters['must'], $propertyValue)) {
                    $errMsg[] = ['property' => $property, 'validate' => 'must'];
                }
                //
                if (!empty($propertyValue) && isset($propertyValidateFilters['exists']) && !ValidateUtils::eachValid($propertyValidateFilters['exists'], $propertyValue)) {
                    $errMsg[] = ['property' => $property, 'validate' => 'exists'];
                }
                continue;
            }
        }
        if (empty($errMsg)) {
            return true;
        } else {
            return $errMsg;
        }

    }


    /**
     * 验证文档是否需要强制不能为空,bool：有标识，null:未标识
     * @param string $text
     * @return bool|null
     */
    static private function docMustRequire(string $text)
    {
        if (preg_match_all(' / @' . self::DEF_REQUIRE . '\s + [^\r\n]+/', $text)) {
            return true;
        } else if (preg_match_all(' / @' . self::DEF_NOT_REQUIRE . '\s + [^\r\n]+/', $text)) {
            return false;
        } else {
            return null;
        }
    }


    /**
     * @#title 验证文档是否需要强制不能为空,bool：有标识，null:未标识
     * @param string $text
     * @return array
     */
    static private function _getDocValidateFilter(string $text)
    {
        $arr = [];
        if (preg_match_all(' / @' . self::DEF_MUST_VALIDATE_SYMBOL . '([\w\|\& | \( | \) | \#|\$]*)/ims', $text, $musts)) {
            $arr['must'] = $musts[1];
        }

        if (preg_match_all('/@' . self::DEF_EXISTS_VALIDATE_SYMBOL . '([\w\|\&|\(|\)|\#|\$]*)/ims', $text, $exists)) {
            $arr['exists'] = $exists[1];
        }
        return $arr;
    }

}

