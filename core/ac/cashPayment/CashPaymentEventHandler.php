<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\core\ac\cashPayment;

/**
 * Description of CashPaymentEventHandler
 *
 * @author vaishali
 */
class CashPaymentEventHandler extends \app\core\ac\base\VoucherBaseEventHandler  {
        
    public function afterFetch($criteriaparam) {
        parent::afterFetch($criteriaparam);
                
        $this->bo->vch_tran->getColumn("dc")->default="D";
        $this->bo->setTranColDefault('vch_tran', 'dc', "D");
       if($this->bo->voucher_id=="" or $this->bo->voucher_id=="-1")
        {
            $this->bo->dc='C';
        } 
    }
}
