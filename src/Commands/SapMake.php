<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SapMake extends Command
{
    protected $signature = 'sap:make {model} {--force}';
    protected $description = 'Make Up The CRUD From Config';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->stub_path = config('sap.stub_path');
    }

    public function handle()
    {
        $this->model = $this->argument('model');
        $config_file = config_path('sap/'.$this->model.'Config.php');
        if (!$this->files->exists($config_file)) {
            $this->error('Config file not found: <info>'.$config_file.'</info>');

            return;
        }
        $this->config = include $config_file;
        if (!$this->config['ready']) {
            if (false == $this->option('force')) {
                $this->error('Config file not ready: <info>'.$config_file.'</info>');

                return;
            }
        }
        $this->line('Config <info>'.$this->model.'</info> Found! Initiating!');
        $this->initReplacer();
        $this->reinstate();

        $config_content = $this->files->get($config_file);
        $config_content = str_replace("'ready' => true,", "'ready' => false,", $config_content);
        $this->files->put($config_file, $config_content);
        $this->line('<info>Since you had done make the CRUD, we will help you set ready to false to prevent accidentally make after you have done all your changes in your flow!</info>');
        $this->line('Config has changed: <info>'.$config_file.'</info>');

        $this->alert("Now remember to run php artisan ziggy:generate resources/js/ziggy.js && npm run production\nafter you have done adjusting your crud component\nor business in your controler & model.");
    }

    protected function initReplacer()
    {
        $this->replaces['{%custom_controller_namespace%}'] = config('sap.custom_controller_namespace');
        $this->replaces['{%custom_api_controller_namespace%}'] = config('sap.custom_api_controller_namespace');

        $this->replaces['{%model%}'] = $this->model;
        $this->replaces['{%model_namespace%}'] = ucfirst(str_replace('/', '\\', config('sap.model_namespace')));
        $this->replaces['{%model_class%}'] = $this->replaces['{%model%}'];
        $this->replaces['{%model_string%}'] = trim(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $this->replaces['{%model%}']));
        $this->replaces['{%model_strings%}'] = str_plural($this->replaces['{%model_string%}']);
        $this->replaces['{%model_variable%}'] = strtolower(str_replace(' ', '_', $this->replaces['{%model_string%}']));
        $this->replaces['{%model_variables%}'] = strtolower(str_replace(' ', '_', $this->replaces['{%model_strings%}']));
        $this->replaces['{%model_%}'] = strtolower(str_replace(' ', '_', $this->replaces['{%model_strings%}']));
        $this->replaces['{%table_name%}'] = $this->replaces['{%model_variables%}'];
        $this->replaces['{%table_declared%}'] = '';
        $this->replaces['{%menu_name%}'] = $this->replaces['{%model_strings%}'];
        $this->replaces['{%menu_icon%}'] = $this->config['menu_icon'];

        if (isset($this->config['table_name']) && '' != $this->config['table_name']) {
            $this->replaces['{%table_name%}'] = $this->config['table_name'];
            $this->replaces['{%table_declared%}'] = "protected \$table = '{$this->config['table_name']}';";
        }
        if (isset($this->config['menu_name']) && '' != $this->config['menu_name']) {
            $this->replaces['{%menu_name%}'] = $this->config['menu_name'];
        }
    }

    protected function reinstate()
    {
        $config_form = $this->config['form'];
        $upload_strings = $model_keys = $setting_keys = $table_fields = $search_scopes = $search_fields = $settings_options_up = $settings_options_down = $read_fields = $form_fields = $validations = $user_timezones = $fillables = $casts = $appends = $mutators = $relationships = $relationships_query = [];

        foreach ($config_form as $field => $options) {
            $this->replaces['{%field_variable%}'] = studly_case($field);
            if (isset($options['migration']) && '' != $options['migration']) {
                $migration = $options['migration'];
                $this->replaces['{%field%}'] = $field;
                $migration_codes[] = $this->replaceholder('$table->'.$this->putInChains($migration).';');
            }
            $fillables[] = "'{$field}'";
            if ('' != $options['casts']) {
                $casts[] = "'{$field}' => '{$options['casts']}'";
            }
            if ($options['list']) {
                $search_boolean_string = $options['search'] ? 'true' : 'false';
                $sort_boolean_string = isset($options['sortable']) && $options['sortable'] ? 'true' : 'false';
                $table_fields[] = "['title' => '{$options['label']}', 'data' => '{$field}', 'sortable' => {$sort_boolean_string}, 'filterable' => {$search_boolean_string}]";
            }
            $scopes = [];
            $searches = [];
            if ($options['search']) {
                $scopes[] = 'public function scopeFilter'.studly_case($field).'($query, $search)';
                $scopes[] = $this->indent().'{';
                $scopes[] = $this->indent().'    return $query->where(\''.$field.'\', \'like\', "%{$search}%");';
                $scopes[] = $this->indent().'}';
                $search_scopes[] = implode(PHP_EOL, $scopes).PHP_EOL;

                // TODO to make this into stub, usually only date range, text and multiple select
                $searches[] = '<div class="form-group">';
                $searches[] = $this->indent().'<label for="created_at">'.$options['label'].'</label>';
                $searches[] = $this->indent().'<input type="text" class="form-control filterInput" name="'.$field.'" id="'.$field.'">';
                $searches[] = $this->indent().'</div>';
                $search_fields[] = implode(PHP_EOL, $searches).PHP_EOL;
            }

            if (!empty($options['relationship'])) {
                $relationships[] = $this->indent().'public function '.array_keys($options['relationship'])[0].'()';
                $relationships[] = $this->indent().'{';
                $relationships[] = $this->indent().'    return $this->'.$this->putInChains(array_values($options['relationship'])[0]).';';
                $relationships[] = $this->indent().'}'.PHP_EOL;
                $relationships_query[] = array_keys($options['relationship'])[0];
            }

            if (!empty($options['user_timezone'])) {
                $user_timezones[] = $this->indent().'public function get'.studly_case($field).'Attribute($value)';
                $user_timezones[] = $this->indent().'{';
                $user_timezones[] = $this->indent().'    return $this->inUserTimezone($value);';
                $user_timezones[] = $this->indent().'}'.PHP_EOL;
            }

            if (!empty($options['validation'])) {
                foreach ($options['validation'] as $method => $rules) {
                    if (isset($options['input']['type']) && 'file' == $options['input']['type']) {
                        $validations[$method][] = $this->indent(3).'"'.$field.'_file" => "'.$rules.'",';
                    } else {
                        $validations[$method][] = $this->indent(3).'"'.$field.'" => "'.$rules.'",';
                    }
                }
            }

            $replace_for_form['{%option_key%}'] = '';
            $select_options = '';
            if (isset($options['model_options']) && '' != $options['model_options']) {
                $replace_for_form['{%model_option_query%}'] = $select_options = $options['model_options'];
                $model_keys[] = "{$field}";
                $replace_for_form['{%option_key%}'] = "models['{$field}']";
            } else {
                if (isset($options['options']) && is_array($options['options']) && count($options['options'])) {
                    $opts = [];
                    foreach ($options['options'] as $key => $value) {
                        $opts[] = "'$key' => '$value'";
                    }
                    $setting_keys[] = $setting_key = "{$this->replaces['{%model_variable%}']}_{$field}";
                    $settings_options_up[] = "app(config('sap.models.setting'))->create(['key' => '$setting_key','value' => [".implode(',', $opts).']]);';
                    $settings_options_down[] = "app(config('sap.models.setting'))->where('key','$setting_key')->forceDelete();";
                    $replace_for_form['{%option_key%}'] = "settings['{$setting_key}']";
                    $select_options = "settings('{$setting_key}')";
                }
            }

            $replace_for_form['{%label%}'] = $options['label'];
            $replace_for_form['{%field%}'] = $field;
            $replace_for_form['{%type%}'] = '';
            // if ('json' == $options['type']) {
            //     $replace_for_form['{%type%}'] = $type;
            // }
            $replace_for_form['{%model_variable%}'] = $model_variable = $this->replaces['{%model_variable%}'];
            $replace_for_form['{%attributes_tag%}'] = '';
            if (count($options['attributes'])) {
                $temp_attrs = [];
                foreach ($options['attributes'] as $attr_key => $attr_val) {
                    $temp_attrs[] = "'{$attr_key}'=>'{$attr_val}'";
                }
                $replace_for_form['{%attributes_tag%}'] = implode(', ', $temp_attrs);
            }
            $replace_for_form['{%class_tag%}'] = "'".implode("',\n'", $options['class'])."'";

            $read_stub = '<x-sap-display-field type="text" name="{%field%}" id="{%field%}" label="{%label%}" :value="$model->{%field%}" type="{%type%}"/>';
            $read_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $read_stub);

            $form_stub = '';
            switch ($options['type']) {
                case 'email':
                case 'number':
                case 'password':
                case 'text':
                case 'url':
                    $form_stub = '<x-sap-input-field type="'.$options['type'].'" name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :value="$model->{%field%} ?? \'\'"/>';
                    break;
                case 'time':
                    $form_stub = '<x-sap-time-field name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :value="$model->{%field%} ?? \'\'"/>';
                    break;
                case 'date':
                    $form_stub = '<x-sap-date-field name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :value="$model->{%field%} ?? \'\'"/>';
                    break;
                case 'image':
                    $form_stub = '<x-sap-image-field type="'.$options['type'].'" name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :value="$model->{%field%} ?? \'\'"/>';
                    break;
                case 'file':
                    $form_stub = '<x-sap-file-field type="'.$options['type'].'" name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :value="$model->{%field%} ?? \'\'"/>';
                    break;
                case 'textarea':
                    $form_stub = '<x-sap-textarea-field name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :value="$model->{%field%} ?? \'\'"/>';
                    break;
                case 'select':
                    $form_stub = '<x-sap-select-field name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :data="[\'style\'=>\'border bg-white\',\'live-search\'=>false]" :options="'.$select_options.'" :selected="$model->{%field%} ?? []"/>';
                    break;
                case 'radio':
                    $form_stub = '<x-sap-radios-field name="{%field%}" id="{%field%}" label="{%label%}" :options="'.$select_options.'" :checked="$model->{%field%} ?? []" :isGroup="false" :stacked="'.($options['stacked'] ? 1 : 0).'"/>';
                    break;
                case 'checkbox':
                    $form_stub = '<x-sap-checkboxes-field name="{%field%}" id="{%field%}" label="{%label%}" :options="'.$select_options.'" :checked="$model->{%field%} ?? []" :isGroup="false" :stacked="'.($options['stacked'] ? 1 : 0).'"/>';
                    break;
                case 'editor':
                    $form_stub = '<x-sap-editor-field name="{%field%}" id="{%field%}" label="{%label%}" :class="[{%class_tag%}]" :attribute_tags="[{%attributes_tag%}]" :value="$model->{%field%} ?? \'\'"/>';
                    break;
                default:
                    $this->error('Input Type not supported: <info>'.$field.':'.$options['type'].'</info>');
                    break;
            }
            $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $form_stub);

            if (in_array($options['type'], ['file', 'image'])) {
                if (isset($options['attributes']['multiple']) && 'multiple' == $options['attributes']['multiple']) {
                    $upload_strings[] = <<<EOT
        \$uploaded_files = [];
        if (\$request->hasFile('$field')) {
            foreach(\$request->file('$field') as \$key => \$file)
            {
                \$uploaded_files[] = str_replace('public', 'storage', \$request->file('$field.'.\$key)->store('public/$model_variable/$field'));
            }
            unset(\$request['$field']);
            \$request->merge([
                '$field' => \$uploaded_files,
            ]);
        }
EOT;
                } else {
                    $upload_strings[] = <<<EOT
        if (\$request->hasFile('$field')) {
            \$path = str_replace('public', 'storage', \$request->file('$field')->store('public/$model_variable/$field'));
            unset(\$request['$field']);
            \$request->merge([
                '$field' => \$path,
            ]);
        }
EOT;
                }
            }
        } // end foreach
        foreach ($this->config['appends'] as $key => $value) {
            $appends[] = "'{$key}'";
            $mutator = [];
            $key_name = studly_case($key);
            $mutator[] = 'public function get'.$key_name.'Attribute($value)'." {\n";
            $mutator[] = $this->indent(2).$this->replaceholder($value)."\n".$this->indent(1).'}';
            $mutators[] = implode('', $mutator);
        }
        $appends[] = "'readUrl'";
        $appends[] = "'esField'";

        $this->replaces['{%fillable_array%}'] = implode(",\n".$this->indent(2), $fillables);
        $this->replaces['{%mutators%}'] = implode(",\n".$this->indent(2), $mutators);
        $this->replaces['{%model_casts%}'] = "protected \$casts = [\n".$this->indent(2).implode(",\n".$this->indent(2), $casts)."\n".$this->indent(1).'];';
        $this->replaces['{%model_appends%}'] = "protected \$appends = [\n".$this->indent(2).implode(",\n".$this->indent(2), $appends)."\n".$this->indent(1).'];';
        $this->replaces['{%relationships%}'] = $relationships ? trim(implode(PHP_EOL, $relationships)) : '';
        $this->replaces['{%relationships_query%}'] = $relationships_query ? "->with('".implode("', '", $relationships_query)."')" : '';
        $this->replaces['{%user_timezones%}'] = $user_timezones ? trim(implode(PHP_EOL, $user_timezones)) : '';
        $this->replaces['{%validations_create%}'] = isset($validations['create']) ? trim(implode(PHP_EOL, $validations['create'])) : '';
        $this->replaces['{%validations_update%}'] = isset($validations['update']) ? trim(implode(PHP_EOL, $validations['update'])) : '';
        $this->replaces['{%form_fields%}'] = isset($form_fields) ? trim(implode(PHP_EOL.$this->indent(4), $form_fields)) : '';
        $this->replaces['{%read_fields%}'] = isset($read_fields) ? trim(implode(PHP_EOL.$this->indent(4), $read_fields)) : '';
        $this->replaces['{%settings_options_up%}'] = isset($settings_options_up) ? trim(implode(PHP_EOL.$this->indent(2), $settings_options_up)) : '';
        $this->replaces['{%settings_options_down%}'] = isset($settings_options_down) ? trim(implode(PHP_EOL.$this->indent(2), $settings_options_down)) : '';
        $this->replaces['{%search_scopes%}'] = isset($search_scopes) ? trim(implode(PHP_EOL.$this->indent(1), $search_scopes)) : '';
        $this->replaces['{%search_fields%}'] = isset($search_fields) ? trim(implode(PHP_EOL.$this->indent(1), $search_fields)) : '';
        $this->replaces['{%table_fields%}'] = isset($table_fields) ? trim(implode(','.PHP_EOL.$this->indent(3).'  ', $table_fields)).',' : '';
        $this->replaces['{%upload_strings%}'] = isset($upload_strings) ? trim(implode(PHP_EOL.'  ', $upload_strings)) : '';

        $this->model();
        $this->route();
        $this->api_route();
        $this->controller();
        $this->api_controller();
        $this->menu();
        $this->views();

        if ($this->config['migration']) {
            if (isset($migration_codes) && count($migration_codes)) {
                $this->migration_codes = implode("\n".$this->indent(3), $migration_codes);
            }
            $this->migration();
        }
    }

    protected function route()
    {
        if (!$this->files->exists(app_path('../'.config('sap.sub_route_dir')))) {
            $this->files->makeDirectory(app_path('../'.config('sap.sub_route_dir')), 0755, true);
        }
        $route_file = config('sap.sub_route_dir').'/'.$this->replaces['{%model_variable%}'].'Routes.php';
        $route_stub = $this->stub_path.'/route.stub';
        if (!$this->files->exists($route_stub)) {
            $this->error('API Route stub file not found: <info>'.$route_stub.'</info>');
            return;
        }
        $route_stub = $this->files->get($route_stub);
        $this->files->put($route_file, $this->replaceholder($route_stub));
        $this->line('Route file created: <info>'.$route_file.'</info>');
    }

    protected function api_route()
    {
        if (!$this->files->exists(app_path('../'.config('sap.sub_api_route_dir')))) {
            $this->files->makeDirectory(app_path('../'.config('sap.sub_api_route_dir')), 0755, true);
        }
        $route_file = config('sap.sub_api_route_dir').'/'.$this->replaces['{%model_variable%}'].'Routes.php';
        $route_stub = $this->stub_path.'/api_route.stub';
        if (!$this->files->exists($route_stub)) {
            $this->error('API Route stub file not found: <info>'.$route_stub.'</info>');
            return;
        }
        $route_stub = $this->files->get($route_stub);
        $this->files->put($route_file, $this->replaceholder($route_stub));
        $this->line('API Route file created: <info>'.$route_file.'</info>');
    }

    protected function menu()
    {
        $menu_stub = $this->stub_path.'/menu.stub';
        if (!$this->files->exists($menu_stub)) {
            $this->error('Menu stub file not found: <info>'.$menu_stub.'</info>');

            return;
        }
        $menu_stub = $this->files->get($menu_stub);
        $toWriteInFile = resource_path('views/vendor/sap/components/menu.blade.php');
        $toWriteInFileContent = $this->files->get($toWriteInFile);
        $replaceContent = $this->replaceholder($menu_stub);
        if (false === strpos($toWriteInFileContent, $replaceContent)) {
            $replaceContent = str_replace('<!--DoNotRemoveMe-->', $replaceContent."\n".$this->indent(0).'<!--DoNotRemoveMe-->', $toWriteInFileContent);
            $this->files->put($toWriteInFile, $replaceContent);
            $this->line('Menu included: <info>'.config('sap.routes_dir').'</info>');
        }
    }

    protected function model()
    {
        $model_stub = $this->stub_path.'/model.stub';
        if (!$this->files->exists($model_stub)) {
            $this->error('Model stub file not found: <info>'.$model_stub.'</info>');

            return;
        }
        $model_file = app_path($this->replaces['{%model%}'].'.php');
        $model_stub = $this->files->get($model_stub);
        $this->files->put($model_file, $this->replaceholder($model_stub));
        $this->line('Model file created: <info>'.$model_file.'</info>');
    }

    protected function controller()
    {
        if (!$this->files->exists(app_path(config('sap.custom_controller_dir')))) {
            $this->files->makeDirectory(app_path(config('sap.custom_controller_dir')), 0755, true);
        }
        $controller_stub = $this->stub_path.'/controller.stub';
        if (!$this->files->exists($controller_stub)) {
            $this->error('Controller stub file not found: <info>'.$controller_stub.'</info>');
            return;
        }
        $controller_file = app_path(config('sap.custom_controller_dir').'/'.$this->replaces['{%model%}'].'Controller.php');
        $controller_stub = $this->files->get($controller_stub);
        $this->files->put($controller_file, $this->replaceholder($controller_stub));
        $this->line('Controller file created: <info>'.$controller_file.'</info>');
    }

    protected function api_controller()
    {
        if (!$this->files->exists(app_path(config('sap.custom_api_controller_dir')))) {
            $this->files->makeDirectory(app_path(config('sap.custom_api_controller_dir')), 0755, true);
        }
        $controller_stub = $this->stub_path.'/api_controller.stub';
        if (!$this->files->exists($controller_stub)) {
            $this->error('Api Controller stub file not found: <info>'.$controller_stub.'</info>');
            return;
        }
        $controller_file = app_path(config('sap.custom_api_controller_dir').'/'.$this->replaces['{%model%}'].'Controller.php');
        $controller_stub = $this->files->get($controller_stub);
        $this->files->put($controller_file, $this->replaceholder($controller_stub));
        $this->line('Api Controller file created: <info>'.$controller_file.'</info>');
    }

    protected function views()
    {
        $view_files = ['search', 'index', 'edit', 'create', 'show', 'actions'];
        foreach ($view_files as $mode) {
            $view_stub = $this->stub_path.'/views/'.$mode.'.stub';
            if (!$this->files->exists($view_stub)) {
                $this->error('View stub file not found: <info>'.$view_stub.'</info>');

                return;
            }
            $view_path = resource_path('views/'.config('sap.custom_view_dir').'/'.$this->replaces['{%model_variable%}']);

            if (!$this->files->exists($view_path)) {
                $this->files->makeDirectory($view_path, 0755, true);
            }

            $view_file = resource_path('views/'.config('sap.custom_view_dir').'/'.$this->replaces['{%model_variable%}'].'/'.$mode.'.blade.php');
            $view_stub = $this->files->get($view_stub);

            $this->files->put($view_file, $this->replaceholder($view_stub));
            $this->line('View file created: <info>'.$view_file.'</info>');
        }
    }

    protected function migration()
    {
        $msg = 'Migration file created';
        $migration_stub = $this->stub_path.'/migration.stub';
        if (!$this->files->exists($migration_stub)) {
            $this->error('Migration stub file not found: <info>'.$migration_stub.'</info>');

            return;
        }
        $filename = "sap{$this->model}Table.php";
        $migration_file = database_path('migrations/'.date('Y_m_d_000000_').$filename);

        foreach ($this->files->files(database_path('migrations/')) as $file) {
            if (str_contains($file->getPathname(), $filename)) {
                $migration_file = $file->getPathname();
                $msg = 'Migration file overwritten';
            }
        }

        $migrations_stub = $this->files->get($migration_stub);
        $this->files->put($migration_file, $this->replaceholder($migrations_stub));
        $this->line($msg.': <info>'.$migration_file.'</info>');
    }

    protected function replaceholder($content)
    {
        if (isset($this->migration_codes)) {
            $this->replaces['{%migration_codes%}'] = $this->migration_codes;
        }
        foreach ($this->replaces as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }

    protected function putInChains($value)
    {
        $chains = [];
        foreach (explode('|', $value) as $chain) {
            $method_params = explode(':', $chain);
            $method = $method_params[0];
            $params_typed = [];
            if (isset($method_params[1])) {
                foreach (explode(',', $method_params[1]) as $param) {
                    $params_typed[] = (in_array($param, ['true', 'false']) || is_numeric($param)) ? $param : "'$param'";
                }
            }
            $chains[] = $method.'('.implode(', ', $params_typed).')';
        }

        return implode('->', $chains);
    }

    protected function indent($multiplier = 1)
    {
        // add indents to line
        return str_repeat('    ', $multiplier);
    }
}
