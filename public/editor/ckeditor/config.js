/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'zh-cn';
	// config.uiColor = '#AADC6E';
	config.height = 400;
	config.width = 900;
	//编辑器样式，有三种：'kama'（默认）、'office2003'、'v2'
	//config.skin = 'office2003';
	// 背景颜色
	//config.uiColor = '#CCC';
	// 工具栏（基础'Basic'、全能'Full'、自定义）plugins/toolbar/plugin.js
	//config.toolbar = 'Basic';
	//定制工具栏
	//config.toolbar = 'Full';
	/*config.toolbar_Full = [

	           ['Source','-','Save','NewPage','Preview','-','Templates'],

	           ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],

	           ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],

	           ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],

	              '/',

	           ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],

	           ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],

	           ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

	           ['Link','Unlink','Anchor'],

	           ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],

	           '/',

	           ['Styles','Format','Font','FontSize'],

	           ['TextColor','BGColor']

	        ];
	        */
	//工具栏是否可以被收缩
	config.toolbarCanCollapse = true;
	//工具栏的位置
	config.toolbarLocation = 'top';//可选：bottom
	//工具栏默认是否展开 默认展开
	config.toolbarStartupExpanded = true;
	// 取消 “拖拽以改变尺寸”功能  默认为 true
	config.resize_enabled = true;
	
	//改变大小的最大高度
	//config.resize_maxHeight = 100;
	//改变大小的最大宽度
	//config.resize_maxWidth = 500;
	
	//改变大小的最小高度
	//config.resize_minHeight = 250;
	//改变大小的最小宽度
	//config.resize_minWidth = 750;
	
	// 当提交包含有此编辑器的表单时，是否自动更新元素内的数据
	//config.autoUpdateElement = true;
	//设置是使用绝对目录还是相对目录，为空为相对目录
	//config.baseHref = '';
	// 编辑器的z-index值
	//config.baseFloatZIndex = 100;
	//设置快捷键
    config.keystrokes = [

       [ CKEDITOR.ALT + 121 /*F10*/, 'toolbarFocus' ], //获取焦点

        [ CKEDITOR.ALT + 122 /*F11*/, 'elementsPathFocus' ], //元素焦点

       [ CKEDITOR.SHIFT + 121 /*F10*/, 'contextMenu' ], //文本菜单

       [ CKEDITOR.CTRL + 90 /*Z*/, 'undo' ], //撤销

        [ CKEDITOR.CTRL + 89 /*Y*/, 'redo' ], //重做

        [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 90 /*Z*/, 'redo' ], //

        [ CKEDITOR.CTRL + 76 /*L*/, 'link' ], //链接

        [ CKEDITOR.CTRL + 66 /*B*/, 'bold' ], //粗体

        [ CKEDITOR.CTRL + 73 /*I*/, 'italic' ], //斜体

        [ CKEDITOR.CTRL + 85 /*U*/, 'underline' ], //下划线

        [ CKEDITOR.ALT + 109 /*-*/, 'toolbarCollapse' ]

    ]
    
    //设置快捷键 可能与浏览器快捷键冲突 plugins/keystrokes/plugin.js.
    config.blockedKeystrokes = [

        CKEDITOR.CTRL + 66 /*B*/,

        CKEDITOR.CTRL + 73 /*I*/,

        CKEDITOR.CTRL + 85 /*U*/
     ]
    
    //设置编辑内元素的背景色的取值 plugins/colorbutton/plugin.js.
    /*config.colorButton_backStyle = {

        element : 'p',

        styles : { 'background-color' : '#CCCC' }

    }*/
    config.font_names = '宋体;楷体_GB2312;新宋体;黑体;隶书;幼圆;微软雅黑;Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana;"Lucida Sans Unicode", "Lucida Grande"';
    config.filebrowserBrowseUrl = '/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = '/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=File';
    config.filebrowserImageUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	
};












