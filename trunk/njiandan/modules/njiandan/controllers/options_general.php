<?php defined('SYSPATH') OR die('No direct access allowed.');

class Options_General_Controller extends Controller {

    public function index() {
        $site_url_error = '';
        $site_title = Kohana::config('njiandan.site_title');
        $site_description = Kohana::config('njiandan.site_description');
        $site_url = Kohana::config('njiandan.site_url');
        $default_language = Kohana::config('njiandan.default_language');
        $space_size = Kohana::config('njiandan.space_size');
        $database_size = Kohana::config('njiandan.database_size');
        $upload_max_filesize = Kohana::config('njiandan.upload_max_filesize');
        $editor_width = Kohana::config('njiandan.editor_width');
        $editor_height = Kohana::config('njiandan.editor_height');

        if ($_POST) {
            // check is use have role
            if (!$this->user->can('edit_options_general')) {
                die(T::_('You are not access allowed.'));
            }

            $site_url = trim($this->input->post('site_url'), ' /');

            if (!valid::url($site_url)) {
                $site_url_error = T::_('Url is invalid.');
            }

            if (empty($site_url_error)) {
                Njiandan::config_save('njiandan.site_title', trim($this->input->post('site_title')));
                Njiandan::config_save('njiandan.site_description', trim($this->input->post('site_description')));
                Njiandan::config_save('njiandan.site_url', $site_url);
                Njiandan::config_save('njiandan.default_language', $this->input->post('default_language'));
                Njiandan::config_save('njiandan.space_size', $this->input->post('space_size'));
                Njiandan::config_save('njiandan.database_size', $this->input->post('database_size'));
                Njiandan::config_save('njiandan.upload_max_filesize', $this->input->post('upload_max_filesize'));
                Njiandan::config_save('njiandan.editor_width', $this->input->post('editor_width'));
                Njiandan::config_save('njiandan.editor_height', $this->input->post('editor_height'));
                Njiandan::config_save('njiandan.default_date_format', $this->input->post('default_date_format'));
                Tip::set('Options saved.');
                url::admin_redirect('options_general');
            }
        }

        $view = new View('layouts/admin');
        $view->page_title = 'Options General';
        $view->site_title = $site_title;
        $view->site_description = $site_description;
        $view->site_url = $site_url;
        $view->site_url_error = $site_url_error;
        $view->languages = T::get_languages();
        $view->default_language = $default_language;
        $view->space_size = $space_size;
        $view->database_size = $database_size;
        $view->upload_max_filesize = $upload_max_filesize;
        $view->editor_width = $editor_width;
        $view->editor_height = $editor_height;

        $date_formats = date::date_formats();
        $formats = array();
        foreach($date_formats as $key => $value) {
            if (isset($value[2])) {
                $formats[$key] = $value[2];
            }
        }
        $view->date_formats = $formats;
        $view->default_date_format = Kohana::config('njiandan.default_date_format');
        $view->render(true);
    }
}
