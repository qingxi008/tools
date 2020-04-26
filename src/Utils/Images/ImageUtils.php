<?php
/**
 * Created by PhpStorm.
 * User: QingXi
 * Date: 2019/3/16
 * Time: 22:35
 */

namespace QingXi\Tools\Utils\Images;


class ImageUtils
{

    /**
     * @#title 将Base64图片转换为本地图片并保存
     * @param $base64ImageContent [要保存的Base64]
     * @param $path 目录 [要保存的路径]
     * @return bool|string
     */
    static public function base64ToDisk($base64ImageContent, $path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64ImageContent, $result)) {
            $type = $result[2];
            $filePath =  date('Ym') . "/";
            $fileString = base64_decode(str_replace($result[1], '', $base64ImageContent));

            if (!file_exists($path."/".$filePath)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($path."/".$filePath, 0700);
            }
            $filePath = $filePath.md5($fileString).'-'.sha1($fileString).".".$type;
            if(file_exists($path."/".$filePath)){
                return $filePath;
            }
            if (file_put_contents($path."/".$filePath, $fileString)) {
                return $filePath;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
