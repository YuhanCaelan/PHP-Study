# PHP学习环境（Docker Compose）

本仓库包含两套可选环境（共享同一份代码与数据库，仅启动方式不同）：
- LAMP（Apache + PHP + MariaDB + phpMyAdmin + BrowserSync）
- LNMP（Nginx + PHP-FPM + MariaDB + phpMyAdmin + BrowserSync）

BrowserSync用于前端实时刷新（监控PHP/HTML/CSS/JS）。

## 目录结构
- src/ 共享代码目录
- nginx/ LNMP专用配置

## LAMP
启动：
```
docker compose -f docker-compose.lamp.yml up -d
```

访问：
- 应用（Apache）：http://localhost:8081
- 实时刷新（BrowserSync）：http://localhost:3000
- phpMyAdmin：http://localhost:8082

数据库默认：
- host: db
- user: root
- password: root
- database: app

## LNMP
启动：
```
docker compose -f docker-compose.lnmp.yml up -d
```

访问：
- 应用（Nginx）：http://localhost:8080
- 实时刷新（BrowserSync）：http://localhost:3000
- phpMyAdmin：http://localhost:8082

数据库默认：
- host: db
- user: root
- password: root
- database: app

## 说明
- 代码统一放在 src/ 目录。
- 修改 PHP/HTML/CSS/JS 后，BrowserSync 会自动刷新。
- 停止服务：`docker compose down`（记得带上 -f）。
