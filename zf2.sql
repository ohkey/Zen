
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `access`
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `as_id` int(4) NOT NULL AUTO_INCREMENT,
  `as_resource` int(4) NOT NULL COMMENT '资源id',
  `as_action` text NOT NULL COMMENT '可执行操作',
  `as_act` varchar(10) NOT NULL COMMENT '是否可访问',
  `as_groupid` int(4) NOT NULL DEFAULT '0' COMMENT '分组id',
  `as_group` varchar(20) DEFAULT NULL COMMENT '组别',
  `as_note` varchar(140) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`as_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of access
-- ----------------------------
INSERT INTO `access` VALUES ('1', '3', 'a:4:{i:0;s:5:\"index\";i:1;s:5:\"login\";i:2;s:8:\"loginout\";i:3;s:8:\"looknews\";}', 'allow', '3', 'guest', null);
INSERT INTO `access` VALUES ('2', '2', 'a:1:{i:0;s:5:\"index\";}', 'allow', '3', 'guest', null);
INSERT INTO `access` VALUES ('3', '2', 'a:1:{i:0;s:5:\"index\";}', 'allow', '2', 'member', null);

-- ----------------------------
-- Table structure for `groups`
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `gp_id` int(4) NOT NULL AUTO_INCREMENT,
  `gp_name` varchar(20) NOT NULL COMMENT '组名',
  `gp_role` varchar(20) NOT NULL COMMENT '角色',
  `gp_parentrole` varchar(20) DEFAULT '0' COMMENT '父角色',
  `gp_note` varchar(140) DEFAULT NULL COMMENT '备注',
  `gp_isdelete` tinyint(2) DEFAULT '0' COMMENT '是否已删除',
  PRIMARY KEY (`gp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组表';

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('1', '超级管理员', 'administrator', '0', '可管理网站所有功能（不可删除）', '0');
INSERT INTO `groups` VALUES ('2', '普通管理员', 'member', '0', '普通管理员', '0');
INSERT INTO `groups` VALUES ('3', '游客', 'guest', '0', '游客', '0');

