/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : fgdatabase

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2016-11-24 22:49:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for fg_act
-- ----------------------------
DROP TABLE IF EXISTS `fg_act`;
CREATE TABLE `fg_act` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `title` varchar(256) NOT NULL COMMENT '活动主题',
  `intro` varchar(2048) NOT NULL DEFAULT '' COMMENT '活动详情',
  `name_format` varchar(64) NOT NULL DEFAULT '' COMMENT '名字格式',
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `userid` int(11) NOT NULL COMMENT '发布人id',
  `phone` char(12) NOT NULL COMMENT '发布人电话',
  `vote_state` char(1) NOT NULL DEFAULT '0' COMMENT '时间地点是否确定(0否1是)',
  `join_num` smallint(4) NOT NULL DEFAULT '0' COMMENT '参加人数',
  `logo_id` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=356 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_act
-- ----------------------------
INSERT INTO `fg_act` VALUES ('338', '电影', '大家一起去看电影啦', '真实姓名', '1476444492', '2157', '15875064665', '1', '1', '5');
INSERT INTO `fg_act` VALUES ('339', '唱K', '来唱K吧哈哈哈', '真实姓名\n真实姓名\n真实姓名', '1476444638', '2158', '13692190638', '1', '1', '0');
INSERT INTO `fg_act` VALUES ('340', '宣讲会', '欢迎大家到来', '真实姓名', '1476444956', '2157', '15875064665', '1', '1', '8');
INSERT INTO `fg_act` VALUES ('341', '看电影', '欢迎大家参加。。。', '名字，如“志伟”', '1476451032', '2158', '15875064665', '0', '1', '3');
INSERT INTO `fg_act` VALUES ('343', '运动', '来一起运动吧', '志伟', '1476452555', '2158', '15089837950', '1', '0', '4');
INSERT INTO `fg_act` VALUES ('344', '电影', '来看场电影呗，地点在益华或者新之城，来讨论下', '格式不限', '1476452703', '2158', '13692190638', '1', '0', '5');
INSERT INTO `fg_act` VALUES ('345', '宣讲会', '18号下午六点半有一场精彩的宣讲会\n我们不见不散', '不限', '1476452818', '2158', '15875064665', '1', '0', '8');
INSERT INTO `fg_act` VALUES ('346', '桌游', '明天来玩桌游吧\n时间地点未定\n欢迎桌游爱好者的你来参加哦', '不可使用真实姓名哦', '1476452943', '2158', '13692190638', '0', '2', '2');
INSERT INTO `fg_act` VALUES ('349', '测试', '略', '志伟', '1478096244', '2157', '13692190638', '1', '0', '0');
INSERT INTO `fg_act` VALUES ('353', '测试', '略', '啦啦啦', '1478137453', '2157', '15875064665', '0', '0', '0');
INSERT INTO `fg_act` VALUES ('355', '看电影', '一起来看电影吧，地点在益华或者一汇', '真实姓名', '1478149192', '2157', '13692190638', '0', '0', '0');

