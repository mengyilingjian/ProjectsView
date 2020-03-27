<?php
namespace app\controller;

use app\BaseController;

class DirController extends BaseController
{
    /**
     * 获取文件夹数据
     * 类型值为UI框架内置的icon名称:https://www.mdui.org/docs/material_icon
     */
    public function getDirsInfo($dirpath){
        $dirs = scandir($dirpath); 
        $projcets = [];
        foreach($dirs as $key => $dir){
            $path = $dirpath.$dir;
            if(is_dir($path)){
                if($dir != '.' && $dir != '..'){
                    $floderSize = $this->getRealSize($this->getDirSize($path));
                    $projcets[$dir] = [
                        'size' => $floderSize,
                        'type' => 'folder', // ui内置图标名称
                        'dir' => $dir
                    ];
                }
            }else{
                $floderSize = $this->getRealSize($this->getDirSize($dir));
                $projcets[$dir] = [
                    'size' => $floderSize,
                    'type' => 'insert_drive_file', // ui内置图标名称
                    'dir'  => $dir
                ];
            }
        }
        return $projcets;
    }

    // 获取当前文件夹大小
    public function getDirSize($dir)
    {
        $sizeResult = 0;
        if(is_file($dir)){
            $sizeResult += filesize($dir);
        }else{
            $handle = opendir($dir);
            while (false!==($FolderOrFile = readdir($handle)))
            {   
                if($FolderOrFile != "." && $FolderOrFile != "..")
                {
                    if(is_dir("$dir/$FolderOrFile"))
                    {
                        $sizeResult += $this->getDirSize("$dir/$FolderOrFile");
                    }
                    else
                    {
                        $sizeResult += filesize("$dir/$FolderOrFile");
                    }
                }  
            }
            closedir($handle);
        }
        return $sizeResult;
    }

    // 单位自动转换函数
    public function getRealSize($size)
    {
        $kb = 1024;         // Kilobyte
        $mb = 1024 * $kb;   // Megabyte
        $gb = 1024 * $mb;   // Gigabyte
        $tb = 1024 * $gb;   // Terabyte
        
        if($size < $kb)
        {
            return $size." B";
        }
        else if($size < $mb)
        {
            return round($size/$kb,2)." KB";
        }
        else if($size < $gb)
        {
            return round($size/$mb,2)." MB";
        }
        else if($size < $tb)
        {
            return round($size/$gb,2)." GB";
        }
        else
        {
            return round($size/$tb,2)." TB";
        }
    }
}