-- ----------------------------
-- Table structure for `health`
-- ----------------------------
DROP TABLE IF EXISTS `health`;
CREATE TABLE `health` (
  `Hid` int(11) NOT NULL AUTO_INCREMENT COMMENT '健康常识ID',
  `Htime` datetime NOT NULL COMMENT '健康常识发布时间',
  `Htitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '健康常识发布标题',
  `Hcontent` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '健康常识发布内容',
  `HCID` int(11) NOT NULL COMMENT '健康常识分类ID',
  PRIMARY KEY (`Hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='健康常识表';

-- ----------------------------
-- Table structure for `healthclassify`
-- ----------------------------
DROP TABLE IF EXISTS `healthclassify`;
CREATE TABLE `healthclassify` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '常识ID',
  `Hcname` varchar(255) NOT NULL COMMENT '常识分类名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='常识分类表';

-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `Nid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Ntime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2012-12-21',
  `Ntitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Ncontent` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `NCID` int(11) NOT NULL,
  PRIMARY KEY (`Nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新闻表';

-- ----------------------------
-- Table structure for `newsclassify`
-- ----------------------------
DROP TABLE IF EXISTS `newsclassify`;
CREATE TABLE `newsclassify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NCName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `resource`
-- ----------------------------
DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `um_id` int(4) NOT NULL AUTO_INCREMENT,
  `um_url` varchar(50) DEFAULT NULL COMMENT '资源',
  `um_name` varchar(50) DEFAULT NULL COMMENT '资源名称',
  `um_pid` int(4) NOT NULL COMMENT '父级id',
  `um_order` int(4) NOT NULL COMMENT '排列顺序',
  `um_isurl` int(1) NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `um_level` int(1) DEFAULT '0' COMMENT '菜单级别',
  PRIMARY KEY (`um_id`),
  KEY `um_id` (`um_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户菜单表';

-- ----------------------------
-- Records of resource
-- ----------------------------
INSERT INTO `resource` VALUES ('1', 'TeamWork', '主模块', '0', '0', '1', '0');
INSERT INTO `resource` VALUES ('2', 'TeamWork\\Controller\\Index', '总览', '1', '100', '1', '0');
INSERT INTO `resource` VALUES ('3', 'TeamWork\\Controller\\Login', '登陆', '1', '11', '0', '0');
INSERT INTO `resource` VALUES ('4', 'TeamWork\\Controller\\Rights', '权限管理', '1', '200', '1', '1');
INSERT INTO `resource` VALUES ('5', '/TeamWork/Rights/account', '账号管理', '4', '203', '1', '2');
INSERT INTO `resource` VALUES ('7', 'TeamWork\\Controller\\Center', '个人中心', '1', '300', '1', '1');
INSERT INTO `resource` VALUES ('8', '/TeamWork/Center/index', '个人中心', '7', '301', '1', '2');
INSERT INTO `resource` VALUES ('9', 'TeamWork\\Controller\\Website', '网站管理', '1', '400', '1', '1');
INSERT INTO `resource` VALUES ('10', '/TeamWork/Website/looknews', '新闻管理', '9', '401', '1', '2');
INSERT INTO `resource` VALUES ('11', 'TeamWork\\Controller\\Sysoption', '系统设置', '1', '1000', '1', '1');
INSERT INTO `resource` VALUES ('12', '/TeamWork/Sysoption/index', '系统设置', '11', '1001', '1', '2');
INSERT INTO `resource` VALUES ('14', '/TeamWork/Rights/privileges', '分组权限', '4', '202', '1', '2');
INSERT INTO `resource` VALUES ('15', '/TeamWork/Rights/groups', '分组管理', '4', '201', '1', '2');
INSERT INTO `resource` VALUES ('16', '/TeamWork/Website/lookhealth', '常识管理', '9', '402', '1', '2');
INSERT INTO `resource` VALUES ('17', 'TeamWork\\Controller\\Firm', '厂商管理', '1', '500', '1', '1');
INSERT INTO `resource` VALUES ('18', '/TeamWork/Firm/supplier', '厂家列表', '17', '501', '1', '2');
INSERT INTO `resource` VALUES ('19', '/TeamWork/Firm/device', '设备管理', '17', '502', '1', '2');

-- ----------------------------
-- Table structure for `supplier`
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '供应商ID',
  `name` varchar(255) NOT NULL COMMENT '公司名称',
  `address` varchar(255) NOT NULL COMMENT '公司地址',
  `phone` int(50) NOT NULL COMMENT '联系电话',
  `main_principal` varchar(255) NOT NULL COMMENT '主要负责人',
  `business_linkman` varchar(255) NOT NULL COMMENT '业务联系人',
  `postcode` int(11) NOT NULL COMMENT '邮编',
  `fax` varchar(50) NOT NULL COMMENT '传真',
  `url` varchar(255) NOT NULL COMMENT '公司网址',
  `email` varchar(50) NOT NULL COMMENT '电子邮件',
  `retister_capital` varchar(50) NOT NULL COMMENT '公司概况(注册资本)',
  `established_time` varchar(50) NOT NULL COMMENT '公司概况(成立时间)',
  `credit_rating` varchar(50) NOT NULL COMMENT '公司概况(银行信用状况)',
  `manpower_resource` varchar(50) NOT NULL COMMENT '公司概况(人力资源状况)',
  `floor_space` varchar(50) NOT NULL COMMENT '公司概况(占地面积)',
  `turnover` varchar(50) NOT NULL COMMENT '公司概况(营业额)',
  `equipment` varchar(50) NOT NULL COMMENT '公司概况(设备状况)',
  `main_product` text NOT NULL COMMENT '主要产品及服务',
  `main_production_equipment` text NOT NULL COMMENT '主要生产设备',
  `main_production_craft` text NOT NULL COMMENT '主要生产工艺',
  `main_test_facility` text NOT NULL COMMENT '主要试验设备',
  `main_detection_facility` text NOT NULL COMMENT '主要检测设备',
  `month_supply` varchar(255) NOT NULL COMMENT '生产能力（月供货能力）',
  `delivery_cycle` varchar(255) NOT NULL COMMENT '生产能力（正常交付周期）',
  `client_group` text NOT NULL COMMENT '主要客户简介（客户群体）',
  `proportion` varchar(255) NOT NULL COMMENT '主要客户简介（所提供的产品占生产的比例）',
  `clearing_form` varchar(255) NOT NULL COMMENT '结算方式',
  `any_business` text NOT NULL COMMENT '其他事项',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供销商';

-- ----------------------------
-- Table structure for `teamuser`
-- ----------------------------
DROP TABLE IF EXISTS `teamuser`;
CREATE TABLE `teamuser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `EndUserName` varchar(16) NOT NULL COMMENT '用户名',
  `EndUserPwd` char(32) NOT NULL,
  `role` varchar(255) NOT NULL COMMENT '角色（组别）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `TeamUserName` (`EndUserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of teamuser
-- ----------------------------
INSERT INTO `teamuser` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'administrator');

-- ----------------------------
-- Table structure for `teamworkaction`
-- ----------------------------
DROP TABLE IF EXISTS `teamworkaction`;
CREATE TABLE `teamworkaction` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '动作名',
  `controller` int(3) NOT NULL COMMENT '控制器id',
  `parentaction` int(3) DEFAULT NULL COMMENT '隶属动作',
  `action` varchar(20) NOT NULL COMMENT '动作',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of teamworkaction
-- ----------------------------
INSERT INTO `teamworkaction` VALUES ('1', '编辑权限', '4', '14', 'groupauthority');
INSERT INTO `teamworkaction` VALUES ('2', '添加分组', '4', '15', 'addgroup');
INSERT INTO `teamworkaction` VALUES ('3', '编辑分组', '4', '15', 'editgroup');
INSERT INTO `teamworkaction` VALUES ('4', '删除分组', '4', '15', 'deletegroup');
INSERT INTO `teamworkaction` VALUES ('5', '添加账号', '4', '5', 'addaccount');
INSERT INTO `teamworkaction` VALUES ('6', '编辑账号', '4', '5', 'editaccount');
INSERT INTO `teamworkaction` VALUES ('7', '删除账号', '4', '5', 'deleteaccount');
