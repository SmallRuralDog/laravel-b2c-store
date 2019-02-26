laravel-admin B2C商城扩展
======
注意：请先安装好 `https://github.com/z-song/laravel-admin`

## 安装
```
composer require small-rural-dog/store
```
## 发布资源
```
php artisan vendor:publish --provider="SmallRuralDog\Store\StoreServiceProvider"
```
## 自定义配置

配置 `config/store.php` ，默认可忽略

## 执行安装命令
```
php artisan store:install

php artisan admin:import store
```

## 可体验功能
- 商品列表
  - 基本展示
  - 排序
  - 修改
- 添加修改产品
  - 基本信息
  - 媒体管理
  - SKU管理