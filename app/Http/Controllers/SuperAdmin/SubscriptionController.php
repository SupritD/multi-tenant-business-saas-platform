<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {}

    public function index(): View
    {
        $subscriptions = Subscription::query()
            ->with(['tenant', 'plan'])
            ->latest('id')
            ->paginate(15);

        return view('super-admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function show(int $subscription): View
    {
        $subscriptionModel = Subscription::query()
            ->with(['tenant', 'plan'])
            ->findOrFail($subscription);

        return view('super-admin.subscriptions.show', [
            'subscription' => $subscriptionModel,
        ]);
    }

    public function activate(int $subscription): RedirectResponse
    {
        $subscriptionModel = Subscription::query()
            ->findOrFail($subscription);

        $this->subscriptionService->activate($subscriptionModel);

        return back()->with(
            'success',
            'Subscription activated successfully.'
        );
    }

    public function cancel(int $subscription): RedirectResponse
    {
        $subscriptionModel = Subscription::query()
            ->findOrFail($subscription);

        $this->subscriptionService->cancel($subscriptionModel);

        return back()->with(
            'success',
            'Subscription cancelled successfully.'
        );
    }

    public function suspend(int $subscription): RedirectResponse
    {
        $subscriptionModel = Subscription::query()
            ->findOrFail($subscription);

        $this->subscriptionService->suspend($subscriptionModel);

        return back()->with(
            'success',
            'Subscription suspended successfully.'
        );
    }
}
