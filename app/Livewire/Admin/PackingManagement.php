<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\PackingProduct;
use App\Models\PackingSalary;
use App\Models\Employee;
use Carbon\Carbon;

#[Title("Admin Production Management")]
#[Layout("components.layouts.admin")]
class PackingManagement extends Component
{
    /**
     * Passed to the Blade and then into Alpine.
     * We'll keep them as simple arrays for easy JSON encoding.
     */
    public array $employees = [];    // [ ['emp_id'=>1,'fname'=>'Jane'], ... ]
    public array $products  = [];    // [ ['id'=>1,'product_name'=>'A','per_rate'=>120.00], ... ]

    /**
     * Listen for a frontend call to persist records.
     * $records is an array of rows like:
     * [
     *   [
     *     'employee_id' => 1,
     *     'product_id' => 2,
     *     'date' => '2025-08-22',
     *     'quantity' => 50,
     *     'session' => 'Morning',
     *     'salary' => 600.00,
     *     'adjustment' => 50.00
     *   ], ...
     * ]
     */
    public function savePackingRecords(array $records): void
    {
        // Basic shape validation first
        $validated = validator(
            ['records' => $records],
            [
                'records' => ['required', 'array', 'min:1'],
                'records.*.employee_id' => ['required', 'integer', Rule::exists('employees', 'emp_id')],
                'records.*.product_id'  => ['required', 'integer', Rule::exists('packing_product', 'id')],
                'records.*.date'        => ['required', 'date'],
                'records.*.quantity'    => ['required', 'integer', 'min:1'],
                'records.*.session'     => ['nullable', 'string', Rule::in(['Morning', 'Evening'])],
                'records.*.salary'      => ['nullable', 'numeric', 'min:0'],
                'records.*.adjustment'  => ['nullable', 'numeric'],
            ],
            [],
            [] // attribute names
        )->validate();

        // Load needed product rates in one go
        $productRates = PackingProduct::query()
            ->whereIn('id', collect($records)->pluck('product_id')->unique()->values())
            ->pluck('per_rate', 'id');

        DB::transaction(function () use ($validated, $productRates) {
            foreach ($validated['records'] as $row) {
                $perRate = (float) ($productRates[$row['product_id']] ?? 0);
                $quantity = (int) $row['quantity'];

                // If salary not provided or zero, auto-calc from per_rate * quantity
                $salary = isset($row['salary']) && $row['salary'] !== ''
                    ? (float) $row['salary']
                    : round($perRate * $quantity, 2);

                $adjustment = isset($row['adjustment']) && $row['adjustment'] !== ''
                    ? (float) $row['adjustment']
                    : 0.0;

                $total = round($salary + $adjustment, 2);

                PackingSalary::create([
                    'employee_id'  => (int) $row['employee_id'],
                    'product_id'   => (int) $row['product_id'],
                    'date_packed'  => Carbon::parse($row['date'])->toDateString(),
                    'quentity'     => $quantity,
                    'session'      => $row['session'] ?? 'Morning',
                    'salary'       => $salary,
                    'adjustment'   => $adjustment,
                    'total_salary' => $total,
                ]);
            }
        });

        session()->flash('packingSaved', 'Packing salary records saved successfully!');
        $this->dispatch('recordsSaved'); // Alpine listener will clear UI and close modal
    }

    public function render()
    {
        // “Today Records”
        $todayRecords = PackingSalary::query()
            ->with(['employee:emp_id,fname', 'product:id,product_name'])
            ->whereDate('date_packed', now()->toDateString())
            ->orderByDesc('id')
            ->get();

        // “Total Records This Month” summary (grouped by product)
        $monthlySummary = PackingSalary::query()
            ->with(['product:id,product_name'])
            ->selectRaw('product_id, SUM(quentity) as total_quantity, SUM(total_salary) as total_amount')
            ->whereYear('date_packed', now()->year)
            ->whereMonth('date_packed', now()->month)
            ->groupBy('product_id')
            ->get();

        // Employees & Products for dropdowns / lists
        $this->employees = Employee::query()
            ->select('emp_id', 'fname')
            ->orderBy('fname')
            ->get()
            ->map(fn ($e) => ['emp_id' => $e->emp_id, 'fname' => $e->fname])
            ->toArray();

        $this->products = PackingProduct::query()
            ->select('id', 'product_name', 'per_rate')
            ->orderBy('product_name')
            ->get()
            ->map(fn ($p) => ['id' => $p->id, 'product_name' => $p->product_name, 'per_rate' => (float) $p->per_rate])
            ->toArray();

        return view('livewire.admin.packing-management', [
            'todayRecords'   => $todayRecords,
            'monthlySummary' => $monthlySummary,
        ]);
    }
}