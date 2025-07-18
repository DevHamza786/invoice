<?php

namespace App\Http\Controllers;

use App\Exports\AdminPaymentsExport;
use App\Http\Requests\CreateAdminPaymentRequest;
use App\Models\AdminPayment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\AdminPaymentRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminPaymentController extends AppBaseController
{
    /** @var AdminPaymentRepository */
    public $adminPaymentRepository;

    /**
     * @param  AdminPaymentRepository  $adminPaymentRepo
     */
    public function __construct(AdminPaymentRepository $adminPaymentRepo)
    {
        $this->adminPaymentRepository = $adminPaymentRepo;
    }

    /**
     * @return Application|Factory|View
     *
     */
    public function index(): View|Factory|Application
    {
        $invoice = Invoice::whereNotIn('status', [Invoice::PAID, Invoice::DRAFT])
            ->orderBy('created_at', 'desc')
            ->pluck('invoice_id', 'id')->toArray();

        return view('payments.index', compact('invoice'));
    }

    /**
     * @param  CreateAdminPaymentRequest  $request
     * @return mixed
     */
    public function store(CreateAdminPaymentRequest $request)
    {
        $input = $request->all();

        try {
            $payment = $this->adminPaymentRepository->store($input);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }

        return $this->sendResponse($payment, 'Payment saved successfully.');
    }

    /**
     * @param  AdminPayment  $payment
     * @return JsonResponse
     */
    public function edit(AdminPayment $payment): JsonResponse
    {
        $invoiceId = $payment->invoice->id;
        $payment['currencyCode'] = getInvoiceCurrencyIcon($payment->invoice->currency_id);
        $payment['invoice'] = $payment->invoice->invoice_id;
        $payment['DueAmount'] = $this->getInvoiceDueAmount($invoiceId);

        return $this->sendResponse($payment, 'payment retrieved successfully.');
    }

    /**
     * @param  CreateAdminPaymentRequest  $request
     * @return JsonResponse
     */
    public function update(CreateAdminPaymentRequest $request): JsonResponse
    {
        $input = $request->all();
        $this->adminPaymentRepository->updatePayment($input);

        return $this->sendSuccess('Payment updated successfully.');
    }

    /**
     * @param  AdminPayment  $payment
     * @return JsonResponse
     */
    public function destroy(AdminPayment $payment): JsonResponse
    {
        $this->adminPaymentRepository->adminDeletePayment($payment);

        return $this->sendSuccess('Payment deleted successfully.');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getInvoiceDueAmount($id): mixed
    {
        $data = [];
        /** @var Invoice $invoice */
        $invoice = Invoice::whereId($id)->with('payments')->firstOrFail();

        $paidAmount = $invoice->payments()->where('is_approved', Payment::APPROVED)->sum('amount');
        $dueAmount = $invoice->final_amount - $paidAmount;

        $data['currencyCode'] = getInvoiceCurrencyIcon($invoice->currency_id);
        $data['totalDueAmount'] = $dueAmount;
        $data['totalPaidAmount'] = $paidAmount;

        return $this->sendResponse($data, 'Invoice Due Amount Retrieve successfully');
    }

    /**
     * @return BinaryFileResponse
     */
    public function exportAdminPaymentsExcel(Request $request): BinaryFileResponse
    {
        return Excel::download(new AdminPaymentsExport($request->date), 'Payment-Excel.xlsx');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function exportAdminPaymentsPDF(Request $request)
    {
        $timeEntryDate = explode(' - ',  $request->date);
        $startDate = Carbon::parse($timeEntryDate[0])->format('Y-m-d');
        $endDate = Carbon::parse($timeEntryDate[1])->format('Y-m-d');
        $data['adminPayments'] = AdminPayment::whereBetween('payment_date', [$startDate, $endDate])->with(['invoice.client.user'])->get();
        $paymentsPDF = PDF::loadView('payments.export_pdf', $data);

        return $paymentsPDF->download('payments.pdf');
    }

    public function getCurrentDateFormat()
    {
        return currentDateFormat();
    }
}