-- ----------------------------
-- Table structure for fg_act_address
-- ----------------------------
DROP TABLE IF EXISTS `fg_act_address`;
CREATE TABLE `fg_act_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `actid` int(11) NOT NULL COMMENT '所属活动id',
  `address` varchar(526) NOT NULL COMMENT '活动地点',
  `votes` smallint(4) NOT NULL DEFAULT '0' COMMENT '所得票数',
  `choose` char(1) DEFAULT '0' COMMENT '选中为1，未选中为0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_act_address
-- ----------------------------
INSERT INTO `fg_act_address` VALUES ('196', '338', '江门中影火星湖电影城(江门店)', '1', '1');
INSERT INTO `fg_act_address` VALUES ('197', '338', '江门DMAX电影院', '0', '0');
INSERT INTO `fg_act_address` VALUES ('198', '339', '江门唱喜来KTV', '1', '1');
INSERT INTO `fg_act_address` VALUES ('199', '339', '江门唱k8娱乐会所', '0', '0');
INSERT INTO `fg_act_address` VALUES ('200', '340', '江门五邑大学-南主楼', '0', '1');
INSERT INTO `fg_act_address` VALUES ('201', '341', '江门中影火星湖影院', '1', '0');
INSERT INTO `fg_act_address` VALUES ('202', '341', '万达广场影院', '0', '0');
INSERT INTO `fg_act_address` VALUES ('205', '343', '江门新会体育馆(公交站)', '0', '1');
INSERT INTO `fg_act_address` VALUES ('206', '344', '江门中影火星湖电影城(江门店)', '0', '0');
INSERT INTO `fg_act_address` VALUES ('207', '344', '江门大地数字影院(益华购物广场店)', '0', '1');
INSERT INTO `fg_act_address` VALUES ('208', '345', '江门十友楼', '0', '1');
INSERT INTO `fg_act_address` VALUES ('209', '346', '江门南珠楼', '2', '0');
INSERT INTO `fg_act_address` VALUES ('210', '346', '江门五邑大学-北主楼', '0', '0');
INSERT INTO `fg_act_address` VALUES ('213', '349', '江门五邑大学(北门)', '0', '1');
INSERT INTO `fg_act_address` VALUES ('214', '349', '江门益华百货(南门)', '0', '0');
INSERT INTO `fg_act_address` VALUES ('222', '353', '江门宝宝宠物店(港口二路)', '0', '0');
INSERT INTO `fg_act_address` VALUES ('223', '353', '江门妞妞仔仔(水南路)', '0', '0');
INSERT INTO `fg_act_address` VALUES ('225', '355', '江门益华百货', '0', '0');
INSERT INTO `fg_act_address` VALUES ('226', '355', '江门一汇广场', '0', '0');

-- ----------------------------
-- Table structure for fg_act_comment
-- ----------------------------
DROP TABLE IF EXISTS `fg_act_comment`;
CREATE TABLE `fg_act_comment` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `actid` int(11) NOT NULL COMMENT '所属活动id',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '评论内容',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  `userid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_act_comment
-- ----------------------------
INSERT INTO `fg_act_comment` VALUES ('34', '338', '是不是傻', '1476444670', '2158');
INSERT INTO `fg_act_comment` VALUES ('35', '339', '志伟傻逼', '1476444841', '2157');
INSERT INTO `fg_act_comment` VALUES ('36', '339', '志伟是逗比', '1476878680', '2157');
INSERT INTO `fg_act_comment` VALUES ('37', '349', '哈', '1478440374', '2157');

