-- phpMyAdmin SQL Dump
-- version 4.3.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2017 at 11:35 AM
-- Server version: 5.6.23
-- PHP Version: 5.6.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gitlibs_cp`
--

-- --------------------------------------------------------

--
-- Table structure for table `rbac_action`
--

CREATE TABLE IF NOT EXISTS `rbac_action` (
  `actionid` int(11) NOT NULL COMMENT '动作id',
  `classid` varchar(32) NOT NULL DEFAULT '' COMMENT '资源id，即类id',
  `functionid` varchar(32) NOT NULL DEFAULT '' COMMENT '操作方法id',
  `actionname` varchar(64) NOT NULL DEFAULT '' COMMENT '权限名称',
  `ismenu` varchar(32) NOT NULL DEFAULT '' COMMENT '是否显示成菜单，父class值，为空不显示菜单'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- Dumping data for table `rbac_action`
--

INSERT INTO `rbac_action` (`actionid`, `classid`, `functionid`, `actionname`, `ismenu`) VALUES
(1, 'action', 'view', '功能权限', 'action'),
(2, 'log', 'view', '浏览日志', 'action'),
(3, 'role', 'view', '浏览角色', 'master'),
(4, 'master', 'view', '浏览用户', 'master'),
(8, 'action', 'add', '添加权限', ''),
(9, 'action', 'edit', '编辑权限', ''),
(10, 'action', 'delete', '删除权限', ''),
(11, 'log', 'viewdetail', '查看日志详情', ''),
(12, 'role', 'add', '添加角色', ''),
(13, 'role', 'edit', '编辑角色', ''),
(14, 'role', 'delete', '删除角色', ''),
(15, 'role', 'viewdetail', '查看角色详情', ''),
(16, 'master', 'add', '添加用户', ''),
(17, 'master', 'edit', '编辑用户', ''),
(18, 'master', 'enable', '禁用（启用）用户', ''),
(19, 'master', 'viewdetail', '查看用户详情', '');

-- --------------------------------------------------------

--
-- Table structure for table `rbac_actionrole`
--

CREATE TABLE IF NOT EXISTS `rbac_actionrole` (
  `actionroleid` int(10) unsigned NOT NULL,
  `actionid` int(10) unsigned DEFAULT '0' COMMENT '动作id',
  `roleid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `creatorid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建者id',
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间'
) ENGINE=InnoDB AUTO_INCREMENT=655 DEFAULT CHARSET=utf8 COMMENT='动作角色对应表';

--
-- Dumping data for table `rbac_actionrole`
--

INSERT INTO `rbac_actionrole` (`actionroleid`, `actionid`, `roleid`, `creatorid`, `createtime`) VALUES
(617, 16, 1, 1, '2016-07-29 11:05:55'),
(618, 17, 1, 1, '2016-07-29 11:05:55'),
(619, 18, 1, 1, '2016-07-29 11:05:55'),
(620, 4, 1, 1, '2016-07-29 11:05:55'),
(621, 19, 1, 1, '2016-07-29 11:05:55'),
(622, 8, 1, 1, '2016-07-29 11:05:55'),
(623, 10, 1, 1, '2016-07-29 11:05:55'),
(624, 9, 1, 1, '2016-07-29 11:05:55'),
(625, 1, 1, 1, '2016-07-29 11:05:55'),
(626, 12, 1, 1, '2016-07-29 11:05:55'),
(627, 14, 1, 1, '2016-07-29 11:05:55'),
(628, 13, 1, 1, '2016-07-29 11:05:55'),
(629, 3, 1, 1, '2016-07-29 11:05:55'),
(630, 15, 1, 1, '2016-07-29 11:05:55'),
(632, 2, 1, 1, '2016-07-29 11:05:55'),
(633, 11, 1, 1, '2016-07-29 11:05:55');

-- --------------------------------------------------------

--
-- Table structure for table `rbac_log`
--

CREATE TABLE IF NOT EXISTS `rbac_log` (
  `logid` int(10) unsigned NOT NULL,
  `mastername` varchar(32) NOT NULL DEFAULT '' COMMENT '操作用户名',
  `actionid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '动作id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '操作URL',
  `get` text NOT NULL COMMENT 'get方式提交的内容',
  `post` text NOT NULL COMMENT 'post方式提交的内容',
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作日志';

-- --------------------------------------------------------

--
-- Table structure for table `rbac_master`
--

