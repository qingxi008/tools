<?php
/**
 * Created by PhpStorm.
 * User: liangkang
 * Date: 2019-04-10
 * Time: 18:24
 */

namespace QingXi\Tools\Utils\Binarys;


/**
 * ===========================================
 * 项目: 进制转换
 * 版本: 1.0
 * 团队:
 * 作者:
 * 功能: 10进制转换2、8、16、36、62进制  |  2、8、16、36、62进制转换10进制
 * ===========================================
 * Copyright (c) 2008
 * 团队主页:
 * 团队信箱:
 * 创建日期:
 * 修改日期: 暂无
 * 修改说明: ----
 * 版权声明:
 * ===========================================
 */
class BinaryUtils
{

    /**
     * @#title 2进制
     * @var string
     */
    const BINARY_2 = '2';


    /**
     * @#title 8进制
     * @var string
     */
    const BINARY_8 = '8';


    /**
     * @#title 10进制
     * @var string
     */
    const BINARY_10 = '10';


    /**
     * @#title 16进制(小写)
     * @var string
     */
    const BINARY_16_LOWER = '16-lower';


    /**
     * @#title 16进制(大写)
     * @var string
     */
    const BINARY_16_UPPER = '16-upper';


    /**
     * @#title 36进制(小写)
     * @var string
     */
    const BINARY_36_LOWER = '36-lower';


    /**
     * @#title 36进制(大写)
     * @var string
     */
    const BINARY_36_UPPER = '36-upper';


    /**
     * @#title 62进制
     * @var string
     */
    const BINARY_62 = '62';


    /**
     * @#title 2进制内容
     * @var array
     */
    static protected $binary_2 = ['0', '1'];

    /**
     * @#title 8进制内容
     * @var array
     */
    static protected $chr_8 = ['0', '1', '2', '3', '4', '5', '6', '7'];

    /**
     * @#title 10进制内容
     * @waring 这个的值不能改，否则下面的计算都会报错
     * @var array
     */
    static protected $chr_10 = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    /**
     * @#title 16进制(小写)内容
     * @var array
     */
    static protected $chr_16_lower = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'];


    /**
     * @#title 16进制(大写)内容
     * @var array
     */
    static protected $chr_16_upper = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];


    /**
     * @#title 36进制(小写)内容
     * @var array
     */
    static protected $chr_36_lower = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];


    /**
     * @#title 36进制(大写)内容
     * @var array
     */
    static protected $chr_36_upper = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];


    /**
     * @#title 62进制内容
     * @var array
     */
    static protected $chr_62 = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];


    /**
     * @#title 转十进制
     * @param string $source
     * @param string $sourceBinary
     * @return float|int|string
     */
    static public function binaryToDecimal(string $source, string $sourceBinary)
    {
        if ($sourceBinary === static::BINARY_10) {
            return $source;
        }
        $binaryInt = null;
        switch ($sourceBinary) {
            case static::BINARY_2:
                $chr = static::$binary_2;
                $binaryInt = 2;
                break;

            case static::BINARY_8:
                $chr = static::$chr_8;
                $binaryInt = 8;
                break;

            case static::BINARY_16_LOWER:
                $chr = static::$chr_16_lower;
                $binaryInt = 16;
                break;

            case static::BINARY_16_UPPER:
                $chr = static::$chr_16_upper;
                $binaryInt = 16;
                break;

            case static::BINARY_36_LOWER:
                $chr = static::$chr_36_lower;
                $binaryInt = 36;
                break;

            case static::BINARY_36_UPPER:
                $chr = static::$chr_36_upper;
                $binaryInt = 36;
                break;

            case static::BINARY_62:
                $chr = static::$chr_62;
                $binaryInt = 62;
                break;

            default:
                user_error('$sourceBinary error');
                break;
        }

        $chrFlip = array_flip($chr);
        $sourceChrs = str_split($source);
        $newInt = 0;
        foreach ($sourceChrs as $c) {
            if (isset($chrFlip[$c])) {
                $newInt = $newInt * $binaryInt + intval($chrFlip[$c]);
            } else {
                user_error("$" . "source:[$source]:[$c] error");
            }
        }
        return $newInt;
    }


    /**
     * @#title 十进制转对应进制
     * @param int $decimal 十进制数
     * @param string $targetBinary 目标进制
     * @return float|int|string
     */
    static public function decimalToBinary(int $decimal, string $targetBinary)
    {
        if ($targetBinary === static::BINARY_10) {
            return $decimal;
        }
        if ($decimal == 1 || $decimal == -1 || $decimal == 0) {
            return $decimal;
        }
        $binaryInt = null;
        switch ($targetBinary) {
            case static::BINARY_2:
                $chr = static::$binary_2;
                $binaryInt = 2;
                break;

            case static::BINARY_8:
                $chr = static::$chr_8;
                $binaryInt = 8;
                break;

            case static::BINARY_16_LOWER:
                $chr = static::$chr_16_lower;
                $binaryInt = 16;
                break;

            case static::BINARY_16_UPPER:
                $chr = static::$chr_16_upper;
                $binaryInt = 16;
                break;

            case static::BINARY_36_LOWER:
                $chr = static::$chr_36_lower;
                $binaryInt = 36;
                break;

            case static::BINARY_36_UPPER:
                $chr = static::$chr_36_upper;
                $binaryInt = 36;
                break;

            case static::BINARY_62:
                $chr = static::$chr_62;
                $binaryInt = 62;
                break;

            default:
                user_error('$targetBinary error');
                break;
        }


        $newChrs = [];
        $int = abs($decimal);
        $f = $decimal / $int;
        while (abs($int) > 0) {
            $mod = $int % $binaryInt;
            if (isset($chr[$mod])) {
                array_unshift($newChrs, $chr[$mod]);
            } else {
                user_error("$" . "mod:[$mod] error");
            }
            $int = (int)($int / $binaryInt);
        }
        return ($f == 1 ? "" : "-") . implode("", $newChrs);
    }


    /**
     * @#title 进制转换
     * @param string $source 需要转换的源
     * @param string $sourceBinary 源的进制
     * @param string $targetBinary 目标进制
     * @return float|int|string
     */
    static public function binaryTo(string $source, string $sourceBinary, string $targetBinary)
    {
        if ($sourceBinary === $targetBinary) {
            return $source;
        }
        return static::decimalToBinary(static::binaryToDecimal($source, $sourceBinary), $targetBinary);
    }

}