-- ----------------------------
-- Table structure for fg_act_time
-- ----------------------------
DROP TABLE IF EXISTS `fg_act_time`;
CREATE TABLE `fg_act_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `actid` int(11) NOT NULL COMMENT '所属活动id',
  `starttime` int(10) NOT NULL COMMENT '活动开始时间',
  `votes` smallint(4) NOT NULL DEFAULT '0' COMMENT '所得票数',
  `timelast` decimal(2,1) NOT NULL COMMENT '活动时长',
  `choose` char(1) NOT NULL DEFAULT '0' COMMENT '选中为1，未选中为0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=376 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_act_time
-- ----------------------------
INSERT INTO `fg_act_time` VALUES ('346', '338', '1476442800', '1', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('347', '338', '1482145200', '0', '3.0', '1');
INSERT INTO `fg_act_time` VALUES ('348', '339', '1507980600', '0', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('349', '339', '1476442800', '1', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('350', '339', '1476444420', '0', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('351', '339', '1507980420', '0', '3.0', '1');
INSERT INTO `fg_act_time` VALUES ('352', '340', '1476442800', '0', '3.0', '1');
INSERT INTO `fg_act_time` VALUES ('353', '341', '1498838460', '1', '2.0', '0');
INSERT INTO `fg_act_time` VALUES ('356', '343', '1476700200', '0', '3.0', '1');
INSERT INTO `fg_act_time` VALUES ('357', '344', '1476538200', '0', '3.0', '1');
INSERT INTO `fg_act_time` VALUES ('358', '344', '1476523800', '0', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('359', '345', '1476786600', '0', '3.0', '1');
INSERT INTO `fg_act_time` VALUES ('360', '346', '1476532800', '1', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('361', '346', '1476531000', '1', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('364', '349', '1478787360', '0', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('365', '349', '1478960160', '0', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('366', '349', '1478096220', '0', '3.0', '1');
INSERT INTO `fg_act_time` VALUES ('373', '353', '1478137380', '0', '3.0', '0');
INSERT INTO `fg_act_time` VALUES ('375', '355', '1478210400', '0', '3.0', '0');

-- ----------------------------
-- Table structure for fg_admin
-- ----------------------------
DROP TABLE IF EXISTS `fg_admin`;
CREATE TABLE `fg_admin` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(32) NOT NULL DEFAULT '',
  `password` char(64) NOT NULL DEFAULT '',
  `last_time` timestamp NULL DEFAULT NULL,
  `last_ip_place` char(64) DEFAULT '',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_admin
-- ----------------------------
INSERT INTO `fg_admin` VALUES ('10', 'a', '77de54ccf56eb6f7dbf99e4d3be949ab', '2016-10-14 20:18:36', '', '2016-09-13 17:28:36');
INSERT INTO `fg_admin` VALUES ('18', 'a', '77de54ccf56eb6f7dbf99e4d3be949ab', null, '', '2016-09-13 19:12:14');
INSERT INTO `fg_admin` VALUES ('19', 'a', '77de54ccf56eb6f7dbf99e4d3be949ab', null, '', '2016-09-13 19:12:15');

-- ----------------------------
-- Table structure for fg_joinact
-- ----------------------------
DROP TABLE IF EXISTS `fg_joinact`;
CREATE TABLE `fg_joinact` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `actid` int(11) NOT NULL COMMENT '活动id',
  `userid` int(11) DEFAULT '0' COMMENT '用户id',
  `power` char(1) NOT NULL DEFAULT '0' COMMENT '活动身份（1发起者，0参与者）',
  `name_format` varchar(64) NOT NULL DEFAULT '' COMMENT '名字格式',
  `time_voted` varchar(128) NOT NULL DEFAULT '' COMMENT '投票时间id拼接',
  `address_voted` varchar(128) NOT NULL DEFAULT '' COMMENT '地点投票id,用-拼接',
  `is_share` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=443 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_joinact
-- ----------------------------
INSERT INTO `fg_joinact` VALUES ('421', '338', '2157', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('422', '339', '2158', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('423', '338', '2158', '0', '略', '346', '196', '0');
INSERT INTO `fg_joinact` VALUES ('424', '339', '2157', '0', '略', '349', '198', '0');
INSERT INTO `fg_joinact` VALUES ('425', '340', '2157', '1', '', '', '', '1');
INSERT INTO `fg_joinact` VALUES ('426', '340', '2158', '0', '露宇', '', '', '1');
INSERT INTO `fg_joinact` VALUES ('427', '341', '2158', '2', '略', '353', '201', '0');
INSERT INTO `fg_joinact` VALUES ('429', '343', '2158', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('430', '344', '2158', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('431', '345', '2158', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('432', '346', '2158', '2', '略', '361', '209', '0');
INSERT INTO `fg_joinact` VALUES ('435', '349', '2157', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('439', '353', '2157', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('441', '355', '2157', '1', '', '', '', '0');
INSERT INTO `fg_joinact` VALUES ('442', '346', '2157', '0', '略', '360', '209', '0');

-- ----------------------------
-- Table structure for fg_picture
-- ----------------------------
DROP TABLE IF EXISTS `fg_picture`;
CREATE TABLE `fg_picture` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(3) unsigned NOT NULL,
  `picture` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_picture
-- ----------------------------

-- ----------------------------
-- Table structure for fg_place
-- ----------------------------
DROP TABLE IF EXISTS `fg_place`;
CREATE TABLE `fg_place` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '场所名称',
  `intro` varchar(256) NOT NULL DEFAULT '' COMMENT '场所简介',
  `type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `show_picture` varchar(64) DEFAULT '' COMMENT '场所背景图',
  `phone` char(20) NOT NULL DEFAULT '',
  `clerk` char(10) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `ctime` timestamp NOT NULL,
  `region` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_place
-- ----------------------------
INSERT INTO `fg_place` VALUES ('11', '茶木TeaWood', '在这里，让空间不再存在想象的边界，让时间不再稍纵即逝。带上朋友，就在TEAWOOD开始我们甜蜜的序曲吧！', '2', 'Public/place/2-5800cd310da2b.jpg', '25632299', '李小姐', '铜锣湾轩尼诗道502号黄金广场9楼', '2016-10-14 20:18:57', '33');

