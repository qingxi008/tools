<?php
/**
 * Created by PhpStorm.
 * User: liangkang
 * Date: 2019-02-14
 * Time: 15:00
 */

namespace QingXi\Tools\Utils\Validates;


class ValidateUtils
{
    const EMAIL = 'email';


    const MOBILE = 'mobile';

    const TEL = 'TEL';

    const NAME = 'name';

    const ID_CARD = 'id_card';

    const BANK_CARD = 'bank_card';

    const QQ = 'qq';

    const WEIXIN = 'weixin';



    static protected $regx = [
        self::EMAIL => [
            'regx' => '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims',

        ],

        self::MOBILE => [
                'regx' => '/^1[3456789]\d{9}$/ims',
        ],

        self::TEL => [
            'regx' => '/^(0\d{2,3})?(\d{7,8})$/ims',
        ],

        self::NAME => [
            'regx' => '/^[\x{4e00}-\x{9fa5}]{2,10}$|^[a-zA-Z\s]*[a-zA-Z\s]{2,20}$/isu',
        ],

        self::ID_CARD => [
            'regx' => '/^\d{15}$)|(^\d{17}([0-9]|X)$/isu',
        ],

        self::BANK_CARD => [
            'regx' => '/^(\d{15}|\d{16}|\d{19})$/isu',
        ],

        self::QQ => [
            'regx' => '/^\d{5,12}$/isu',
        ],

        self::WEIXIN =>[
            'regx' => '/^[_a-zA-Z0-9]{5,19}+$/isu',
        ],

    ];




    static public function email(string $value){
        return static::valid(static::EMAIL, $value);
    }

    static public function mobile(string $value){
        return static::valid(static::MOBILE, $value);
    }

    static public function tel(string $value){
        return static::valid(static::TEL, $value);
    }

    static public function idCard(string $value){
        return static::valid(static::ID_CARD, $value);
    }

    static public function bankCard(string $value){
        return static::valid(static::BANK_CARD, $value);
    }



    /**
     * @name *验证
     * @param string $type
     * @param string $value
     * @return bool
     */
    static public function valid(string $type, string $value){
        if(!isset(static::$regx[$type])){
            user_error('type not allow');
        }
        if(is_null($value)){
            user_error('value must not null');
        }
        if(preg_match(static::$regx[$type]['regx'], $value)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * @
     * @param string[] $eachRegxs
     * @param string $value
     * @return bool
     */
    static public function eachValid($eachRegxs, string $value){
        if(empty($eachRegxs)){
            user_error('eachRegxs must not be blank');
        }
        foreach($eachRegxs as $validType){
            if(!self::valid($validType, $value)){
                return false;
            }
        }
        return true;
    }

}