<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
class Cashtransactions_IndexController extends Zend_Controller_Action {
	public function init() 
        {
                    $this->view->pageTitle='Cash Transactions';
                    $this->view->title = "Transaction";
            $this->view->adm = new App_Model_Adm();
            $this->view->dateconvertor = new App_Model_dateConvertor();

        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if(!$data){
                $this->_redirect('index/login'); // once session get expired it will redirect to Login page
        }

        $sessionName = new Zend_Session_Namespace('ourbank');
        $userid=$this->view->createdby = $sessionName->primaryuserid; // get the stored session id 

        $login=new App_Model_Users();
        $loginname=$login->username($userid);
        foreach($loginname as $loginname) {
            $this->view->username=$loginname['username']; // get the user name
        }

	}

	public function indexAction() {

		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data){
			$this->_redirect('index/login');
		}
                $path = $this->view->baseUrl();
                $username = $this->view->username;
		$receiptsform = new Cashtransactions_Form_Cashtransactions($path);
		$this->view->form = $receiptsform;
                $receipts= new Receipts_Model_Receipts();

                $receipts= new Receipts_Model_Receipts();
                $mainBranch = $receipts->getHeadOffice($this->view->username);                                    //checking the office wrt username
                foreach($mainBranch as $mainBranch) { if($mainBranch) { $branchid= $mainBranch['id']; }} 

                $glcode=$receipts->listOfglcode();
                foreach($glcode as $glcodes) {
                    $receiptsform->toglcode->addMultiOption($glcodes['id'],$glcodes['header']." -[".$glcodes['glcode']."]");
                }

		$select = $receipts->paymenttype();
		foreach ($select as $paymenttype1){
			$receiptsform->paymenttype->addMultiOption($paymenttype1['id'],$paymenttype1['id']." -[".$paymenttype1['description']."]");
		}

		if ($this->_request->isPost() && $this->_request->getPost('Submit')) {

			$formData = $this->_request->getPost();
			if ($this->_request->isPost()) {

				$paymenttype=$this->view->paymenttype=$this->_request->getParam('paymenttype'); 
				if( $paymenttype ==1 || $paymenttype ==""  ) {
					$receiptsform->paymenttype_details->setRequired(false);
				}

				if ($receiptsform->isValid($formData)) {

					$toglcode = $this->_request->getParam('toglcode'); 
					$toglsubcodeid = $this->_request->getParam('toglsubcodeid');
					$amount = $this->_request->getParam('amount');
					$transactiondate = $this->_request->getParam('transactiondate');
					$description = $this->_request->getParam('description');
					$paymenttype_details = $this->_request->getParam('paymenttype_details');
                                        $balance1 = $this->_request->getParam('amount');
                                        $balance2 = $this->_request->getParam('balance2');
                                  $balance = $balance1 + $balance2; $balance = $balance.'.00';   //to display the latest ledger balance after amount is entered

                                        $receipts= new Receipts_Model_Receipts();                                   //get the ledger codes

                                        if ($toglcode) {
                                                    $toledgercode=$receipts->listOfledgercode($toglcode);
                                                    foreach($toledgercode as $toledgercodes) {
                                                        $toledgertype=$toledgercodes['name'];
                                                    } }
                                        $flag=0;
                                        if($toglsubcodeid=="") {
                                             echo "<font color='red'><b> Select the GL code  and GL subcode again</b> </font>";$flag=1;
                                        }
                                         else {

                        $sessionName = new Zend_Session_Namespace('ourbank');
                        $user_id = $sessionName->primaryuserid;
                        $receipts= new Receipts_Model_Receipts();

                        if($toledgertype=="Income") {                                       //check the table related to ledger type
                            $tablenameto="ourbank_Income";
                        } elseif($toledgertype=="Expenditure") {
                            $tablenameto="ourbank_Expenditure";
                        }elseif($toledgertype=="Assets") {
                            $tablenameto="ourbank_Assets";
                        } elseif($toledgertype=="Liabilities") {
                            $tablenameto="ourbank_Liabilities";
                        }



			$Transactiondata = (array('transaction_id'=>'',
					'account_id' => '',
					'glsubcode_id_from' => '',
					'glsubcode_id_to' => $toglsubcodeid,
					'transaction_date'=>$transactiondate,
					'amount_to_bank'=>$amount,
					'amount_from_bank'=>'',
					'paymenttype_id'=>$paymenttype,
					'transactiontype_id'=>'1',
					'recordstatus_id'=>'3',
					'reffering_vouchernumber'=>$paymenttype_details,
					'transaction_description'=>$description,
					'balance'=>$balance,
					'confirmation_flag'=>'',
                                        'created_by'=>$this->view->createdby,
					'created_date'=>date("Y-m-d")
			)); //echo '<pre>'; print_r($Transactiondata); echo $toglsubcodeid; echo "/"; echo $tablenameto;
			        $transaction_id=$receipts->addtransactions($Transactiondata);
                                $fromglsubcodeid=0;
                                $receipts->addtoaccounts($tablenameto,$branchid,$fromglsubcodeid,$toglsubcodeid,$transaction_id,$amount);
}
            if($flag==0){
                $this->_redirect('transaction');
            }
                                        }
                                }
                        }
		}

        function getglsubcodeAction()                           //to get the from glsubcode
            {
	      $this->_helper->layout->disableLayout();
               $glcode=$this->_request->getParam('glcode');
               $username=$this->view->username;
               $receipts= new Receipts_Model_Receipts();
               $this->view->glsubcodeid=$receipts->listOfglsubcode($glcode,$username);
	   }

        public function getbalanceAction()                              //to get to glsubcode balance
        {
            $this->_helper->layout->disableLayout();
            $glsubcode=$this->_request->getParam('glsubcode');
            $this->view->adm = new App_Model_Adm();
            $receipts= new Receipts_Model_Receipts();
            $toledgertype=''; $tablenameto='';
            if ($glsubcode) {
                $toledgercode=$receipts->listOfsubledgercode($glsubcode);
                foreach($toledgercode as $toledgercodes) {
                $toledgertype=$toledgercodes['name'];
                            } }

            if($toledgertype=="Income") {
                            $tablenameto="ourbank_Income";
                        } elseif($toledgertype=="Expenditure") {
                            $tablenameto="ourbank_Expenditure";
                        }elseif($toledgertype=="Assets") {
                            $tablenameto="ourbank_Assets";
                        } elseif($toledgertype=="Liabilities") {
                            $tablenameto="ourbank_Liabilities";
                        }

            $this->view->balance2=$receipts->getbalance($glsubcode,$tablenameto);
        }

         public function latestbalancetoAction()                  //to get latest balance after amount addition for tosubledger
        {
            $this->_helper->layout->disableLayout();
            $amt=$this->_request->getParam('amount');
            $tobalance=$this->_request->getParam('balance2');
            $tolatestbalance = $tobalance + $amt; 
            $this->view->latestbalanceto = $tolatestbalance; 
        }

}

