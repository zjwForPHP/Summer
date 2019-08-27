# Summer

<p align="center"><img src="https://wxlifepay.babel-group.cn/web/assets/img/logo-blue.png"></p>

# About

**一个使用yaf作为MVC架构，用swoole作为服务入口的轻量级API框架**

### 特性

- 框架分两种模式，开发模式（YAF-fpm模式）和生产模式（YAF-SWOOLE），调试开发阶段用开发模式，项目上线后请切换至生产模式，生产模式QPS是开发模式的4-5倍
- 框架基于Yaf，只封装了简单的mvc架构和pdo，轮子需要自己搭，但是可以使用composer，但是使用composer将大幅度减少性能，所以框架默认配置中已关闭
- 可以在框架任何地方使用协程，多协程可让性能更高
- 框架主做同步阻塞的HTTP类API

### version 1.1.0 列队增强版

1. 修改若干BUG
2. 增加全异步队列支持
3. 自动加载的优化

### 依赖更新说明
1. php >= 7.1.0
2. swoole >= 4.3.0
3. yaf >= 3.0.0
