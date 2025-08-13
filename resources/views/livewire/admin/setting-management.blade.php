<div>
    <!-- Settings Module -->
        <h2 class="text-[#0d151c] text-2xl font-bold leading-tight tracking-[-0.015em] px-3 py-2">System Settings</h2>

        <div class="p-2">
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Payroll Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">EPF Rate (%)</label>
                        <input type="number" class="form-control w-full" value="8">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">ETF Rate (%)</label>
                        <input type="number" class="form-control w-full" value="3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tax Threshold (LKR)</label>
                        <input type="number" class="form-control w-full" value="100000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tax Rate (%)</label>
                        <input type="number" class="form-control w-full" value="6">
                    </div>
                </div>
                <div class="mt-6">
                    <button class="btn-primary px-6 py-2 rounded-lg">Save Settings</button>
                </div>
            </div>

            <div class="card p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Production Rate Configuration</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left px-4 py-3">Role</th>
                                <th class="text-left px-4 py-3">Magi (LKR/kg)</th>
                                <th class="text-left px-4 py-3">Papadam (LKR/kg)</th>
                                <th class="text-left px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-3">Production Worker</td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-control w-24" value="65">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-control w-24" value="80">
                                </td>
                                <td class="px-4 py-3">
                                    <button class="text-blue-600 hover:text-blue-800"><i class="fas fa-save"></i>
                                        Update</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Supervisor</td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-control w-24" value="85">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-control w-24" value="100">
                                </td>
                                <td class="px-4 py-3">
                                    <button class="text-blue-600 hover:text-blue-800"><i class="fas fa-save"></i>
                                        Update</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Quality Control</td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-control w-24" value="75">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-control w-24" value="90">
                                </td>
                                <td class="px-4 py-3">
                                    <button class="text-blue-600 hover:text-blue-800"><i class="fas fa-save"></i>
                                        Update</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4">System Preferences</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center mb-4">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" checked>
                            <span class="ml-2 text-sm">Enable Email Notifications</span>
                        </label>
                        <label class="flex items-center mb-4">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" checked>
                            <span class="ml-2 text-sm">Auto-calculate Salary on Production Entry</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-sm">Enable Two-Factor Authentication</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Default Currency</label>
                        <select class="form-control w-full">
                            <option value="LKR" selected>Sri Lankan Rupee (LKR)</option>
                            <option value="USD">US Dollar (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                        </select>

                        <label class="block text-sm font-medium mb-1 mt-4">Date Format</label>
                        <select class="form-control w-full">
                            <option value="YYYY-MM-DD" selected>YYYY-MM-DD (2024-03-28)</option>
                            <option value="DD/MM/YYYY">DD/MM/YYYY (28/03/2024)</option>
                            <option value="MM/DD/YYYY">MM/DD/YYYY (03/28/2024)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <button class="btn-primary px-6 py-2 rounded-lg">Save Preferences</button>
                </div>
            </div>
        </div>
</div>