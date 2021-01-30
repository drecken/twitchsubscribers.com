<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'subscriptions',
            function (Blueprint $table) {
                $table->foreignId('broadcaster_id')->constrained('users');
                $table->foreignId('subscriber_id')->constrained('users');
                $table->foreignId('gifter_id')->nullable()->constrained('users');
                $table->string('tier');

                $table->index(['broadcaster_id', 'subscriber_id']);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
