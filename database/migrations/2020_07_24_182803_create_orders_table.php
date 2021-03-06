<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer', 255);
            $table->string('comment', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->enum('status', ['Created', 'Shipped'])->default('Created');
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->string('tracking_number', 255)->nullable();
            $table->date('shipped')->nullable();
            $table->enum('packing_selection', ['Bubbles Pack', 'Carton']);
            $table->string('address', 255);
            $table->string('city', 255);
            $table->string('zip_postal_code', 255);
            $table->string('state_region', 255);
            $table->string('country', 255);
            $table->string('phone', 30)->nullable();
            $table->enum('shipping_company', ['USPS', 'FedEx', 'DHL', 'UPS', 'APC']);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
