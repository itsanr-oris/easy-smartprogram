## EasySmartProgram

百度智能小程序SDK，参照easy-wechat实现



## 功能
- [x] 小程序登录
- [x] 小程序授权信息解密
- [x] 小程序模板消息管理，发送模板消息
- [x] swan_id校验组件
- [ ] 信息流资源
- [x] 获取unionid

## 安装

```bash
composer require f-oris/easy-smartprogram
```

## 基本使用

参考[easy-wechat](https://github.com/overtrue/wechat)使用文档，因为是仿着做的，所以小程序各组件提供的方法，含义，用法基本上和easy-wechat一致

## 获取unionid

```php
// 配置好config，获取code...

$app = new Application($config);
$session = $app->auth->session($code);
$uionidData = $app->auth->getUnionid($session['openid']);
// {"unionid": "St6PVMkgMDeh92Uq2EWfx6H"}

```

## 信息资源流

```
// 注意：此功能尚未在实际产品上应用验证，慎用

// 配置好config ...
$app = new Application($config);

// Resource操作，传入参数见官方文档说明
$app->resource->submit($data);
$app->resource->delete($path);

// Sitemap操作，传入参数见官方文档说明
$app->site_map->submit($url, $desc, $type, $frequency);
$app->site_map->delete($url);

```

## License

MIT License

Copyright (c) 2019-present F.oris <us@f-oris.me>
