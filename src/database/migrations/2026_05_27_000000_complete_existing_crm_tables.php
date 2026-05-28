<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('clients', 'company_name')) {
                $table->string('company_name')->nullable();
            }

            if (! Schema::hasColumn('clients', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('clients', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (! Schema::hasColumn('clients', 'website')) {
                $table->string('website')->nullable();
            }

            if (! Schema::hasColumn('clients', 'address')) {
                $table->text('address')->nullable();
            }

            if (! Schema::hasColumn('clients', 'type')) {
                $table->string('type')->default('company');
            }
        });

        Schema::table('contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('contacts', 'client_id')) {
                $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('contacts', 'first_name')) {
                $table->string('first_name')->nullable();
            }

            if (! Schema::hasColumn('contacts', 'last_name')) {
                $table->string('last_name')->nullable();
            }

            if (! Schema::hasColumn('contacts', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('contacts', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (! Schema::hasColumn('contacts', 'position')) {
                $table->string('position')->nullable();
            }
        });

        Schema::table('quotes', function (Blueprint $table) {
            if (! Schema::hasColumn('quotes', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('quotes', 'client_id')) {
                $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('quotes', 'quote_number')) {
                $table->string('quote_number')->nullable()->unique();
            }

            if (! Schema::hasColumn('quotes', 'status')) {
                $table->string('status')->default('draft');
            }

            if (! Schema::hasColumn('quotes', 'valid_until')) {
                $table->date('valid_until')->nullable();
            }

            if (! Schema::hasColumn('quotes', 'total')) {
                $table->decimal('total', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('quotes', 'notes')) {
                $table->text('notes')->nullable();
            }
        });

        Schema::table('quote_items', function (Blueprint $table) {
            if (! Schema::hasColumn('quote_items', 'quote_id')) {
                $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('quote_items', 'description')) {
                $table->string('description')->nullable();
            }

            if (! Schema::hasColumn('quote_items', 'quantity')) {
                $table->decimal('quantity', 10, 2)->default(1);
            }

            if (! Schema::hasColumn('quote_items', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('quote_items', 'total')) {
                $table->decimal('total', 12, 2)->default(0);
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('invoices', 'client_id')) {
                $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('invoices', 'quote_id')) {
                $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('invoices', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->unique();
            }

            if (! Schema::hasColumn('invoices', 'status')) {
                $table->string('status')->default('draft');
            }

            if (! Schema::hasColumn('invoices', 'issued_at')) {
                $table->date('issued_at')->nullable();
            }

            if (! Schema::hasColumn('invoices', 'due_at')) {
                $table->date('due_at')->nullable();
            }

            if (! Schema::hasColumn('invoices', 'total')) {
                $table->decimal('total', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('invoices', 'notes')) {
                $table->text('notes')->nullable();
            }
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            if (! Schema::hasColumn('invoice_items', 'invoice_id')) {
                $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('invoice_items', 'description')) {
                $table->string('description')->nullable();
            }

            if (! Schema::hasColumn('invoice_items', 'quantity')) {
                $table->decimal('quantity', 10, 2)->default(1);
            }

            if (! Schema::hasColumn('invoice_items', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('invoice_items', 'total')) {
                $table->decimal('total', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        //
    }
};
