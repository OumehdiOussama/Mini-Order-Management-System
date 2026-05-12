<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    /**
     * Create a new customer.
     */
    public function createCustomer(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Update an existing customer.
     */
    public function updateCustomer(Customer $customer, array $data): Customer
    {
        $customer->update($data);
        return $customer;
    }

    /**
     * Delete a customer.
     */
    public function deleteCustomer(Customer $customer): void
    {
        $customer->delete();
    }
}
