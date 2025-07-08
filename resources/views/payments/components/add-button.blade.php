<div class="dropdown d-flex align-items-center me-4 me-md-2" wire:ignore>
    <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
            id="apptmentFilterBtn"
            data-bs-auto-close="outside"
            data-bs-toggle="dropdown" aria-expanded="false">
        <p class="text-center">
            <i class='fas fa-filter'></i>
        </p>
    </button>
    <div class="dropdown-menu py-0" aria-labelledby="apptmentFilterBtn">
        <div class="text-start border-bottom py-4 px-7">
            <h3 class="text-gray-900 mb-0">{{__('messages.common.filter_options')}}</h3>
        </div>
        <div class="p-5">
            <div class="mb-5">
                <label for="filterBtn" class="form-label">{{__('messages.client.created_at')}}:</label>
                <input type="text" class="form-control form-control-solid flatpickr-input"  placeholder="Pick date range" id="paymentDateFilter"/>
            </div>
        </div>
    </div>
</div>
<div class="me-2 mb-3 mb-sm-0">
    <a id="adminPaymentExcelExport" data-turbo="false" type="button" class="btn btn-outline-success">
        <i class="fas fa-file-excel me-1"></i> {{__('messages.invoice.excel_export')}}
    </a>
</div>
<div class="my-3 my-sm-3">
    <a id="adminPaymentPdfExport" type="button" class="btn btn-outline-info me-2" data-turbo="false">
        <i class="fas fa-file-pdf me-1"></i> {{__('messages.pdf_export')}}
    </a>
</div>
<div>
    <button type="button" class="btn btn-primary addPayment">
        {{ __('messages.payment.add_payment') }}
    </button>
</div>
