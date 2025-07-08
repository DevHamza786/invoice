@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard') }}
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-12">
                @include('flash::message')
                <div class="row g-4">
                    {{-- Clients Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{ route('clients.index') }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-primary shadow-lg rounded-4 d-flex align-items-center justify-content-between">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-users widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <h2 class="widget-value mb-1 text-white">{{ formatTotalAmount($total_clients) }}</h2>
                                    <h3 class="widget-label mb-0 fs-6">{{ __('messages.admin_dashboard.total_clients') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- Total Invoices Amount Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{ route('invoices.index') }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-success shadow-lg rounded-4 d-flex align-items-center justify-content-between">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-money-bill-wave widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <div class="widget-amount mb-1">{{ getCurrencyAmount($invoice_amount, false) }}
                                    </div>
                                    <h3 class="widget-label mb-0 fs-6">{{ __('messages.admin_dashboard.total_amount') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- Recieved Amount Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{route('invoices.index',['status'=>2]) }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-info shadow-lg rounded-4 d-flex align-items-center justify-content-between">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-hand-holding-usd widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <div class="widget-amount mb-1">{{ getCurrencyAmount($paid_amount, false) }}
                                    </div>
                                    <h3 class="widget-label mb-0 fs-6">{{ __('messages.admin_dashboard.total_paid') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{--Partially Paid Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{ route('invoices.index',['status'=>3]) }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-warning shadow-lg rounded-4 d-flex align-items-center justify-content-between">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-money-check-alt widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <div class="widget-amount mb-1">{{ getCurrencyAmount($due_amount, false) }}
                                    </div>
                                    <h3 class="widget-label mb-0 fs-6">{{ __('messages.admin_dashboard.total_due') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- Products Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{ route('products.index') }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-secondary shadow-lg rounded-4 d-flex align-items-center justify-content-between">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-boxes widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <h2 class="widget-value mb-1 text-white">{{ formatTotalAmount($total_products) }}</h2>
                                    <h3 class="widget-label mb-0 fs-6">{{ __('messages.admin_dashboard.total_products') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- Total Invoices Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{ route('invoices.index') }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-danger shadow-lg rounded-4 d-flex align-items-center justify-content-between">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-file-invoice-dollar widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <h2 class="widget-value mb-1 text-white">{{ formatTotalAmount($total_invoices) }}</h2>
                                    <h3 class="widget-label mb-0 fs-6">{{ __('messages.admin_dashboard.total_invoices') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{--Paid Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{ route('invoices.index',['status'=>2]) }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-dark shadow-lg rounded-4 d-flex align-items-center justify-content-between">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-check-circle widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <h2 class="widget-value mb-1 text-white">{{ formatTotalAmount($paid_invoices) }}</h2>
                                    <h3 class="widget-label mb-0 fs-6">{{ __('messages.admin_dashboard.total_paid_invoices') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{--Unapid Widget --}}
                    <div class="col-xxl-3 col-xl-4 col-sm-6">
                        <a href="{{ route('invoices.index',['status'=>1]) }}" class="text-decoration-none">
                            <div class="dashboard-widget bg-gradient-primary shadow-lg rounded-4 d-flex align-items-center justify-content-between" style="padding: 1.5rem 1rem !important;">
                                <div class="widget-icon-wrapper">
                                    <i class="fas fa-exclamation-circle widget-icon"></i>
                                </div>
                                <div class="widget-content text-end text-white">
                                    <h2 class="widget-value mb-1 text-white">{{ formatTotalAmount($unpaid_invoices) }}</h2>
                                    <h3 class="widget-label mb-0 fs-6" style="font-size: 16px;">{{ __('messages.admin_dashboard.total_unpaid_invoices') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="">
                    <div class="card mt-3">
                        <div class="card-body p-5">
                            <div class="card-header border-0 pt-5">
                                <h3 class="mb-0">{{  __('messages.admin_dashboard.income_overview') }}</h3>
                                <div class="ms-auto">
                                    <div id="rightData" class="date-picker-space">
                                        <input class="form-control " id="time_range">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-lg-6 p-0">
                                <div class="">
                                    <div id="yearly_income_overview-container" class="pt-2">
                                        <canvas id="yearly_income_chart_canvas" height="200" width="905"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-12 mb-5 mb-xl-0">
                <div class="card">
                    <div class="card-header pb-0 px-10">
                        <h3 class="mb-0">{{  __('messages.admin_dashboard.payment_overview') }}</h3>
                    </div>
                    <div class="card-body pt-7">
                        <div id="payment-overview-container" class="justify-align-center">
                            <canvas id="payment_overview"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-12">
                <div class="card">
                    <div class="card-header pb-0 px-10">
                        <h3 class="mb-0">{{  __('messages.admin_dashboard.invoice_overview') }}</h3>
                    </div>
                    <div class="card-body pt-7">
                        <div id="invoice-overview-container" class="justify-align-center">
                            <canvas id="invoice_overview"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{ Form::hidden('currency',  getCurrencySymbol(),['id' => 'currency']) }}

<style>
    .dashboard-widget {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dashboard-widget:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }

    .dashboard-widget::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
        z-index: 1;
    }

    .widget-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .dashboard-widget:hover .widget-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        background: rgba(255, 255, 255, 0.25);
    }

    .widget-icon {
        font-size: 1.75rem;
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    .widget-content {
        position: relative;
        z-index: 2;
    }

    .widget-value {
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        margin-bottom: 0.25rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .widget-label {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dashboard-widget {
        padding: 1.5rem !important;
    }

    .widget-amount {
        color: #fff !important;
        font-size: 1.25rem !important;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.2em;
        justify-content: flex-end;
    }

    .widget-currency {
        color: #fff !important;
        font-size: 1.1rem !important;
        font-weight: 600;
        margin-right: 0.1em;
    }
</style>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }
</style>
@endsection
