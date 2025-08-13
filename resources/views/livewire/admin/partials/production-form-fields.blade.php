<div>
    <label class="block text-sm font-medium mb-1">Employee Name</label>
    <div class="flex gap-2 items-center">
        <select wire:model="form.employee_id"
            class="form-control flex-1 border border-gray-300 rounded-md p-2">
            <option value="">Select Employee</option>
            @foreach ($employees as $employee)
            <option value="{{ $employee->emp_id }}">{{ $employee->fname }}</option>
            @endforeach
        </select>
        <button wire:click="showAllEmployees" type="button"
            class="p-2 px-3 rounded-md bg-blue-500 text-white text-xl leading-none hover:bg-blue-600">
            +
        </button>
    </div>
    @error('form.employee_id')
    <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium mb-1">Role</label>
    <select wire:model="form.work_type"
        class="form-control w-full border border-gray-300 rounded-md p-2">
        <option value="">Select Role</option>
        <option value="cutter">Cutter</option>
        <option value="roller">Roller</option>
        <option value="dryer">Dryer</option>
        <option value="packer">Packer</option>
        <option value="worker">Worker</option>
    </select>
    @error('form.work_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

<div>
    <label class="block text-sm font-medium mb-1">Date</label>
    <input type="date" wire:model="form.date"
        class="form-control w-full border border-gray-300 rounded-md p-2">
    @error('form.date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

<div>
    <label class="block text-sm font-medium mb-1">Total Quantity (kg)</label>
    <input type="number" wire:model="form.quantity"
        class="form-control w-full border border-gray-300 rounded-md p-2"
        placeholder="Enter quantity"
        wire:change="calculateTotalSalary">
    @error('form.quantity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

<div>
    <label class="block text-sm font-medium mb-1">Worked Quantity</label>
    <input type="number" wire:model="form.worked_quantity"
        class="form-control w-full border border-gray-300 rounded-md p-2"
        placeholder="Enter worked quantity"
        wire:change="calculateTotalSalary">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Additional Salary</label>
    <input type="number" wire:model="form.additional_salary"
        class="form-control w-full border border-gray-300 rounded-md p-2"
        placeholder="Enter additional salary"
        wire:change="calculateTotalSalary">
</div>

<div>
    <label class="block text-sm font-medium mb-1">Total Amount (LKR)</label>
    <input type="text" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md p-2"
        value="{{ number_format($form['total_salary'], 2) }}" readonly>
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Description</label>
    <textarea wire:model="form.description"
        class="form-control w-full border border-gray-300 rounded-md p-2" rows="3"
        placeholder="Enter production details"></textarea>
    @error('form.description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>