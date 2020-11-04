## Available Components

- [Config](#Config)
- [text](#text)
- [file](#file)
- [image](#image)
- [date](#date)
- [time](#time)
- [number](#number)
- [editor](#editor)
- [textarea](#textarea)
- [select](#select)
- [checkbox](#checkbox)
- [radio](#radio)
- [datalist](#datalist)

### Brand Components

- [alert](#alert)
- [carousel](#carousel)
- [login modal](#login-modal)
- [navbar top](#navbar-top)
- [navbar top login](#navbar-top-login)

#### Config

```php
'name' => [
    'label' => 'Name',
    'type' => 'text',
    'class' => [],
    'attributes' => [],
    'list' => true,
    'search' => true,
    'sortable' => true,
    'migration' => 'string:{%field%}|nullable|default:',
    'validation' => [
        'create' => 'required|min:4',
        'update' => 'required|min:4',
    ],
    'options' => ['one' => 'this'],
    'model_options' => "app(config('sap.models.user'))->query()->pluck('name','id')->toArray()",
    'user_timezone' => false,
    'casts' => '',
    'mutators' => [
        'get' => '',
        'set' => '',
    ],
    'relationship' => [
        'another_user' => 'belongsTo:App\User,another_user_id,id',
    ],
],
```

1. *name*. (field name) is an index of the field name which will be saved as table field name
1. *label*. used in label tag
1. *type*. text, file, image, date, time, number, editor, textarea, select, checkbox, radio, datalist
1. *class*. class for DOM attribute. There are already prefilled class which are important
1. *attributes*. others attributes for the DOM excluded class. Set multiple if needed, & files, images, select, checkbox will automatically takes as array
1. *list*. to display the field in index or listing page
1. *search*. to auto create search DOM in index or listing page
1. *sortable*. to allow sortable (WIP)
1. *migration*. create a migration scripts
1. *validation*. rules for input validation on create or update
1. *options*. array of the options that allow to select, this will be automatically added to settings table
1. *model_options*. options from model, this has toppest priority if *options* and *model_options* both declared, options will be ignored
1. *casts*. cast this field as array, boolean, integer and so
1. *mutators*. set or get mutators for the field, in php coding method
1. *relationship*. declare the relationship if there is
1. *user_timezone*. true or false, usually this is needed for *datetime*, or *date* field type

#### text

```php
<x-sap::input-field type="text" name="name" id="name" label="Name" :class="['']" :attribute_tags="[]" :value="$model->name ?? ''"/>
```

#### file

```php
<x-sap::file-field type="file" name="file" id="file" label="File" :class="['']" :attribute_tags="[]" :value="$model->file ?? ''"/>
```

#### image

```php
<x-sap::image-field type="image" name="image" id="image" label="Image" :class="['']" :attribute_tags="[]" :value="$model->images ?? ''"/>
```

```php
<x-sap::image-field type="images" name="images" id="images" label="Images" :class="['']" :attribute_tags="['multiple'=>'multiple']" :value="$model->images ?? ''"/>
```

#### date

```php
<x-sap::date-field name="date" id="date" label="Date" :class="['']" :attribute_tags="[]" :value="$model->date ?? ''"/>
```

#### time

```php
<x-sap::time-field name="time" id="time" label="Time" :class="['']" :attribute_tags="[]" :value="$model->time ?? ''"/>
```

#### number

```php
<x-sap::input-field type="number" name="number" id="number" label="Number" :class="['']" :attribute_tags="['min'=>'1', 'max'=>'100']" :value="$model->number ?? ''"/>
```

#### editor

```php
<x-sap::editor-field name="editor" id="editor" label="Editor" :class="['']" :attribute_tags="[]" :value="$model->editor ?? ''"/>
```

#### textarea

```php
<x-sap::textarea-field name="textarea" id="textarea" label="Textarea" :class="['']" :attribute_tags="[]" :value="$model->textarea ?? ''"/>
```

#### select

```php
<x-sap::select-field name="select" id="select" label="select" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('*modulename*_select')" :selected="$model->select ?? []"/>
```

```php
<x-sap::select-field name="user" id="user" label="User" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.user'))->query()->pluck('name','id')->toArray()" :selected="$model->user ?? []"/>
```

#### checkbox

```php
<x-sap::checkboxes-field name="checkbox" id="checkbox" label="Checkbox" :options="settings('*modulename*_checkbox')" :checked="$model->checkbox ?? []" :isGroup="false" :stacked="1"/>
```

#### radio

```php
<x-sap::radios-field name="radio" id="radio" label="Radio" :options="settings('*modulename*_radio')" :checked="$model->radio ?? []" :isGroup="false" :stacked="0"/>
```

#### datalist

```php
<x-sap::datalist-field name="datalist" id="datalist" label="Datalist" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="settings('*modulename*_datalist')" :selected="$model->datalist ?? []"/>
```

```php
<x-sap::datalist-field name="datalist" id="datalist" label="Datalist" :class="['']" :attribute_tags="[]" :data="['style'=>'border bg-white','live-search'=>false]" :options="app(config('sap.models.user'))->query()->pluck('name','id')->toArray()" :selected="$model->datalist ?? []"/>
```

#### alert

```php
<x-*brandname*::alert />
```

#### carousel

```php
<x-*brandname*::carousel slug="sample-carousel" :tags="['new','hot']" />
```

#### login modal

```php
<x-*brandname*::login-modal />
```

#### navbar top

```php
<x-*brandname*::navbar-top groupSlug="sample-navbar" />
```

#### navbar top login

```php
<x-*brandname*::navbar-top-login />
```
