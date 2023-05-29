<?php

namespace Esyede\BiSnap\IntrabankTransfer;

use Esyede\BiSnap\Amount;
use Esyede\BiSnap\Contracts\ServicePayload;
use Esyede\BiSnap\Contracts\AdditionalInfo;

class Payload implements ServicePayload
{
    public $partnerReferenceNo;
    public $amount;
    public $beneficiaryAccountNo;
    public $sourceAccountNo;
    public $transactionDate;
    public $beneficiaryEmail = null;
    public $currency = 'IDR';
    public $customerReference = null;
    public $feeType = null;
    public $remark = null;
    public $additionalInfo = null;

    /**
     * Constructor
     *
     * @param string $partnerReferenceNo
     * @param Amount|int|float $amount
     * @param string $beneficiaryAccountNo
     * @param string $sourceAccountNo
     * @param string $transactionDate
     * @param string|null $beneficiaryEmail
     * @param string $currency
     * @param string $customerReference
     * @param string|null $feeType
     * @param string|null $remark
     * @param array|null $additionalInfo
     */
    public function __construct(
        $partnerReferenceNo,
        Amount $amount,
        $beneficiaryAccountNo,
        $sourceAccountNo,
        $transactionDate,
        $beneficiaryEmail = null,
        $currency = 'IDR',
        $customerReference = null,
        $feeType = null,
        $remark = null,
        $additionalInfo = null
    ) {
        $this->partnerReferenceNo = $partnerReferenceNo;
        $this->amount = $amount;
        $this->beneficiaryAccountNo = $beneficiaryAccountNo;
        $this->sourceAccountNo = $sourceAccountNo;
        $this->transactionDate = $transactionDate;
        $this->beneficiaryEmail = $beneficiaryEmail;
        $this->currency = $currency;
        $this->customerReference = $customerReference;
        $this->feeType = $feeType;
        $this->remark = $remark;
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'partnerReferenceNo' => $this->partnerReferenceNo,
            'amount' => $this->amount->toArray(),
            'beneficiaryAccountNo' => $this->beneficiaryAccountNo,
            'sourceAccountNo' => $this->sourceAccountNo,
            'transactionDate' => $this->transactionDate,
            'beneficiaryEmail' => $this->beneficiaryEmail,
            'currency' => $this->currency,
            'customerReference' => $this->customerReference,
            'feeType' => $this->feeType,
            'remark' => $this->remark,
        ];

        if ($this->additionalInfo) {
            $data['additionalInfo'] = ($this->additionalInfo instanceof AdditionalInfo)
                ? $this->additionalInfo->toArray()
                : $this->additionalInfo;
        }

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
