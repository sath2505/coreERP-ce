<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use  vsla\utils\FormatHelper;
namespace app\core\ac\reports\trialBalance;

/**
 * Description of TrialBalanceValidator
 *
 * @author Ravindra
 */
class TrialBalance extends \app\cwf\fwShell\base\ReportBase {
    //use  vsla\utils\FormatHelper;
    public function onRequestReport($rptOption) {
        parent::onRequestReport($rptOption);     
        
        if(strtotime($rptOption->rptParams["pfrom_date"]) > strtotime($rptOption->rptParams["pto_date"])){
            array_push($rptOption->brokenRules, 'From Date should be less than To Date.');
        }
        
        if($this->allowConsolidated && ($rptOption->rptParams['pbranch_id']=='' || $rptOption->rptParams['pbranch_id']=='-1')){
            array_push($rptOption->brokenRules, 'Please Select Branch.');
        } 
        
        if($this->allowConsolidated && $rptOption->rptParams['pbranch_id']==0) {
            if($rptOption->rptParams['pfilter_gst_state']) {
                $newBrId = (\app\cwf\vsla\security\SessionManager::getSessionVariable('company_id') * 1000000) + 500000;
                $newBrId = $newBrId + intval(\app\cwf\vsla\security\SessionManager::getBranchGstInfo()['gst_state_id']);
                $rptOption->rptParams['pbranch_id'] = $newBrId;
            }
        }
        
        if($rptOption->rptParams['ptrial_balance_type']==-1){
            array_push($rptOption->brokenRules, 'Please Select Trial Balance.');
        }
        
        //Normal Columnar Reports
        
        if ($rptOption->rptParams['pwithout_groups']==1 AND $rptOption->rptParams['ptrial_balance_type']==1){ 
                $rptOption->rptParams['preport_period']='Between '. \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date'])
                    .' And ' . \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );

                //for opening balance with out group
                if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)){
                    $rptOption->rptName='TBOpening_CTNoGroup';  
                    $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                //for Transation During Period with out group
                else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='TBClosing_CTNoGroup';
                }
                //for Transation During Period AND Opening balance with out group
                else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='TBOpening_CTNoGroup';        
                    $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                else{ //for Trial Balance with out group
                    $rptOption->rptName='TBClosing_CTNoGroup';
                    $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );
                }   
        }  
        elseif ($rptOption->rptParams['pwithout_groups']==0 AND $rptOption->rptParams['ptrial_balance_type']==1){

                $rptOption->rptParams['preport_period']='Between '. \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date'])
                       .' And ' . \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );

                //for opening balance with  group
                if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)){
                    $rptOption->rptName='TBOpening_CTGroup'; 
                    $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                //for Transation During Period with  group
                else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='TBClosing_CTGroup';
                }
                //for Transation During Period AND Opening balance with group
                else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)){
                         $rptOption->rptName='TBOpening_CTGroup';  
                         $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                else{ //for Trial Balance with  group
                   $rptOption->rptName='TBClosing_CTGroup';  
                   $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );
                }   
        }
        
        //Normal Reports
        if ($rptOption->rptParams['pwithout_groups']==1 AND $rptOption->rptParams['ptrial_balance_type']==0) { 
            $rptOption->rptParams['preport_period']='Between '.\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date']).
                    ' And '.\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date']);
             //for opening balance without group
            if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)) {
               $rptOption->rptName='TrialBalanceOpBalNoGroup';  
            }
            //for Transation During Period with out group
            else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)) {
                     $rptOption->rptName='TrialBalanceTxnNoGroup';
            }
            //for Transation During Period AND Opening balance with out group
            else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)) {
                      $rptOption->rptName='TrialBalanceOpBalTxnNoGroup';        
            }
            else { //for Trial Balance with out group
                $rptOption->rptName='TrialBalanceNoGroup';
                $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date']);
            }   
        }  
        elseif ($rptOption->rptParams['pwithout_groups']==0 AND $rptOption->rptParams['ptrial_balance_type']==0) {
            $rptOption->rptParams['preport_period']='Between '.\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date']).
                ' And '.\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date']);
            //for opening balance with  group
            if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)) {
              $rptOption->rptName='TrialBalanceOpBal';  
            }
            //for Transation During Period with  group
            else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)) {
                    $rptOption->rptName='TrialBalanceTxn';
            }
            //for Transation During Period AND Opening balance with group
            else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)) {
                     $rptOption->rptName='TrialBalanceOpBalTxn';        
            }
            else { //for Trial Balance with  group
               $rptOption->rptName='TrialBalance';
               $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date']);
            }
        }
    return $rptOption;
    }  
}