<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService,
        protected ViewFactory $viewFactory
    ) {}

    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $customers = $this->customerService->getForUser($user);

        return $this->viewFactory->make('customers.index', [
            'customers' => $customers,
        ]);
    }

    public function create(): View
    {
        return $this->viewFactory->make('customers.create');
    }

    public function store(
        StoreCustomerRequest $request
    ): RedirectResponse {
        /** @var User $user */
        $user = $request->user();

        $customer = $this->customerService->create(
            $user,
            $request->validated()
        );

        return redirect()
            ->route('customers.show', $customer->id)
            ->with('success', 'Customer created successfully.');
    }

    public function show(
        Request $request,
        int $customer
    ): View {
        /** @var User $user */
        $user = $request->user();

        $customerModel = $this->customerService->findForUser(
            $user,
            $customer
        );

        return $this->viewFactory->make('customers.show', [
            'customer' => $customerModel,
        ]);
    }

    public function edit(
        Request $request,
        int $customer
    ): View {
        /** @var User $user */
        $user = $request->user();

        $customerModel = $this->customerService->findForUser(
            $user,
            $customer
        );

        return $this->viewFactory->make('customers.edit', [
            'customer' => $customerModel,
        ]);
    }

    public function update(
        UpdateCustomerRequest $request,
        int $customer
    ): RedirectResponse {
        /** @var User $user */
        $user = $request->user();

        $customerModel = $this->customerService->update(
            $user,
            $customer,
            $request->validated()
        );

        return redirect()
            ->route('customers.show', $customerModel->id)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(
        Request $request,
        int $customer
    ): RedirectResponse {
        /** @var User $user */
        $user = $request->user();

        $this->customerService->delete(
            $user,
            $customer
        );

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
