## Author

    作者：F.oris
    邮箱：us@f-oris.me

## 简介

&emsp;&emsp;百度智能小程序SDK，参照easy-wechat实现，主要用与公司项目从微信小程序横向拓展到百度小程序，兼容较底层的平台对接（之前用easy-wechat对接微信平台），降低平台对接与业务代码的耦合以及代码理解维护成本，不定期维护更新

## 功能
- [x] 小程序登录
- [x] 小程序授权信息解密
- [x] 小程序模板消息管理，发送模板消息
- [x] swan_id校验组件
- [ ] 资源流

## 使用

composer require f-oris/easy-smartprogram:dev-master

参考[easy-wechat](https://github.com/overtrue/wechat)，因为是仿着做的，所以小程序各组件提供的方法，含义，用法基本上和easy-wechat一致，底层有点不一样，需要深入定制扩展的要看一下源码

## 备注
&emsp;&emsp;尚未完成测试，仅简单调用了一下接口，基本上是逻辑是通的
