<?php


namespace SmallRuralDog\Store\Helpers;


class MediaTool
{
    public static function storeMedia($media)
    {
        $disk = \Storage::disk($media->disk);
        return $disk->url($media->path);
    }


    public static function setUserAvatar($path)
    {
        if (empty($path)) {
            return config('union.user.default_avatar', 'https://img.yzcdn.cn/upload_files/2015/07/12/FkT41mwUuofyf-VAjxun_0SRRwQS.png');
        }
        if (starts_with($path, 'http') || starts_with($path, '//')) {
            return $path;
        }
        return \Storage::url($path);
    }

    public static function setSize($size)
    {
        $units = array(' B', ' KB', ' M', ' G', ' T');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $units[$i];

    }
}