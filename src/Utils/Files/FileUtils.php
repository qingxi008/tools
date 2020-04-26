<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12
 * Time: 10:16
 */

namespace QingXi\Tools\Utils\Files;


class FileUtils
{

    /**
     * @#title 获取目录下面所有的文件
     * @param string $path
     * @return array
     */
    static public function scanDirFiles(string $path)
    {
        $files = [];
        $rootPath = '';
        //最终返回
        $rel = [];
        if (is_dir($path)) {
            $files = scandir($path);
            $rootPath = $path;
        }
        //
        foreach ($files as $file) {
            if (is_dir($rootPath . DS . $file) || $file === '.' || $file === '..') {
                continue;
            }
            $rel[] = $rootPath . DS . $file;
        }
        return $rel;
    }

}
