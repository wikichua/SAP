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
        $config_file = config_path('sap/' . $this->model . 'Config.php');
        if (!$this->files->exists($config_file)) {
            $this->error('Config file not found: <info>' . $config_file . '</info>');
            return;
        }
        $this->config = include $config_file;
        if (!$this->config['ready']) {
            if ($this->option('force') == false) {
                $this->error('Config file not ready: <info>' . $config_file . '</info>');
                return;
            }
        }
        $this->line('Config <info>' . $this->model . '</info> Found! Initiating!');
        $this->initReplacer();
        $this->reinstate();

        $config_content = $this->files->get($config_file);
        $config_content = str_replace("'ready' => true,", "'ready' => false,", $config_content);
        $this->files->put($config_file, $config_content);
        $this->line('<info>Since you had done make the CRUD, we will help you set ready to false to prevent accidentally make after you have done all your changes in your flow!</info>');
        $this->line('Config has changed: <info>' . $config_file . '</info>');

        $this->alert("Now remember to run php artisan ziggy:generate && npm run production\nafter you have done adjusting your vue component\nor business in your controler & model.");
    }
    protected function initReplacer()
    {
        $this->replaces['{%custom_controller_namespace%}'] = config('sap.custom_controller_namespace');

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

        if (isset($this->config['table_name']) && $this->config['table_name'] != '') {
            $this->replaces['{%table_name%}'] = $this->config['table_name'];
            $this->replaces['{%table_declared%}'] = "protected \$table = '{$this->config['table_name']}';";
        }
        if (isset($this->config['menu_name']) && $this->config['menu_name'] != '') {
            $this->replaces['{%menu_name%}'] = $this->config['menu_name'];
        }
    }
    protected function reinstate()
    {
        $config_form = $this->config['form'];
        $scopeModelLists = $model_keys = $setting_keys = $table_fields = $search_scopes = $settings_options_up = $settings_options_down = $read_fields = $form_fields = $validations = $user_timezones = $fillables = $casts = $appends = $mutators = $relationships = $relationships_query = [];

        foreach ($config_form as $field => $options) {
            $this->replaces['{%field_variable%}'] = studly_case($field);
            if (isset($options['migration']) && $options['migration'] != '') {
                $migration = $options['migration'];
                $this->replaces['{%field%}'] = $field;
                $migration_codes[] = $this->replaceholder('$table->' . $this->putInChains($migration) . ';');
            }
            $fillables[] = "'{$field}'";
            if ($options['casts'] != '') {
                $casts[] = "'{$field}' => '{$options['casts']}'";
            }
            if ($options['list']) {
                $search_boolean_string = $options['search'] ? 'true' : 'false';
                $sort_boolean_string = isset($options['sortable']) && $options['sortable'] ? 'true' : 'false';
                $table_fields[] = "['title' => '{$options['label']}', 'data' => '{$field}', 'sortable' => {$sort_boolean_string}, 'filterable' => {$search_boolean_string}]";
            }
            $scopes = [];
            if ($options['search']) {
                $scopes[] = 'public function scopeFilter' . studly_case($field) . '($query, $search)';
                $scopes[] = $this->indent() . '{';
                $scopes[] = $this->indent() . '    return $query->where(\''.$field.'\', \'like\', "%{$search}%");';
                $scopes[] = $this->indent() . '}';
                $search_scopes[] = implode(PHP_EOL, $scopes) . PHP_EOL;
            }

            if (!empty($options['relationship'])) {
                $relationships[] = $this->indent() . 'public function ' . array_keys($options['relationship'])[0] . '()';
                $relationships[] = $this->indent() . '{';
                $relationships[] = $this->indent() . '    return $this->' . $this->putInChains(array_values($options['relationship'])[0]) . ';';
                $relationships[] = $this->indent() . '}' . PHP_EOL;
                $relationships_query[] = array_keys($options['relationship'])[0];
            }

            if (!empty($options['user_timezone'])) {
                $user_timezones[] = $this->indent() . 'public function get' . studly_case($field) . 'Attribute($value)';
                $user_timezones[] = $this->indent() . '{';
                $user_timezones[] = $this->indent() . '    return $this->inUserTimezone($value);';
                $user_timezones[] = $this->indent() . '}' . PHP_EOL;
            }

            if (!empty($options['validation'])) {
                foreach ($options['validation'] as $method => $rules) {
                    if (isset($options['input']['type']) && $options['input']['type'] == 'file')
                        $validations[$method][] = $this->indent(3) . '"' . $field . '_file" => "' . $rules . '",';
                    else
                        $validations[$method][] = $this->indent(3) . '"' . $field . '" => "' . $rules . '",';
                }
            }

            $replace_for_form['{%option_key%}'] = '';

            if (isset($options['model_options']) && is_array($options['model_options']) && count($options['model_options'])) {
                $replace_for_form['{%model_option_query%}'] = $options['model_options']['query'];
                $replace_for_form['{%map_text%}'] = $options['model_options']['map_text'];
                $replace_for_form['{%map_value%}'] = $options['model_options']['map_value'];
                $stub = $this->stub_path . '/scopeModelList.stub';
                $stub = str_replace(array_keys($this->replaces), $this->replaces, $this->indent() . $this->files->get($stub));
                $scopeModelLists[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                $model_keys[] = "{$field}";
                $replace_for_form['{%option_key%}'] = "models['{$field}']";
            } else {
                if (isset($options['options']) && is_array($options['options']) && count($options['options'])) {
                    $opts = [];
                    foreach ($options['options'] as $key => $value) {
                        $opts[] = "'$key' => '$value'";
                    }
                    $setting_keys[] = $setting_key = "{$this->replaces['{%model_variable%}']}_{$field}";
                    $settings_options_up[] = "app(config('sap.models.setting'))->create(['key' => '$setting_key','value' => [" . implode(',', $opts) . "]]);";
                    $settings_options_down[] = "app(config('sap.models.setting'))->where('key','$setting_key')->forceDelete();";
                    $replace_for_form['{%option_key%}'] = "settings['{$setting_key}']";
                }
            }

            $replace_for_form['{%label%}'] = $options['label'];
            $replace_for_form['{%field%}'] = $field;
            $replace_for_form['{%model_variable%}'] = $this->replaces['{%model_variable%}'];
            $replace_for_form['{%attributes_tag%}'] = implode(' ', $options['attributes']);
            $replace_for_form['{%class_tag%}'] = implode(' ', $options['class']);

            $stub = $this->stub_path . '/components/form/plaintext.stub';
            if (!$this->files->exists($stub)) {
                $this->error('Plaintext stub file not found: <info>' . $stub . '</info>');
                return;
            }
            $stub = $this->files->get($stub);
            $read_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);

            switch ($options['type']) {
                case 'email':
                case 'number':
                case 'password':
                case 'text':
                case 'url':
                    $replace_for_form['{%input_type%}'] = $options['type'];
                    $stub = $this->stub_path . '/components/form/dynamic.stub';
                    if (!$this->files->exists($stub)) {
                        $this->error('Dynamic stub file not found: <info>' . $stub . '</info>');
                        return;
                    }
                    $stub = $this->files->get($stub);
                    $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                    break;
                // case 'date':
                //     $stub = $this->stub_path . '/components/form/date.stub';
                //     if (!$this->files->exists($stub)) {
                //         $this->error('Date stub file not found: <info>' . $stub . '</info>');
                //         return;
                //     }
                //     $stub = $this->files->get($stub);
                //     $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                //     break;
                // case 'file':
                //     $stub = $this->stub_path . '/components/form/file.stub';
                //     if (!$this->files->exists($stub)) {
                //         $this->error('File stub file not found: <info>' . $stub . '</info>');
                //         return;
                //     }
                //     $stub = $this->files->get($stub);
                //     $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                //     break;
                // case 'textarea':
                //     $stub = $this->stub_path . '/components/form/textarea.stub';
                //     if (!$this->files->exists($stub)) {
                //         $this->error('Textarea stub file not found: <info>' . $stub . '</info>');
                //         return;
                //     }
                //     $stub = $this->files->get($stub);
                //     $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                //     break;
                // case 'select':
                //     $stub = $this->stub_path . '/components/form/select.stub';
                //     if (!$this->files->exists($stub)) {
                //         $this->error('Select stub file not found: <info>' . $stub . '</info>');
                //         return;
                //     }
                //     $stub = $this->files->get($stub);
                //     $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                //     break;
                // case 'radio':
                //     $stub = $this->stub_path . '/components/form/radio.stub';
                //     if (!$this->files->exists($stub)) {
                //         $this->error('Radio stub file not found: <info>' . $stub . '</info>');
                //         return;
                //     }
                //     $stub = $this->files->get($stub);
                //     $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                //     break;
                // case 'checkbox':
                //     $stub = $this->stub_path . '/components/form/checkbox.stub';
                //     if (!$this->files->exists($stub)) {
                //         $this->error('Checkbox stub file not found: <info>' . $stub . '</info>');
                //         return;
                //     }
                //     $stub = $this->files->get($stub);
                //     $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                //     break;
                // case 'editor':
                //     $stub = $this->stub_path . '/components/form/editor.stub';
                //     if (!$this->files->exists($stub)) {
                //         $this->error('Editor stub file not found: <info>' . $stub . '</info>');
                //         return;
                //     }
                //     $stub = $this->files->get($stub);
                //     $form_fields[] = str_replace(array_keys($replace_for_form), $replace_for_form, $stub);
                //     break;
                default:
                    $this->error('Input Type not supported: <info>' . $field . ':' . $options['type'] . '</info>');
                    break;
            }
        }

        foreach ($this->config['appends'] as $key => $value) {
            $appends[] = "'{$key}'";
            $mutator = [];
            $key_name = studly_case($key);
            $mutator[] = 'public function get' . $key_name . 'Attribute($value)' . " {\n";
            $mutator[] = $this->indent(2) . $this->replaceholder($value) . "\n" . $this->indent(1) . "}";
            $mutators[] = implode('', $mutator);
        }

        $this->replaces['{%fillable_array%}'] = implode(",\n" . $this->indent(2), $fillables);
        $this->replaces['{%mutators%}'] = implode(",\n" . $this->indent(2), $mutators);
        $this->replaces['{%model_casts%}'] = "protected \$casts = [\n" . $this->indent(2) . implode(",\n" . $this->indent(2), $casts) . "\n" . $this->indent(1) . "];";
        $this->replaces['{%model_appends%}'] = "protected \$appends = [\n" . $this->indent(2) . implode(",\n" . $this->indent(2), $appends) . "\n" . $this->indent(1) . "];";
        $this->replaces['{%relationships%}'] = $relationships ? trim(implode(PHP_EOL, $relationships)) : '';
        $this->replaces['{%relationships_query%}'] = $relationships_query ? "->with('" . implode("', '", $relationships_query) . "')" : '';
        $this->replaces['{%user_timezones%}'] = $user_timezones ? trim(implode(PHP_EOL, $user_timezones)) : '';
        $this->replaces['{%validations_create%}'] = isset($validations['create']) ? trim(implode(PHP_EOL, $validations['create'])) : '';
        $this->replaces['{%validations_update%}'] = isset($validations['update']) ? trim(implode(PHP_EOL, $validations['update'])) : '';
        $this->replaces['{%form_fields%}'] = isset($form_fields) ? trim(implode(PHP_EOL . $this->indent(3), $form_fields)) : '';
        $this->replaces['{%read_fields%}'] = isset($read_fields) ? trim(implode(PHP_EOL . $this->indent(3), $read_fields)) : '';
        $this->replaces['{%settings_options_up%}'] = isset($settings_options_up) ? trim(implode(PHP_EOL . $this->indent(2), $settings_options_up)) : '';
        $this->replaces['{%settings_options_down%}'] = isset($settings_options_down) ? trim(implode(PHP_EOL . $this->indent(2), $settings_options_down)) : '';
        $this->replaces['{%search_scopes%}'] = isset($search_scopes) ? trim(implode(PHP_EOL . $this->indent(1), $search_scopes)) : '';
        $this->replaces['{%table_fields%}'] = isset($table_fields) ? trim(implode(',' . PHP_EOL . $this->indent(3) . "  ", $table_fields)) . ',' : '';
        $this->replaces['{%scopeModelLists%}'] = isset($scopeModelLists) ? trim(implode(PHP_EOL, $scopeModelLists)) : '';

        $this->model();
        $this->route();
        $this->controller();
        $this->menu();
        $this->views();

        if ($this->config['migration']) {
            if (isset($migration_codes) && count($migration_codes)) {
                $this->migration_codes = implode("\n" . $this->indent(3), $migration_codes);
            }
            $this->migration();
        }
    }
    protected function route()
    {
        if (!$this->files->exists(app_path('../' . config('sap.sub_route_dir')))) {
            $this->files->makeDirectory(app_path('../' . config('sap.sub_route_dir')), 0755, true);
        }
        $route_file = config('sap.sub_route_dir') . '/' . $this->replaces['{%model_variable%}'] . 'Routes.php';
        $route_stub = $this->stub_path . '/route.stub';
        if (!$this->files->exists($route_stub)) {
            $this->error('API Route stub file not found: <info>' . $route_stub . '</info>');
            return;
        }
        $route_stub = $this->files->get($route_stub);
        $this->files->put($route_file, $this->replaceholder($route_stub));
        $this->line('API Route file created: <info>' . $route_file . '</info>');
    }
    protected function menu()
    {
        $menu_stub = $this->stub_path . '/menu.stub';
        if (!$this->files->exists($menu_stub)) {
            $this->error('Menu stub file not found: <info>' . $menu_stub . '</info>');
            return;
        }
        $menu_stub = $this->files->get($menu_stub);
        $toWriteInFile = resource_path('views/vendor/sap/components/menu.blade.php');
        $toWriteInFileContent = $this->files->get($toWriteInFile);
        $replaceContent = $this->replaceholder($menu_stub);
        if (strpos($toWriteInFileContent, $replaceContent) === false) {
            $replaceContent = str_replace('<!--DoNotRemoveMe-->', $replaceContent . "\n" . $this->indent(0) . "<!--DoNotRemoveMe-->", $toWriteInFileContent);
            $this->files->put($toWriteInFile, $replaceContent);
            $this->line('Menu included: <info>' . config('sap.routes_dir') . '</info>');
        }
    }
    protected function model()
    {
        $model_stub = $this->stub_path . '/model.stub';
        if (!$this->files->exists($model_stub)) {
            $this->error('Model stub file not found: <info>' . $model_stub . '</info>');
            return;
        }
        $model_file = app_path($this->replaces['{%model%}'] . '.php');
        $model_stub = $this->files->get($model_stub);
        $this->files->put($model_file, $this->replaceholder($model_stub));
        $this->line('Model file created: <info>' . $model_file . '</info>');
    }
    protected function controller()
    {
        if (!$this->files->exists(app_path(config('sap.custom_controller_dir')))) {
            $this->files->makeDirectory(app_path(config('sap.custom_controller_dir')), 0755, true);
        }
        $controller_stub = $this->stub_path . '/controller.stub';
        if (!$this->files->exists($controller_stub)) {
            $this->error('Controller stub file not found: <info>' . $controller_stub . '</info>');
            return;
        }
        $controller_file = app_path(config('sap.custom_controller_dir') . '/' . $this->replaces['{%model%}'] . 'Controller.php');
        $controller_stub = $this->files->get($controller_stub);
        $this->files->put($controller_file, $this->replaceholder($controller_stub));
        $this->line('Controller file created: <info>' . $controller_file . '</info>');
    }
    protected function views()
    {
        $component_files = ['create'];
        foreach ($component_files as $mode) {
            $component_stub = $this->stub_path . '/components/' . $mode . '.stub';
            if (!$this->files->exists($component_stub)) {
                $this->error('View stub file not found: <info>' . $component_stub . '</info>');
                return;
            }
            $component_path = resource_path('js/components/' . $this->replaces['{%model_variable%}']);
            if (!$this->files->exists($component_path)) {
                $this->files->makeDirectory($component_path, 0755, true);
            }

            $component_file = resource_path('js/components/' . $this->replaces['{%model_variable%}'] . '/' . $mode . 'Component.vue');
            $component_stub = $this->files->get($component_stub);

            $this->files->put($component_file, $this->replaceholder($component_stub));
            $this->line('Vue component file created: <info>' . $component_file . '</info>');
        }
    }
    protected function migration()
    {
        $msg = 'Migration file created';
        $migration_stub = $this->stub_path . '/migration.stub';
        if (!$this->files->exists($migration_stub)) {
            $this->error('Migration stub file not found: <info>' . $migration_stub . '</info>');
            return;
        }
        $filename = "sap{$this->model}Table.php";
        $migration_file = database_path('migrations/' . date('Y_m_d_000000_') . $filename);

        foreach ($this->files->files(database_path('migrations/')) as $file) {
            if (str_contains($file->getPathname(), $filename)) {
                $migration_file = $file->getPathname();
                $msg = 'Migration file overwritten';
            }
        }

        $migrations_stub = $this->files->get($migration_stub);
        $this->files->put($migration_file, $this->replaceholder($migrations_stub));
        $this->line($msg . ': <info>' . $migration_file . '</info>');
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
            $chains[] = $method . '(' . implode(', ', $params_typed) . ')';
        }
        return implode('->', $chains);
    }
    protected function indent($multiplier = 1)
    {
        // add indents to line
        return str_repeat('    ', $multiplier);
    }
}
