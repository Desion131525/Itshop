<?php
header("Content-Type: text/html;charset=utf-8");
/*
 项目需求和开发进度安排:
http://note.youdao.com/noteshare?id=b92b75620dc24b08afc527a1612c84c7

参考网站:
后台:http://admin.bigphp.cn   账号admin 密码123456

老师代码仓库:
https://github.com/flyok666/php0711yii2shop.git

品牌管理
需求
品牌的增删改查
品牌删除使用逻辑删除(status=>-1)
数据表设计

brand 品牌
字段名	类型	注释
id	primaryKey
name	varchar(50)	名称
intro	text	简介
logo	varchar(255)	LOGO图片
sort	int(11)	排序
status	int(2)	状态(-1删除 0隐藏 1正常)


文章分类管理

需求

文章分类的增删改查
数据表设计

article_category 文章

字段名	类型	注释
id	primaryKey
name	varchar(50)	名称
intro	text	简介
sort	int(11)	排序
status	int(2)	状态(-1删除 0隐藏 1正常)

文章管理

需求

文章表使用垂直分表,分两张表保存,文章表(article)和文章分类表(article_category)
文章表的增删改查(多模型同时输入)
数据表设计

article 文章

字段名	类型	注释
id	primaryKey
name	varchar(50)	名称
intro	text	简介
article_category_id	int()	文章分类id
sort	int(11)	排序
status	int(2)	状态(-1删除 0隐藏 1正常)
create_time	int(11)	创建时间

article_detail 文章详情
字段名	类型	注释
article_id	primaryKey	文章id
content	text	简介

day3

商品分类管理

需求

商品分类增删改查
使用嵌套集合保存分类数据
使用ztree插件展示商品分类
数据表设计

goods_category 商品分类

字段名	类型	注释
id	primaryKey
tree	int()	树id
lft	int()	左值
rgt	int()	右值
depth	int()	层级
name	varchar(50)	名称
parent_id	int()	上级分类id
intro	text()	简介
插件

composer安装嵌套集合插件 "creocoder/yii2-nested-sets": "^0.9.0",
ztree插件官网 http://www.ztree.me
知识点


day4

商品管理

需求

商品增删改查
商品相册图片添加,删除和列表展示
商品列表页可以进行搜索
新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
商品详情使用ueditor文本编辑器
记录每天创建商品数
数据表设计

goods_day_count 商品每日添加数
字段名	类型	注释
day	 date	日期
count	int	商品数

goods 商品表
字段名	类型	注释
id	primaryKey
name	varchar(20)	商品名称
sn	varchar(20)	货号
logo	varchar(255)	LOGO图片
goods_category_id	int	商品分类id
brand_id	int	品牌分类
market_price	decimal(10,2)	市场价格
shop_price	decimal(10, 2)	商品价格
stock	int	库存
is_on_sale	int(1)	是否在售(1在售 0下架)
status	inter(1)	状态(1正常 0回收站)
sort	int()	排序
create_time	int()	添加时间
view_times	int()	浏览次数

goods_intro 商品详情表
字段名	类型	注释
goods_id	int	商品id
content	text	商品描述

goods_gallery 商品图片表
字段名	类型	注释
id	primaryKey
goods_id	int	商品id
path	varchar(255)	图片地址
插件

composer安装ueditor文本编辑器插件（插件地址https://github.com/BigKuCha/yii2-ueditor-widget）
composer require "kucha/ueditor": "*"


day7

需求

使用RBAC管理用户和权限
权限增删改查
角色增删改查
角色和权限关联
用户和角色关联
插件

权限列表展示使用datatables插件
插件网址https://datatables.net/
插件入门文档http://www.datatables.club/manual/install.html