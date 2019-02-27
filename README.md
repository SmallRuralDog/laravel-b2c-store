laravel-admin B2C商城扩展
======



> 提供可管理的UI后台，无客户端UI，调用静态方法来操作数据。安装即可使用，不影响原系统。

## 安装
注意：请先安装好 `https://github.com/z-song/laravel-admin`
```
composer require small-rural-dog/store
```
### 发布资源
```
php artisan vendor:publish --provider="SmallRuralDog\Store\StoreServiceProvider"
```
### 自定义配置

配置 `config/store.php` ，默认可忽略

### 执行安装命令
```
php artisan store:install

php artisan admin:import store
```

## 升级
```
composer update small-rural-dog/store

php artisan vendor:publish --tag=store --force

php artisan store:update
```

## 功能（初步，可能改动很大）

- 产品管理
    - 商品列表
      - 基本展示√
      - 排序√
      - 修改√
    - 添加修改产品
      - 基本信息√
      - 媒体管理√
      - SKU管理√
- 品牌管理
- 分类管理
  - 分类属性
- 订单管理
    - 发货
    - 退款
    - 售后
    - 评论
- 物流管理
    - 物流模板
- 营销管理
    - 优惠券
    - 满减
- 数据统计

## 开放数据

- 产品
   - 产品列表
   - 产品搜索
   - 产品详情
      - sku
      - 图文详情
      - 库存
      - 营销
   - 评论
- 购物车
    - 加入购物车
    - 结算
- 订单
    - 创建订单
    - 订单支付成功
    - 订单售后

## API文档

暂时没有

![image](https://user-images.githubusercontent.com/5151848/53393898-8e9ec600-39d8-11e9-959d-e3c67fd94e90.png)
