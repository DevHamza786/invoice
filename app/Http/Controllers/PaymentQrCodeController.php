<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentQrCodeRequest;
use App\Http\Requests\UpdatePaymentQrCodeRequest;
use Illuminate\Http\Request;
use App\Repositories\PaymentQrCodeRepository;
use Exception;
use App\Models\PaymentQrCode;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PaymentQrCodeController extends AppBaseController
{
    /** @var PaymentQrCodeRepository */
    public $paymentQrCodeRepository;

    /**
     * @param  PaymentQrCodeRepository  $paymentqrcoderepo
     */
    public function __construct(PaymentQrCodeRepository $paymentQrCodeRepo)
    {
        $this->paymentQrCodeRepository = $paymentQrCodeRepo;
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('payment_qr_codes.index');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function store(CreatePaymentQrCodeRequest $request)
    {
        $input = $request->all();
        $this->paymentQrCodeRepository->store($input);

        return $this->sendSuccess('Payment QR Code saved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param PaymentQrCode  $paymentQrCode
     * @return mixed
     */
    public function edit(PaymentQrCode $paymentQrCode)
    {
        return $this->sendResponse($paymentQrCode, 'Payment QR Code data retrieved successfully.');
    }

    /**
     * @param  UpdatePaymentQrCodeRequest  $request
     * @return mixed
     */
    public function update(UpdatePaymentQrCodeRequest $request, PaymentQrCode $paymentQrCode)
    {
        $input = $request->all();
        $this->paymentQrCodeRepository->update($input, $paymentQrCode);

        return $this->sendSuccess('Payment QR Code updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param   PaymentQrCode  $paymentQrCode
     * @return mixed
     */
    public function destroy(PaymentQrCode $paymentQrCode)
    {
        $paymentQrCode->delete();

        return $this->sendSuccess('Payment QR Code deleted successfully.');
    }

    public function defaultStatus(PaymentQrCode $paymentQrCode)
    {
        $status = !$paymentQrCode->is_default;

        PaymentQrCode::query()->update(['is_default' => 0]);
        $paymentQrCode->update(['is_default' => $status]);

        return $this->sendSuccess('Payment QR Code Status updated successfully.');
    }
}
