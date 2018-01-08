# Flysystem Ufile

增加 laravel 的文件系统驱动，使其支持ufile。

# Install

```
composer require 'hlf_513/flysystem-ufile'
```

# Config

配置 `config\filesystems.php`；增加如下代码

``` php
...
'disks'=>[
...
    'ufile' => [
        'driver' => 'ufile',
        'bucket' => env('UFILE_BUCKET'),
        'suffix' => env('UFILE_SUFFIX'),  // ufile上传地址
        'suffix_cdn' => env('UFILE_SUFFIX_CDN'), // ufile的CDN地址
        'public_key' => env('UFILE_ACCESS_KEY'), //AccessKey
        'secret_key' => env('UFILE_SECRET_KEY'), //SecretKey
    ],
],
...
```

然后在 `.env` 中新增各个配置项
```
UFILE_ACCESS_KEY=
UFILE_SECRET_KEY=
UFILE_SUFFIX_CDN=.ufile.ucloud.com.cn
UFILE_SUFFIX=
UFILE_BUCKET=
```

# Usage

https://laravel.com/docs/5.5/filesystem
