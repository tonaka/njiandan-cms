<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * diagram customfields list
 */
 
class CustomField_core {
    static $types = array
    (
        'input'=>'An HTML form input tag',
        'textarea'=>'An HTML form textarea tag',
        'upload'=>'Input tag type file for upload files',
        'dropdown'=>'A drop down selection box',
        'multiple'=>'A  multiple selection box',
        'checkbox'=>'A tick box type selection box',
        'radio'=>'A radio type selection box',
    );

    public static function types() {
        $results = array();
        foreach(self::$types as $key => $type) {
            $results[$key] = T::_($type);
        }
        return $results;
    }

    public static function form($diagram_id, $post_id = 0, $diagram = false) {
        $output = '';
        $fields = ORM::factory('customfield')->where('diagram_id', $diagram_id)->find_all();
        if (empty($fields)) {
            return $output;
        }

        foreach($fields as $field) {
            if ($diagram) {
                $meta = ORM::factory('customvalue')->where(array('diagram_id'=>$diagram_id, 'key'=>$field->key))->find();
            } else {
                $meta = ORM::factory('customvalue')->where(array('post_id'=>$post_id, 'key'=>$field->key))->find();
            }
            $output .= '<tr class="finalrow_customfields"><th>' . $field->title . '</th><td>';
            $key = "Customvalues_$field->key";
            switch($field->type) {
                case 'input':
                    // set input attribute
                    $args = array('name'=>$key);
                    // if is set size
                    preg_match("/(size\[)(\d+)(])/", $field->metavalue, $matches);
                    if (isset($matches[2])) {
                        $args['size'] = trim($matches[2]);
                    }

                    // if is set maxlength
                    preg_match("/(maxlength\[)(\d+)(])/", $field->metavalue, $matches);
                    if (isset($matches[2])) {
                        $args['maxlength'] = trim($matches[2]);
                    }

                    if (empty($meta->id)) {
                        // if is first time , check whether set default value
                        preg_match("/(value\[)(.+)(])/", $field->metavalue, $matches);
                        if (isset($matches[2])) {
                            $args['value'] = trim($matches[2]);
                        }
                    } else {
                        $args['value'] = $meta->value;
                    }

                    $output .= form::input($args);
                    break;
                case 'textarea':
                    // set textarea attribute
                    $args = array('name'=>$key);
                    // if is set rows
                    preg_match("/(rows\[)(\d+)(])/", $field->metavalue, $matches);
                    if (isset($matches[2])) {
                        $args['rows'] = trim($matches[2]);
                    }
                    // if is set cols
                    preg_match("/(cols\[)(\d+)(])/", $field->metavalue, $matches);
                    if (isset($matches[2])) {
                        $args['cols'] = trim($matches[2]);
                    }

                    if (empty($meta->id)) {
                        // if is first time , check whether set default value
                        preg_match("/(value\[)(.+)(])/", $field->metavalue, $matches);
                        if (isset($matches[2])) {
                            $args['value'] = trim($matches[2]);
                        }
                    } else {
                        $args['value'] = $meta->value;
                    }
                    $output .= form::textarea($args);
                    break;
                case 'upload':
                    if (!empty($meta->attachment->filename)) {
                        if (strpos($meta->attachment->mime, 'image') !== False) {
                            $title = html::image($meta->attachment->filename, array('height'=>50, 'width'=>100));
                        } else {
                            $title = $meta->attachment->title;
                        }
                        $output .= form::upload($key, '', 'style="display:none;" class="upload_' . $key . '"') . "<span class=\"meta_$key\">" . $title . "</span> <a href=\"#\" onclick=\"$('.meta_$key').hide();$('.upload_$key').show();$('#delete_$key').val('yes');return false;\" class=\"meta_$key\">" . T::_('Delete and upload again') . "</a> <a href=\"#\" onclick=\"$('.upload_$key').hide();$('.meta_$key').show();$('#$key').val('');$('#delete_$key').val('');return false;\" style=\"display:none;\" class=\"upload_$key\">" . T::_('Cancel delete') . "</a><input type=\"hidden\" name=\"delete_$key\" id=\"delete_$key\">";
                    } else {
                        $output .= form::upload($key, $meta->value);
                    }
                    break;
                case 'checkbox':
                    $output .= form::checkbox($key, 'yes', $meta->value);
                    break;
                case 'radio':
                    $radios = array();
                    if (!empty($field->metavalue)) {
                        $radios = explode(',', $field->metavalue);
                    }
                    if (!empty($radios)) {
                        foreach($radios as $count=>$radio) {
                            if ($meta->value == $radio) {
                                $selected = True;
                            } else {
                                $selected = False;
                            }
                            $output .= form::radio($key, $radio, $selected, "id=\"{$field->key}_{$count}\"").form::label("{$field->key}_{$count}", $radio);
                        }
                    }
                    break;
                case 'dropdown':
                case 'multiple':
                    $options = array();
                    $results = array();
                    $extra = '';
                    $selected = $meta->value;
                    if ($field->type == 'multiple') {
                        $extra = 'multiple';
                        $key = $key . '[]';
                        $selected = unserialize($meta->value);
                    }
                    if (!empty($field->metavalue)) {
                        $options = explode(',', $field->metavalue);
                    }
                    if (!empty($options)) {
                        foreach($options as $option) {
                            $results[$option] = $option;
                        }
                        $output .= form::dropdown($key, $results, $selected, $extra);
                    }
                    break;
            }
            $output .= '</td></tr>';
        }
        return $output;
    }