CREATE TABLE IF NOT EXISTS `rbac_master` (
  `masterid` int(10) unsigned NOT NULL,
  `cityid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市id',
  `mastername` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `masterpwd` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码',
  `fullname` varchar(64) NOT NULL DEFAULT '' COMMENT '姓名',
  `master_sex` tinyint(3) NOT NULL DEFAULT '0' COMMENT '性别(1:男;2:女)',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(128) NOT NULL DEFAULT '' COMMENT '邮箱',
  `deptname` varchar(255) NOT NULL DEFAULT '' COMMENT '部门名称',
  `creatorid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建者id',
  `errorlogintimes` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '错误登录次数',
  `lastlogintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上次登录时间',
  `thislogintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '本次登录时间',
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态：-1删除，0禁用，1启用'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户';

--
-- Dumping data for table `rbac_master`
--

INSERT INTO `rbac_master` (`masterid`, `cityid`, `mastername`, `masterpwd`, `fullname`, `master_sex`, `mobile`, `email`, `deptname`, `creatorid`, `errorlogintimes`, `lastlogintime`, `thislogintime`, `createtime`, `updatetime`, `status`) VALUES
(1, 0, 'ceshi', '827ccb0eea8a706c4c34a16891f84e7b', '测试', 1, '13800138000', 'ceshi@126.com', '研发部', 1, 0, '2017-03-20 11:10:40', '2017-03-20 11:17:33', '2016-06-23 00:00:00', '2017-03-20 03:17:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rbac_masterrole`
--

CREATE TABLE IF NOT EXISTS `rbac_masterrole` (
  `masterroleid` int(10) unsigned NOT NULL,
  `masterid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `roleid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `creatorid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建者id',
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户角色对应关系表';

--
-- Dumping data for table `rbac_masterrole`
--

INSERT INTO `rbac_masterrole` (`masterroleid`, `masterid`, `roleid`, `creatorid`, `createtime`) VALUES
(2, 1, 1, 1, '2016-07-21 14:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `rbac_resrole`
--

CREATE TABLE IF NOT EXISTS `rbac_resrole` (
  `resroleid` int(11) NOT NULL,
  `restype` varchar(32) NOT NULL DEFAULT '' COMMENT '大区|城市|资源',
  `resid` int(11) NOT NULL DEFAULT '0' COMMENT 'type对应值',
  `roleid` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资源角色表';

-- --------------------------------------------------------

--
-- Table structure for table `rbac_role`
--

CREATE TABLE IF NOT EXISTS `rbac_role` (
  `roleid` int(11) unsigned NOT NULL COMMENT '角色ID',
  `rolename` varchar(64) NOT NULL DEFAULT '' COMMENT '角色名称',
  `roleinfo` varchar(255) NOT NULL DEFAULT '' COMMENT '角色备注信息',
  `creatorid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建者id',
  `createtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后更新时间'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- Dumping data for table `rbac_role`
--

INSERT INTO `rbac_role` (`roleid`, `rolename`, `roleinfo`, `creatorid`, `createtime`, `updatetime`) VALUES
(1, '超级管理员', '', 1, '2016-06-23 00:00:00', '2016-07-29 03:05:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rbac_action`
--
ALTER TABLE `rbac_action`
  ADD PRIMARY KEY (`actionid`), ADD UNIQUE KEY `classid` (`classid`,`functionid`);

--
-- Indexes for table `rbac_actionrole`
--
ALTER TABLE `rbac_actionrole`
  ADD PRIMARY KEY (`actionroleid`), ADD KEY `actionid` (`actionid`,`roleid`);

--
-- Indexes for table `rbac_log`
--
ALTER TABLE `rbac_log`
  ADD PRIMARY KEY (`logid`), ADD KEY `mastername` (`mastername`), ADD KEY `url` (`url`);

--
-- Indexes for table `rbac_master`
--
ALTER TABLE `rbac_master`
  ADD PRIMARY KEY (`masterid`), ADD UNIQUE KEY `mastername` (`mastername`), ADD KEY `bigareaid` (`cityid`), ADD KEY `fullname` (`fullname`);

--
-- Indexes for table `rbac_masterrole`
--
ALTER TABLE `rbac_masterrole`
  ADD PRIMARY KEY (`masterroleid`), ADD KEY `masterid` (`masterid`,`roleid`);

--
-- Indexes for table `rbac_resrole`
--
ALTER TABLE `rbac_resrole`
  ADD PRIMARY KEY (`resroleid`), ADD KEY `type` (`restype`), ADD KEY `resid` (`resid`), ADD KEY `roleid` (`roleid`);

--
-- Indexes for table `rbac_role`
--
ALTER TABLE `rbac_role`
  ADD PRIMARY KEY (`roleid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rbac_action`
--
ALTER TABLE `rbac_action`
  MODIFY `actionid` int(11) NOT NULL AUTO_INCREMENT COMMENT '动作id',AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `rbac_actionrole`
--
ALTER TABLE `rbac_actionrole`
  MODIFY `actionroleid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=655;
--
-- AUTO_INCREMENT for table `rbac_log`
--
ALTER TABLE `rbac_log`
  MODIFY `logid` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rbac_master`
--
ALTER TABLE `rbac_master`
  MODIFY `masterid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rbac_masterrole`
--
ALTER TABLE `rbac_masterrole`
  MODIFY `masterroleid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rbac_resrole`
--
ALTER TABLE `rbac_resrole`
  MODIFY `resroleid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rbac_role`
--
ALTER TABLE `rbac_role`
  MODIFY `roleid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
