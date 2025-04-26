<?php

use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_status_pivots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lead::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(LeadStatus::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_status_pivots');
    }
};