    public static function save($data, $diagram_id, $post_id = 0) {
        $fields = ORM::factory('customfield')->where('diagram_id', $diagram_id)->find_all();
        if (empty($fields)) {
            return null;
        }

        foreach($fields as $field) {
            // if not empty post id ,save type is post
            if (!empty($post_id)) {
                $customvalue = ORM::factory('customvalue')->where(array('post_id'=>$post_id, 'key'=>$field->key))->find();
                $customvalue->post_id = $post_id;
                $customvalue->diagram_id = 0;
              // if empty post id save value type is diagram
            } else {
                $customvalue = ORM::factory('customvalue')->where(array('diagram_id'=>$diagram_id, 'key'=>$field->key))->find();
                $customvalue->diagram_id = $diagram_id;
                $customvalue->post_id = 0;
            }
            $key = "Customvalues_$field->key";
            // if it is no value, not save, if file add new, and empty, not save
            if ((!isset($data[$key]) and $field->type != 'upload') or ($field->type == 'upload' and empty($_FILES[$key]['name']) and empty($customvalue->id))) {
                // if edit
                if (!empty($customvalue->id)) {
                    $customvalue->value = '';
                    $customvalue->save();
                }
                continue;
            }

            switch($field->type) {
                case 'input':
                case 'textarea':
                case 'checkbox':
                case 'radio':
                case 'dropdown':
                    $customvalue->value = trim($data[$key]);
                    break;
                case 'multiple':
                    $customvalue->value = serialize($data[$key]);
                    break;
                case 'upload':
                    if (isset($data["delete_$key"]) and $data["delete_$key"] == 'yes') {
                        upload::delete($customvalue->value);
                        $attachment = ORM::factory('attachment')->where('filename', $customvalue->value)->find();
                        if (!empty($attachment->id)) {
                            $attachment->delete();
                        }
                        $customvalue->value = '';
                    }
                    // Validation the upload file
                    $image_type = '';
                    preg_match("/type\[[^]]+\]/i", $field->metavalue, $matches);
                    if (isset($matches[0])) {
                        $image_type = 'upload::' . trim($matches[0]);
                    }
                    $file_size = '';
                    preg_match("/size\[[0-9kbmg]+\]/i", $field->metavalue, $matches);

                    if (isset($matches[0])) {
                        $file_size = 'upload::' . trim($matches[0]);
                    } else {
                        $file_size = 'upload::size[' . Kohana::config('njiandan.upload_max_filesize') . ']';
                    }

                    if (empty($image_type)) {
                        $files = Validation::factory($_FILES)->add_rules($key, 'upload::valid', 'upload::required', $file_size);
                    } else {
                        $files = Validation::factory($_FILES)->add_rules($key, 'upload::valid', 'upload::required', $image_type, $file_size);
                    }

                    if ($files->validate()) {
	                    if ($filename = upload::save($key)) {
	                        $attach = new Attachment_Model();
	                        $attach->post_id = $customvalue->post_id;
	                        $attach->diagram_id = $customvalue->diagram_id;
	                        $attach->title = $_FILES[$key]['name'];
	                        $attach->filename = $filename;
	                        $attach->is_thumb = 0;
	                        $attach->downloads = 0;
	                        $attach->date = time();
	                        $file_path = DOCROOT . $filename;
	                        $attach->size = filesize($file_path);
	                        $attach->mime = file::mime($file_path);
	                        $attach->save();
	                        $customvalue->value = $filename;
	                    }
	                }
                    break;
            }

            $customvalue->customfield_id = $field->id;
            $customvalue->key = $field->key;
            $customvalue->save();
            unset($customvalue);
        }
    }
}
