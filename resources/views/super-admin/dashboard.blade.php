@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('page-title', 'Platform Overview')

@section(
    'page-description',
    'Monitor tenants, subscriptions, usage and platform health.'
)

@section('page-actions')

    <button class="admin-button secondary">
        Export Report
    </button>

    <button class="admin-button primary">
        + Add Tenant
    </button>

@endsection


@section('content')

    @include('layouts.components.admin-page-header')


    {{-- Platform statistics --}}
    <section class="admin-stats-grid">

        <x-admin.stat-card label="Total Tenants" value="128" change="+12.4% this month" icon="▣" />

        <x-admin.stat-card label="Active Tenants" value="96" change="+8.2% this month" icon="✓" />

        <x-admin.stat-card label="Trial Tenants" value="21" change="+5 new trials" icon="◷" />

        <x-admin.stat-card label="Monthly Revenue" value="₹24.8L" change="+14.6% this month" icon="₹" />

    </section>


    {{-- Main analytics --}}
    <section class="admin-dashboard-grid">

        <div class="admin-card admin-card-large">

            <div class="admin-card-header">

                <div>
                    <h2>Revenue Overview</h2>
                    <p>Subscription revenue over the last 6 months</p>
                </div>

                <select class="admin-select">
                    <option>Last 6 months</option>
                    <option>Last 12 months</option>
                    <option>This year</option>
                </select>

            </div>


            <div class="admin-chart">

                <div class="chart-y-axis">
                    <span>₹30L</span>
                    <span>₹20L</span>
                    <span>₹10L</span>
                    <span>₹0</span>
                </div>

                <div class="chart-area">

                    <div class="chart-grid-line" style="top: 0;"></div>
                    <div class="chart-grid-line" style="top: 33%;"></div>
                    <div class="chart-grid-line" style="top: 66%;"></div>
                    <div class="chart-grid-line" style="top: 100%;"></div>

                    <svg class="revenue-chart" viewBox="0 0 600 220" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="revenueGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-opacity="0.25" />
                                <stop offset="100%" stop-opacity="0" />
                            </linearGradient>
                        </defs>

                        <path d="M0,175
                                   C50,165 75,150 120,158
                                   C170,168 190,120 240,132
                                   C285,145 310,105 355,115
                                   C405,125 430,78 475,92
                                   C520,105 550,55 600,62
                                   L600,220
                                   L0,220 Z" fill="url(#revenueGradient)" />

                        <path d="M0,175
                                   C50,165 75,150 120,158
                                   C170,168 190,120 240,132
                                   C285,145 310,105 355,115
                                   C405,125 430,78 475,92
                                   C520,105 550,55 600,62" fill="none" stroke="currentColor" stroke-width="3" />
                    </svg>

                    <div class="chart-months">
                        <span>Apr</span>
                        <span>May</span>
                        <span>Jun</span>
                        <span>Jul</span>
                        <span>Aug</span>
                        <span>Sep</span>
                    </div>

                </div>

            </div>

        </div>


        {{-- Subscription distribution --}}
        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>Subscriptions</h2>
                    <p>Current subscription status</p>
                </div>

            </div>


            <div class="subscription-summary">

                <div class="donut-chart">
                    <div class="donut-inner">
                        <strong>128</strong>
                        <span>Total</span>
                    </div>
                </div>

                <div class="subscription-legend">

                    <div>
                        <span class="legend-dot active"></span>
                        <span>Active</span>
                        <strong>96</strong>
                    </div>

                    <div>
                        <span class="legend-dot trial"></span>
                        <span>Trial</span>
                        <strong>21</strong>
                    </div>

                    <div>
                        <span class="legend-dot warning"></span>
                        <span>Past Due</span>
                        <strong>4</strong>
                    </div>

                    <div>
                        <span class="legend-dot danger"></span>
                        <span>Suspended</span>
                        <strong>7</strong>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- Tenant growth + platform usage --}}
    <section class="admin-dashboard-grid">

        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>Tenant Growth</h2>
                    <p>New tenants created this month</p>
                </div>

                <span class="admin-card-value">
                    +18
                </span>

            </div>


            <div class="mini-bars">

                <div style="height: 42%;">
                    <span>Mon</span>
                </div>

                <div style="height: 58%;">
                    <span>Tue</span>
                </div>

                <div style="height: 35%;">
                    <span>Wed</span>
                </div>

                <div style="height: 74%;">
                    <span>Thu</span>
                </div>

                <div style="height: 61%;">
                    <span>Fri</span>
                </div>

                <div style="height: 88%;">
                    <span>Sat</span>
                </div>

                <div style="height: 68%;">
                    <span>Sun</span>
                </div>

            </div>

        </div>


        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>Platform Usage</h2>
                    <p>Current resource consumption</p>
                </div>

            </div>


            <div class="usage-list">

                <div class="usage-item">

                    <div class="usage-item-header">
                        <span>Users</span>
                        <strong>1,842 / 2,500</strong>
                    </div>

                    <div class="usage-progress">
                        <span style="width: 74%;"></span>
                    </div>

                </div>


                <div class="usage-item">

                    <div class="usage-item-header">
                        <span>Storage</span>
                        <strong>186 GB / 500 GB</strong>
                    </div>

                    <div class="usage-progress">
                        <span style="width: 37%;"></span>
                    </div>

                </div>


                <div class="usage-item">

                    <div class="usage-item-header">
                        <span>Orders</span>
                        <strong>18,421 / 25,000</strong>
                    </div>

                    <div class="usage-progress">
                        <span style="width: 74%;"></span>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- Recent tenants --}}
    <section class="admin-card">

        <div class="admin-card-header">

            <div>
                <h2>Recent Tenants</h2>
                <p>Latest companies added to the platform</p>
            </div>

            <a href="#" class="admin-link">
                View all →
            </a>

        </div>


        <div class="admin-table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Plan</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>
                            <div class="company-cell">
                                <div class="company-avatar">AF</div>
                                <div>
                                    <strong>ABC Furniture</strong>
                                    <span>abc@example.com</span>
                                </div>
                            </div>
                        </td>

                        <td>Professional</td>

                        <td>42</td>

                        <td>
                            <x-admin.status-badge status="active" />
                        </td>

                        <td>Today</td>

                        <td>
                            <button class="table-action">•••</button>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <div class="company-cell">
                                <div class="company-avatar">XT</div>
                                <div>
                                    <strong>XYZ Traders</strong>
                                    <span>admin@xyztraders.com</span>
                                </div>
                            </div>
                        </td>

                        <td>Free</td>

                        <td>8</td>

                        <td>
                            <x-admin.status-badge status="trial" />
                        </td>

                        <td>Yesterday</td>

                        <td>
                            <button class="table-action">•••</button>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <div class="company-cell">
                                <div class="company-avatar">GS</div>
                                <div>
                                    <strong>Global Services</strong>
                                    <span>hello@globalservices.com</span>
                                </div>
                            </div>
                        </td>

                        <td>Enterprise</td>

                        <td>126</td>

                        <td>
                            <x-admin.status-badge status="active" />
                        </td>

                        <td>2 days ago</td>

                        <td>
                            <button class="table-action">•••</button>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>


    {{-- Bottom section --}}
    <section class="admin-dashboard-grid">

        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>Recent Activity</h2>
                    <p>Latest platform events</p>
                </div>

                <a href="#" class="admin-link">
                    View all
                </a>

            </div>


            <div class="admin-activity-list">

                <x-admin.activity-item title="New tenant registered" description="ABC Furniture joined the platform."
                    time="12 minutes ago" />

                <x-admin.activity-item title="Subscription upgraded" description="Global Services upgraded to Enterprise."
                    time="48 minutes ago" />

                <x-admin.activity-item title="Feature enabled" description="Advanced Reports enabled for XYZ Traders."
                    time="2 hours ago" />

                <x-admin.activity-item title="Payment received" description="Professional plan payment completed."
                    time="3 hours ago" />

            </div>

        </div>


        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>System Health</h2>
                    <p>Platform services</p>
                </div>

                <span class="health-status">
                    All systems operational
                </span>

            </div>


            <div class="health-list">

                <div class="health-item">
                    <span>
                        <i class="health-dot healthy"></i>
                        Application
                    </span>

                    <strong>Healthy</strong>
                </div>

                <div class="health-item">
                    <span>
                        <i class="health-dot healthy"></i>
                        Database
                    </span>

                    <strong>Healthy</strong>
                </div>

                <div class="health-item">
                    <span>
                        <i class="health-dot healthy"></i>
                        Queue Workers
                    </span>

                    <strong>Healthy</strong>
                </div>

                <div class="health-item">
                    <span>
                        <i class="health-dot healthy"></i>
                        Scheduler
                    </span>

                    <strong>Healthy</strong>
                </div>

                <div class="health-item">
                    <span>
                        <i class="health-dot warning"></i>
                        Storage
                    </span>

                    <strong>78%</strong>
                </div>

            </div>

        </div>

    </section>

@endsection