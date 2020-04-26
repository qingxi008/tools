<?php
/**
 * Created by PhpStorm.
 * User: QingXi
 * Date: 2019/1/24
 * Time: 22:21
 */

namespace QingXi\Tools\Utils\Dates;

/**
 * Class DateUtils
 * @#name 日期工具类
 * @package utils
 */
class DateUtils
{
    /**
     * @#name 默认格式
     * @var string
     */
    const DEFAULT_FORMAT = 'Y-m-d H:i:s';


    /**
     * 获取当前时间
     * @param string $format
     * @return false|string
     */
    public static function now($format = self::DEFAULT_FORMAT)
    {
        return date($format);
    }


    /**
     * 格式化时间辍与日期
     * @param int $time
     * @param string $format
     * @return false|string
     */
    public static function timeToDate(int $time, $format = self::DEFAULT_FORMAT)
    {
        return date($format, $time);
    }


    /**
     * @#title
     * @param $date
     * @return bool
     */
    public static function isValid($date)
    {
        //
        if (static::toTime($date) > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @#title
     * @param $date
     * @return false|int|null
     */
    public static function toTime($date)
    {
        $strTime = strtotime($date);
        //
        if ($strTime > 0) {
            return $strTime;
        } elseif (is_numeric($date)) {
            return intval($date);
        } else {
            return null;
        }
    }

    /**
     * @param string $date
     * @param string $format
     * @return false|string|null
     */
    public static function formatDateStr(string $date, string $format = self::DEFAULT_FORMAT)
    {
        return static::isValid($date) ? static::timeToDate(static::toTime($date), $format) : null;

    }


}