-- ----------------------------
-- Table structure for fg_place_comment
-- ----------------------------
DROP TABLE IF EXISTS `fg_place_comment`;
CREATE TABLE `fg_place_comment` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `place_id` int(11) NOT NULL DEFAULT '0' COMMENT '场所id',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '评论内容',
  `ctime` timestamp NOT NULL COMMENT '创建时间',
  `user_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_place_comment
-- ----------------------------

-- ----------------------------
-- Table structure for fg_place_type
-- ----------------------------
DROP TABLE IF EXISTS `fg_place_type`;
CREATE TABLE `fg_place_type` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_place_type
-- ----------------------------
INSERT INTO `fg_place_type` VALUES ('1', '运动');
INSERT INTO `fg_place_type` VALUES ('2', '电影');
INSERT INTO `fg_place_type` VALUES ('3', '美食');
INSERT INTO `fg_place_type` VALUES ('4', '展览');
INSERT INTO `fg_place_type` VALUES ('5', '爬山');
INSERT INTO `fg_place_type` VALUES ('6', '摄影');
INSERT INTO `fg_place_type` VALUES ('7', '聚会');
INSERT INTO `fg_place_type` VALUES ('8', '讲座');
INSERT INTO `fg_place_type` VALUES ('9', '演出');
INSERT INTO `fg_place_type` VALUES ('10', '游戏');
INSERT INTO `fg_place_type` VALUES ('11', '桌游');

-- ----------------------------
-- Table structure for fg_shareact_comment
-- ----------------------------
DROP TABLE IF EXISTS `fg_shareact_comment`;
CREATE TABLE `fg_shareact_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actid` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '二级评论id,与一级评论id对应',
  `reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '二级评论中的回复，与二级评论的parent_id对应',
  `userid` int(10) NOT NULL,
  `content` varchar(512) NOT NULL,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_shareact_comment
-- ----------------------------
INSERT INTO `fg_shareact_comment` VALUES ('9', '555', '0', '0', '2158', '沙发', '1476446219');
INSERT INTO `fg_shareact_comment` VALUES ('10', '555', '9', '0', '2157', '哈哈', '1476446242');
INSERT INTO `fg_shareact_comment` VALUES ('11', '555', '0', '0', '2157', '志伟***', '1476446266');
INSERT INTO `fg_shareact_comment` VALUES ('12', '555', '9', '10', '2158', '啦啦啦啦', '1476446292');
INSERT INTO `fg_shareact_comment` VALUES ('13', '556', '0', '0', '2158', '沙发', '1476449932');
INSERT INTO `fg_shareact_comment` VALUES ('16', '556', '13', '0', '2159', '第二', '1476449958');
INSERT INTO `fg_shareact_comment` VALUES ('17', '556', '13', '16', '2158', '第三', '1476449974');
INSERT INTO `fg_shareact_comment` VALUES ('19', '556', '0', '0', '2159', '竟然没回复', '1476450005');
INSERT INTO `fg_shareact_comment` VALUES ('20', '556', '13', '17', '2159', '我是东强', '1476450050');
INSERT INTO `fg_shareact_comment` VALUES ('21', '556', '0', '0', '2157', '有评论啊，哈哈', '1476450262');
INSERT INTO `fg_shareact_comment` VALUES ('22', '556', '21', '0', '2158', '好有趣', '1476450285');
INSERT INTO `fg_shareact_comment` VALUES ('24', '556', '19', '0', '2158', '给你回复', '1476450504');
INSERT INTO `fg_shareact_comment` VALUES ('25', '556', '21', '0', '2158', '我又来回复啦', '1476450543');
INSERT INTO `fg_shareact_comment` VALUES ('26', '556', '0', '0', '2157', '来评论下午', '1476450647');
INSERT INTO `fg_shareact_comment` VALUES ('27', '556', '26', '0', '2157', '打错了尴尬', '1476450757');
INSERT INTO `fg_shareact_comment` VALUES ('28', '556', '26', '27', '2158', '傻', '1476450820');
INSERT INTO `fg_shareact_comment` VALUES ('29', '556', '26', '28', '2157', '你有意见？', '1476450972');
INSERT INTO `fg_shareact_comment` VALUES ('30', '555', '0', '0', '2157', '把', '1478499854');

