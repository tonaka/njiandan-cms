<?php defined('SYSPATH') OR die('No direct access allowed.');

class Editor {

	
    public static function fck_editor($args = array()) {
        $default_args = array('width'=>0 ,'height'=>0, 'name'=>'', 'value'=>'');
        $args += $default_args;
        require DOCROOT . SUBDIRECTORY . '/modules/njiandan/webroot/js/fckeditor/fckeditor.php';
        $sBasePath = url::base() . SUBDIRECTORY . '/modules/njiandan/webroot/js/fckeditor/';
        $oFCKeditor = new FCKeditor($args['name']);
        $oFCKeditor->BasePath = $sBasePath;
        $oFCKeditor->Width = $args['width'];
        $oFCKeditor->Height= $args['height'];
        $oFCKeditor->Value = $args['value'];
        $oFCKeditor->Create();
    }
	

	public static function baidu_editor($args = array()) {
        $default_args = array('width'=>0 ,'height'=>0, 'name'=>'', 'value'=>'');
        $args += $default_args;
        
		if (SUBDIRECTORY) {
			$html = "<script type='text/javascript'>var BAIDUEDITFILEURL='".SUBDIRECTORY."'; var BAIDUEDITORURL = '". url::base() ."njiandan/modules/njiandan/webroot/js/ueditor/';</script>";
			$html .= "<script type='text/javascript' src='".url::base()."njiandan/modules/njiandan/webroot/js/ueditor/editor_config.js'></script>\n";
			$html .= "<script type='text/javascript' src='".url::base()."njiandan/modules/njiandan/webroot/js/ueditor/editor_all_min.js'></script>\n";
			$html .= "<link rel='stylesheet' href='".url::base()."njiandan/modules/njiandan/webroot/js/ueditor/themes/default/css/ueditor.css'>\n";

			$html .= "<script type='text/plain' id='".$args['name']."' name='".$args['name']."'>".$args['value']."</script>\n";
			$html .= "<script type='text/javascript'>\n"; 
			$html .= "UE.getEditor('".$args['name']."', {initialFrameHeight:".$args['height']."});\n";
			$html .= "</script>";
		} else {
			$html = "<script type='text/javascript'>var BAIDUEDITFILEURL=''; var BAIDUEDITORURL = '". url::base() ."njiandan/modules/njiandan/webroot/js/ueditor/';</script>";
			$html .= "<script type='text/javascript' src='".url::base()."njiandan/modules/njiandan/webroot/js/ueditor/editor_config.js'></script>\n";
			$html .= "<script type='text/javascript' src='".url::base()."njiandan/modules/njiandan/webroot/js/ueditor/editor_all_min.js'></script>\n";
			$html .= "<link rel='stylesheet' href='".url::base()."njiandan/modules/njiandan/webroot/js/ueditor/themes/default/css/ueditor.css'>\n";

			$html .= "<script type='text/plain' id='".$args['name']."' name='".$args['name']."'>".$args['value']."</script>\n";
			$html .= "<script type='text/javascript'>\n"; 
			$html .= "UE.getEditor('".$args['name']."', {initialFrameHeight:".$args['height']."});\n";
			$html .= "</script>";
		}

		echo $html;
    }

}
