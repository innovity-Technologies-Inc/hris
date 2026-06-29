<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $workflowsTable = config('approval-engine.table_names.workflows', 'approval_workflows');
        $stepsTable = config('approval-engine.table_names.workflow_steps', 'approval_workflow_steps');
        $requestsTable = config('approval-engine.table_names.requests', 'approval_requests');
        $stepRequestsTable = config('approval-engine.table_names.step_requests', 'approval_step_requests');

        Schema::create($workflowsTable, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module');
            $table->string('type')->default('sequential'); // sequential or random
            $table->integer('total_steps')->default(1);
            $table->integer('required_approvals')->nullable(); // Used for random workflows
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create($stepsTable, function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained(config('approval-engine.table_names.workflows', 'approval_workflows'))->cascadeOnDelete();
            $table->string('name');
            $table->integer('step_order');
            $table->string('required_user_type'); // e.g., 'department_head', 'manager', etc.
            $table->timestamps();
        });

        Schema::create($requestsTable, function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained(config('approval-engine.table_names.workflows', 'approval_workflows'))->cascadeOnDelete();
            $table->morphs('approvable'); // For linking to e.g. LeaveRequest
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->json('payload')->nullable(); // Field-level approvals
            $table->timestamps();
        });

        Schema::create($stepRequestsTable, function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_request_id')->constrained(config('approval-engine.table_names.requests', 'approval_requests'))->cascadeOnDelete();
            $table->foreignId('workflow_step_id')->constrained(config('approval-engine.table_names.workflow_steps', 'approval_workflow_steps'))->cascadeOnDelete();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('comments')->nullable();
            $table->timestamp('action_taken_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('approval-engine.table_names.step_requests', 'approval_step_requests'));
        Schema::dropIfExists(config('approval-engine.table_names.requests', 'approval_requests'));
        Schema::dropIfExists(config('approval-engine.table_names.workflow_steps', 'approval_workflow_steps'));
        Schema::dropIfExists(config('approval-engine.table_names.workflows', 'approval_workflows'));
    }
};