-- ----------------------------
-- Table structure for fg_shareact_comment_notice
-- ----------------------------
DROP TABLE IF EXISTS `fg_shareact_comment_notice`;
CREATE TABLE `fg_shareact_comment_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commentid` int(11) NOT NULL,
  `to_userid` int(11) NOT NULL COMMENT '接收消息人',
  `is_read` int(11) NOT NULL DEFAULT '0',
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_shareact_comment_notice
-- ----------------------------
INSERT INTO `fg_shareact_comment_notice` VALUES ('9', '9', '2157', '1', '1476446219');
INSERT INTO `fg_shareact_comment_notice` VALUES ('10', '10', '2158', '1', '1476446242');
INSERT INTO `fg_shareact_comment_notice` VALUES ('11', '11', '2157', '1', '1476446266');
INSERT INTO `fg_shareact_comment_notice` VALUES ('12', '12', '2157', '1', '1476446292');
INSERT INTO `fg_shareact_comment_notice` VALUES ('13', '13', '2158', '1', '1476449932');
INSERT INTO `fg_shareact_comment_notice` VALUES ('14', '14', '2158', '1', '1476449933');
INSERT INTO `fg_shareact_comment_notice` VALUES ('15', '15', '2158', '1', '1476449933');
INSERT INTO `fg_shareact_comment_notice` VALUES ('16', '16', '2158', '1', '1476449958');
INSERT INTO `fg_shareact_comment_notice` VALUES ('17', '17', '2159', '1', '1476449974');
INSERT INTO `fg_shareact_comment_notice` VALUES ('18', '18', '2159', '1', '1476449976');
INSERT INTO `fg_shareact_comment_notice` VALUES ('19', '19', '2158', '1', '1476450005');
INSERT INTO `fg_shareact_comment_notice` VALUES ('20', '20', '2158', '1', '1476450050');
INSERT INTO `fg_shareact_comment_notice` VALUES ('21', '21', '2158', '1', '1476450262');
INSERT INTO `fg_shareact_comment_notice` VALUES ('22', '22', '2157', '1', '1476450285');
INSERT INTO `fg_shareact_comment_notice` VALUES ('23', '23', '2157', '1', '1476450286');
INSERT INTO `fg_shareact_comment_notice` VALUES ('24', '24', '2159', '1', '1476450504');
INSERT INTO `fg_shareact_comment_notice` VALUES ('25', '25', '2157', '1', '1476450543');
INSERT INTO `fg_shareact_comment_notice` VALUES ('26', '26', '2158', '1', '1476450647');
INSERT INTO `fg_shareact_comment_notice` VALUES ('27', '27', '2157', '1', '1476450757');
INSERT INTO `fg_shareact_comment_notice` VALUES ('28', '28', '2157', '1', '1476450820');
INSERT INTO `fg_shareact_comment_notice` VALUES ('29', '29', '2158', '1', '1476450972');
INSERT INTO `fg_shareact_comment_notice` VALUES ('30', '30', '2157', '1', '1478499854');

-- ----------------------------
-- Table structure for fg_share_act
-- ----------------------------
DROP TABLE IF EXISTS `fg_share_act`;
CREATE TABLE `fg_share_act` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `title` varchar(256) NOT NULL COMMENT '分享活动标题',
  `feeling` varchar(2048) NOT NULL DEFAULT '' COMMENT '活动感想',
  `ctime` int(10) NOT NULL COMMENT '创建时间',
  `userid` int(11) NOT NULL COMMENT '发布人id',
  `head_path` char(60) NOT NULL DEFAULT '' COMMENT '发布人头像',
  `comments` smallint(5) NOT NULL DEFAULT '0' COMMENT '评论数',
  `collects` smallint(5) NOT NULL DEFAULT '0' COMMENT '收藏数',
  `cover` varchar(128) NOT NULL DEFAULT '' COMMENT '活动封面图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=557 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_share_act
-- ----------------------------
INSERT INTO `fg_share_act` VALUES ('555', '一场***，满场欢意', '这里是我的感想啦', '1476445458', '2157', 'Public/user/2157/user_head/2157-57bbfacf3714b.jpg', '5', '2', 'Public/share_act/555/cover.png');
INSERT INTO `fg_share_act` VALUES ('556', '有趣的宣讲会', '有什么好说的，就是想分享一下\n随便放几张壁纸', '1476445456', '2158', 'Public/user/2158/user_head/2158-57bbfafdb0177.jpg', '17', '0', 'Public/share_act/556/cover.png');

-- ----------------------------
-- Table structure for fg_share_act_handle
-- ----------------------------
DROP TABLE IF EXISTS `fg_share_act_handle`;
CREATE TABLE `fg_share_act_handle` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `share_act_collect` char(255) NOT NULL DEFAULT '' COMMENT '收藏活动id',
  `share_act_release` char(255) NOT NULL DEFAULT '' COMMENT '分享活动id',
  `share_act_ignore` char(255) DEFAULT '' COMMENT '不感兴趣活动id',
  `userid` int(11) NOT NULL COMMENT '用户id',
  `expose_release` tinyint(255) NOT NULL DEFAULT '1',
  `expose_collect` tinyint(255) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_share_act_handle
-- ----------------------------
INSERT INTO `fg_share_act_handle` VALUES ('7', '555', '555', '556,', '2157', '1', '0');
INSERT INTO `fg_share_act_handle` VALUES ('8', '555', '556', '', '2158', '0', '1');

