<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTemplatesTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable','mail_templates'])->flush();
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mailable');
            $table->text('subject')->nullable();
            $table->longtext('html_template');
            $table->longtext('text_template')->nullable();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        app(config('sap.models.permission'))->createGroup('Mailers', ['Read Mailers', 'Preview Mailers', 'Update Mailers', 'Delete Mailers'], 1);
        /*app(config('sap.models.mailer'))->create([
            'mailable' => \App\Mail\GreetingMail::class,
            'subject' => 'Welcome, {{ name }}',
            'html_template' => '<h1>Hello, {{ name }}!</h1>',
            'text_template' => 'Hello, {{ name }}!',
        ]);*/
    }
    public function down()
    {
        app(config('sap.models.permission'))->whereIn('group', [
            'Mailers',
        ])->delete();
        Schema::dropIfExists('mail_templates');
    }
}
