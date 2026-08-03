<?php

use App\Models\Employee\Employee;
use App\Models\Movement\EmployeeMovement;
use App\Models\ClaimExpense\ExpenseApplication;
use App\Models\ClaimExpense\ExpenseType;
use App\Models\Payroll\Bill;
use App\Models\User;
use App\Enums\UserType;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'bills.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'bills.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'bills.delete', 'guard_name' => 'web']);
});

test('saving travel movement allowances creates a bill record', function () {
    $employee = Employee::factory()->create();
    
    $movement = EmployeeMovement::create([
        'employee_id' => $employee->id,
        'from_date' => now()->format('Y-m-d'),
        'to_date' => now()->addDays(2)->format('Y-m-d'),
        'purpose' => 'Business Trip',
        'status' => 'approved',
        'total_ta' => 0.00,
        'total_da' => 0.00,
        'total_allowance' => 0.00,
    ]);

    $service = app(\App\Services\Movement\EmployeeMovementServices::class);
    $service->saveAllowances([
        'ta_plan_id' => null,
        'da_plan_id' => null,
        'total_ta' => 1500.00,
        'total_da' => 1000.00,
        'total_allowance' => 2500.00,
    ], $movement->id);

    $this->assertDatabaseHas('bills', [
        'expense_id' => $movement->id,
        'type' => 'travel-movement',
        'employee_id' => $employee->id,
        'amount' => 2500.00,
        'payment_status' => 'unpaid',
    ]);
});

test('approving claim expense creates a bill record', function () {
    $employee = Employee::factory()->create();
    $expenseType = ExpenseType::create([
        'name' => 'Office Supplies',
        'description' => 'Stationeries and printer ink'
    ]);

    $expense = ExpenseApplication::create([
        'employee_id' => $employee->id,
        'expense_type_id' => $expenseType->id,
        'amount' => 350.00,
        'payment_method' => 'cash',
        'purpose' => 'Buying stationery',
        'status' => 'pending',
    ]);

    // Manually trigger the completed listener
    $workflow = \Innovity\ApprovalEngine\Models\Workflow::create([
        'name' => 'Claim Expense Workflow',
        'module' => 'claim-expense',
        'type' => 'sequential',
        'total_steps' => 1,
        'is_active' => true
    ]);

    $approvalRequest = \Innovity\ApprovalEngine\Models\ApprovalRequest::create([
        'workflow_id' => $workflow->id,
        'approvable_type' => ExpenseApplication::class,
        'approvable_id' => $expense->id,
        'status' => 'approved'
    ]);

    $workflowCompletedEvent = new \Innovity\ApprovalEngine\Events\ApprovalCompleted($approvalRequest);
    $listener = new \App\Listeners\Workflow\ClaimExpenseWorkflowListener();
    $listener->handleCompleted($workflowCompletedEvent);

    $this->assertDatabaseHas('bills', [
        'expense_id' => $expense->id,
        'type' => 'claim-expense',
        'employee_id' => $employee->id,
        'amount' => 350.00,
        'payment_status' => 'unpaid',
    ]);
});

test('user can toggle payment status and delete bills', function () {
    $user = User::factory()->create([
        'user_type' => UserType::Group,
    ]);
    
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['bills.view', 'bills.edit', 'bills.delete']);
    $user->assignRole($role);

    $employee = Employee::factory()->create();
    $bill = Bill::create([
        'employee_id' => $employee->id,
        'expense_id' => 999,
        'type' => 'travel-movement',
        'expense_type' => 'Travel Movement',
        'amount' => 1200.00,
        'payment_status' => 'unpaid',
    ]);

    // View index
    $response = $this->actingAs($user)->get(route('bills.index'));
    $response->assertStatus(200);

    // Toggle to paid
    $response = $this->actingAs($user)->put(route('bills.change_payment_status'), [
        'id' => $bill->id,
        'payment_status' => 'paid',
        'payment_method' => 'Cash',
        'remarks' => 'Paid via cash',
    ]);
    $response->assertJson([
        'success' => true,
        'message' => 'Bill payment status updated successfully.'
    ]);

    $this->assertDatabaseHas('bills', [
        'id' => $bill->id,
        'payment_status' => 'paid',
        'payment_method' => 'Cash',
        'remarks' => 'Paid via cash',
    ]);

    // Delete bill
    $response = $this->actingAs($user)->delete(route('bills.destroy', $bill->id));
    $response->assertJson([
        'success' => true,
        'message' => 'Bill deleted successfully.'
    ]);

    $this->assertDatabaseMissing('bills', [
        'id' => $bill->id,
    ]);
});

test('user can pay a bill with a file attachment', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    
    $user = User::factory()->create([
        'user_type' => UserType::Group,
    ]);
    
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['bills.view', 'bills.edit']);
    $user->assignRole($role);

    $employee = Employee::factory()->create();
    $bill = Bill::create([
        'employee_id' => $employee->id,
        'expense_id' => 999,
        'type' => 'claim-expense',
        'expense_type' => 'Office Supplies',
        'amount' => 500.00,
        'payment_status' => 'unpaid',
    ]);

    $file = \Illuminate\Http\UploadedFile::fake()->create('receipt.pdf', 100);

    $response = $this->actingAs($user)->put(route('bills.change_payment_status'), [
        'id' => $bill->id,
        'payment_status' => 'paid',
        'payment_method' => 'Bank Transfer',
        'remarks' => 'Transferred online',
        'attachment' => $file,
    ]);

    $response->assertJson([
        'success' => true,
        'message' => 'Bill payment status updated successfully.'
    ]);

    $updatedBill = Bill::findOrFail($bill->id);
    $this->assertEquals('paid', $updatedBill->payment_status);
    $this->assertEquals('Bank Transfer', $updatedBill->payment_method);
    $this->assertEquals('Transferred online', $updatedBill->remarks);
    $this->assertNotNull($updatedBill->attachment_path);
});

test('user can export bills to excel and print', function () {
    $user = User::factory()->create([
        'user_type' => UserType::Group,
    ]);
    
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['bills.view']);
    $user->assignRole($role);

    $employee = Employee::factory()->create();
    $bill = Bill::create([
        'employee_id' => $employee->id,
        'expense_id' => 999,
        'type' => 'travel-movement',
        'expense_type' => 'Travel Movement',
        'amount' => 1200.00,
        'payment_status' => 'unpaid',
    ]);

    // Test Export
    $response = $this->actingAs($user)->get(route('bills.export.excel') . '?keyword=Travel');
    $response->assertStatus(200);

    // Test Print
    $response = $this->actingAs($user)->get(route('bills.print'));
    $response->assertStatus(200);
    $response->assertViewIs('payroll.bills.print_index');
    $response->assertSee('Bill Pay Management Sheet');
});