-- ----------------------------
-- Table structure for fg_share_act_pic
-- ----------------------------
DROP TABLE IF EXISTS `fg_share_act_pic`;
CREATE TABLE `fg_share_act_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `share_act_id` int(11) NOT NULL COMMENT '所属分享活动id',
  `picture_path` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=396 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_share_act_pic
-- ----------------------------
INSERT INTO `fg_share_act_pic` VALUES ('392', '555', 'Public/share_act/555/photos/555-5800c54ad3583.jpg');
INSERT INTO `fg_share_act_pic` VALUES ('393', '555', 'Public/share_act/555/photos/555-5800c54ad3ab8.jpg');
INSERT INTO `fg_share_act_pic` VALUES ('394', '555', 'Public/share_act/555/photos/555-5800c54ad3f80.jpg');
INSERT INTO `fg_share_act_pic` VALUES ('395', '556', 'Public/share_act/556/photos/556-5800c56c4f3b1.jpg');

-- ----------------------------
-- Table structure for fg_team
-- ----------------------------
DROP TABLE IF EXISTS `fg_team`;
CREATE TABLE `fg_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `title` varchar(256) NOT NULL COMMENT '活动主题',
  `intro` varchar(2048) NOT NULL DEFAULT '' COMMENT '活动详情',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `user_id` int(11) NOT NULL COMMENT '创建人id',
  `phone` char(20) NOT NULL COMMENT '发布人电话',
  `num_join` smallint(4) NOT NULL DEFAULT '0' COMMENT '参加人数',
  `num_max` smallint(4) NOT NULL DEFAULT '0' COMMENT '最多参加人数',
  `starttime` int(10) NOT NULL COMMENT '活动开始时间',
  `timelast` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT '活动时长',
  `address` varchar(64) NOT NULL COMMENT '活动地点',
  `group_num` char(20) NOT NULL COMMENT 'QQ讨论组号码',
  `cover` varchar(128) NOT NULL DEFAULT '' COMMENT '活动封面图',
  `logo_id` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='活动表（给陌生人参加）';

-- ----------------------------
-- Records of fg_team
-- ----------------------------
INSERT INTO `fg_team` VALUES ('33', '啦啦啦啦', '小伙伴们欢迎参加啊', '2016-10-14 19:51:50', '2157', '13719543701', '2', '10', '1479123000', '2.0', '江门卓悦桌球', '1067081441', 'Public/team/team_pic/33/33-5800c6d64d146.jpg', '2');
INSERT INTO `fg_team` VALUES ('37', '运动', '明天下午三点半\n我们在江门体育馆一起运动吧\n来打羽毛球，篮球……什么都行\n来放松下呗', '2016-10-14 20:32:09', '2158', '13692190638', '0', '20', '1476516600', '4.0', '江门体育馆', '371048852', 'Public/team/team_pic/37/37-5800d049174fb.jpg', '4');
INSERT INTO `fg_team` VALUES ('38', '密室逃脱', '如果你也喜欢密室逃脱就在17号下午6:30过来玩吧\n地点暂定为江门D区真人密室逃脱(江华店)', '2016-10-14 20:36:49', '2158', '15089837950', '0', '10', '1476700200', '2.0', '江门D区真人密室逃脱(江华店)', '371048852', 'Public/team/team_pic/38/38-5800d161bd42a.jpg', '3');
INSERT INTO `fg_team` VALUES ('39', '节日派对', '双十一了呢\n单身狗们浪起来', '2016-10-14 20:47:01', '2159', '15875064665', '0', '20', '1478845800', '3.0', '江门五邑大学(北门)', '371048852', 'Public/team/team_pic/39/39-5800d3c511b77.jpg', '1');
INSERT INTO `fg_team` VALUES ('40', '精品舞剧《一把酸枣》', '张继钢，男，生于榆次粮店街，中国人民解放军总政治部舞团团长，中国当代著名编导，中国文联第六、第七届全委，中国特殊艺术委员会副主席，北京市舞蹈家协会副主席，国家政府特殊津贴获得者，国家一级导演。 　　十二岁开始从事舞蹈艺术，在山西省歌剧院曾任演员、主要演员、舞蹈编导、舞蹈队队长等职；1990年毕业于北京舞蹈学院编导系；后留校任教，任中国民间舞蹈系二级编导；1992年调入总政歌舞团工作，历任总政歌舞团副团长、团长、总政宣传部副部长；2008年任解放军艺术学院院长；2012年4月任武警政治部副主任；2008年7月晋升少将军衔。国家一级导演；2008年北京奥运会开闭幕式副总导演、残奥会开闭幕式执行总导演；2009年中华人民共和国成立60周年大型音乐舞蹈史诗《复兴之路》总导演。 　　2000年12月1日，张继钢创作的舞剧《野斑马》在上海首演，十四年年后经大雪等青年编导们的努力，《野斑马》从原著而改编，从舞剧而又童话舞剧了。', '2016-10-14 21:15:55', '2158', '17195437001', '48', '25', '1478838600', '4.0', '广州 星海音乐厅', '211254569', 'Public/team/team_pic/40/40-5800da8b0f41f.jpg', '5');

-- ----------------------------
-- Table structure for fg_team_comment
-- ----------------------------
DROP TABLE IF EXISTS `fg_team_comment`;
CREATE TABLE `fg_team_comment` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `team_id` int(11) NOT NULL COMMENT '活动id',
  `content` varchar(512) NOT NULL DEFAULT '' COMMENT '评论内容',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  `user_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动留言表';

-- ----------------------------
-- Records of fg_team_comment
-- ----------------------------

-- ----------------------------
-- Table structure for fg_team_join
-- ----------------------------
DROP TABLE IF EXISTS `fg_team_join`;
CREATE TABLE `fg_team_join` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `team_id` int(11) NOT NULL COMMENT '活动id',
  `team_user_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `expect_score` tinyint(4) NOT NULL COMMENT '期待值',
  `satisfy_score` tinyint(4) NOT NULL DEFAULT '0' COMMENT '满意值',
  `phone` char(15) NOT NULL DEFAULT '',
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '发布人是否已接收,0-否，1-是',
  `is_share` tinyint(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 COMMENT='参加活动情况表';

-- ----------------------------
-- Records of fg_team_join
-- ----------------------------
INSERT INTO `fg_team_join` VALUES ('76', '33', '2157', '2157', '100', '0', '', '1', '0');
INSERT INTO `fg_team_join` VALUES ('77', '33', '2157', '2158', '80', '0', '13692190638', '1', '0');
INSERT INTO `fg_team_join` VALUES ('78', '37', '2158', '2158', '100', '0', '', '1', '0');
INSERT INTO `fg_team_join` VALUES ('79', '38', '2158', '2158', '100', '0', '', '1', '0');
INSERT INTO `fg_team_join` VALUES ('80', '39', '2159', '2159', '100', '0', '', '1', '0');
INSERT INTO `fg_team_join` VALUES ('81', '40', '2158', '2158', '44', '0', '', '1', '0');
INSERT INTO `fg_team_join` VALUES ('82', '40', '2158', '2157', '89', '0', '13692190638', '0', '0');

-- ----------------------------
-- Table structure for fg_team_join_not_user
-- ----------------------------
DROP TABLE IF EXISTS `fg_team_join_not_user`;
CREATE TABLE `fg_team_join_not_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长id',
  `team_id` int(11) NOT NULL COMMENT '活动id',
  `team_user_id` int(11) NOT NULL,
  `username` varchar(24) NOT NULL COMMENT '用户id',
  `head_path` char(60) NOT NULL COMMENT '头像',
  `sex` char(1) NOT NULL COMMENT '性别',
  `phone` char(20) NOT NULL COMMENT '手机号',
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '发布人是否已接收,0-否，1-是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='未注册用户参加活动情况表';

-- ----------------------------
-- Records of fg_team_join_not_user
-- ----------------------------
INSERT INTO `fg_team_join_not_user` VALUES ('27', '40', '2158', '何东强', 'Public/team/notuser_head/dd-57ff7eacbd8b9.jpg', 'm', '1719543700', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('28', '40', '2158', 'Clanner', 'Public/team/notuser_head/Clanner-580104b751a9d.jpg', 'f', '13692190638', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('29', '40', '2158', '淳杰', 'Public/team/notuser_head/淳杰-580105f6c4b7d.PNG', 'f', '15989477988', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('30', '40', '2158', 'zero', 'Public/team/notuser_head/zero-5806cd2d71680.jpg', 'm', '13424965402', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('31', '40', '2158', 'zero', 'Public/team/notuser_head/zero-5806cd3368b1e.jpg', 'm', '13424965402', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('32', '40', '2158', '爸爸', 'Public/team/notuser_head/爸爸-5806cda9a32ea.jpg', 'f', '111', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('33', '40', '2158', '爸爸', 'Public/team/notuser_head/爸爸-5806ce42b50b0.JPG', 'f', '13692658343', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('34', '40', '2158', 'benson', 'Public/team/notuser_head/benson-5806d4067e89e.jpg', 'f', '18219111780', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('35', '40', '2158', 'benson', 'Public/team/notuser_head/benson-5806d429e519c.jpg', 'f', '18219111780', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('36', '40', '2158', 'GG', 'Public/team/notuser_head/GG-5806d504028ed.png', 'f', '18718253968', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('37', '40', '2158', 'qqq', 'Public/team/notuser_head/qqq-5806dbf8e6d29.jpg', 'f', '1111', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('38', '40', '2158', 'ganga', 'Public/team/notuser_head/ganga-5806dccc7b534.png', 'f', '18718253968', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('39', '40', '2158', 'chanpikyi', 'Public/team/notuser_head/chanpikyi-5806f4a8ba27e.png', 'm', '13672985211', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('40', '40', '2158', '快乐', 'Public/team/notuser_head/快乐-58070a720a38e.png', 'f', '1111', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('41', '40', '2158', '快乐', 'Public/team/notuser_head/快乐-58070a7314f21.png', 'f', '1111', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('42', '40', '2158', 'qqq', 'Public/team/notuser_head/qqq-58073ef467511.JPG', 'f', '15815', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('43', '40', '2158', '123', 'Public/team/notuser_head/123-5807413cb4826.JPG', 'f', '123', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('44', '40', '2158', '123', 'Public/team/notuser_head/123-5807413e1098d.JPG', 'f', '123', '0');
INSERT INTO `fg_team_join_not_user` VALUES ('45', '40', '2158', '志伟', 'Public/team/notuser_head/志伟-581ac53125f67.jpg', 'f', '15875064665', '0');

-- ----------------------------
-- Table structure for fg_team_pic
-- ----------------------------
DROP TABLE IF EXISTS `fg_team_pic`;
CREATE TABLE `fg_team_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL COMMENT '活动id',
  `picture` varchar(128) NOT NULL DEFAULT '' COMMENT '活动图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT='活动图片表';

-- ----------------------------
-- Records of fg_team_pic
-- ----------------------------
INSERT INTO `fg_team_pic` VALUES ('61', '33', 'Public/team/team_pic/33/33-5800c6d64d60d.jpg');
INSERT INTO `fg_team_pic` VALUES ('62', '33', 'Public/team/team_pic/33/33-5800c6d64db89.jpg');
INSERT INTO `fg_team_pic` VALUES ('63', '38', 'Public/team/team_pic/38/38-5800d161bda7d.jpg');
INSERT INTO `fg_team_pic` VALUES ('64', '39', 'Public/team/team_pic/39/39-5800d3c51209b.jpg');
INSERT INTO `fg_team_pic` VALUES ('65', '39', 'Public/team/team_pic/39/39-5800d3c5124e4.jpg');

-- ----------------------------
-- Table structure for fg_user
-- ----------------------------
DROP TABLE IF EXISTS `fg_user`;
CREATE TABLE `fg_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `phone` char(20) NOT NULL,
  `password` char(32) NOT NULL,
  `username` varchar(24) DEFAULT '',
  `head_path` char(60) DEFAULT '',
  `sex` char(1) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2160 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_user
-- ----------------------------
INSERT INTO `fg_user` VALUES ('2157', '15875064665', '96e79218965eb72c92a549dd5a330112', '何志伟', 'Public/user/2157/user_head/2157-58115a24b18b5.jpg', 'm');
INSERT INTO `fg_user` VALUES ('2158', '13719543701', '96e79218965eb72c92a549dd5a330112', '吴秋婉', 'Public/user/2158/user_head/2158-57bbfafdb0177.jpg', 'f');
INSERT INTO `fg_user` VALUES ('2159', '13719543702', '96e79218965eb72c92a549dd5a330112', '何东强', 'Public/user/2159/user_head/2159-57bbfb0b27cdf.jpg', 'm');

-- ----------------------------
-- Table structure for fg_user_intro
-- ----------------------------
DROP TABLE IF EXISTS `fg_user_intro`;
CREATE TABLE `fg_user_intro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `birth` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生日',
  `resident` varchar(50) NOT NULL DEFAULT '' COMMENT '居住地',
  `profession` varchar(50) NOT NULL DEFAULT '职业',
  `constellation` varchar(10) NOT NULL DEFAULT '' COMMENT '星座',
  `blood_group` varchar(5) NOT NULL DEFAULT '' COMMENT '血型',
  `self_intro` varchar(255) NOT NULL DEFAULT '' COMMENT '个人简介',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fg_user_intro
-- ----------------------------
INSERT INTO `fg_user_intro` VALUES ('4', '2157', '2016-11-07 00:00:00', '五邑大学玫瑰园', '好学生', '摩羯座', 'A', '志伟是');
INSERT INTO `fg_user_intro` VALUES ('5', '2158', '2016-11-07 15:06:58', '', '职业', '', '', '');
SET FOREIGN_KEY_CHECKS=1;
