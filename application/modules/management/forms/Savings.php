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
?>

<?php
/**  
 * class is used to create a form for SavingInstance details along with the validation
*/
class Management_Form_Savings extends Zend_Form
{
	public function init()
	{
		$offerproduct_id = new Zend_Form_Element_Hidden('offerproduct_id');
		$productshortname = new Zend_Form_Element_Text('productshortname');
		$currentdates = new Zend_Form_Element_Hidden('currentdates');

		$productType = new Zend_Form_Element_Select('productType');
		$productType->addMultiOption('','Select...');
		$productType->setAttrib('class', 'txt_put');
		$productType->setAttrib('id', 'productType');
		$productType->setAttrib('onchange', 'getSavingAccount(this.value)');

		$savingproducttype = new Zend_Form_Element_Text('savingproductname');
		$savingproducttype->setAttrib('class', 'txt_put');
		$savingproducttype->setAttrib('id', 'savingproductname');
		$savingproducttype->setAttrib('readonly', 'true');

		$offerproductname = new Zend_Form_Element_Text('offerproductname');
		$offerproductname->setAttrib('class', 'txt_put');
		$offerproductname->setAttrib('id','offerproductname');
		$offerproductname->addValidator(new Zend_Validate_Db_NoRecordExists('ourbank_productdetails', 'productname'));
		$offerproductname->setRequired(true);

		$offerproductshortname = new Zend_Form_Element_Text('offerproductshortname');
		$offerproductshortname->setAttrib('class', 'txt_put');
		$offerproductshortname->setAttrib('id', 'offerproductshortname');
		$offerproductshortname->setRequired(true)
						->addValidators(array(array('NotEmpty'),array('stringLength', false, array(1, 3))));


		$offerproduct_description = new Zend_Form_Element_Textarea('offerproduct_description');
		$offerproduct_description->setAttrib('class', 'textfield');
		$offerproduct_description->setAttrib('id', 'offerproduct_description');
		$offerproduct_description->setAttrib('rows','2');
		$offerproduct_description->setAttrib('cols','23');
		$offerproduct_description->setRequired(true);

		$begindate = new Zend_Form_Element_Text('begindate');
		$begindate->setAttrib('class', 'txt_put');
		$begindate->setAttrib('id', 'begindate');
		$begindate->setRequired(true)
	   					->addValidator(new Zend_Validate_Date('YYYY-MM-DD'),true,
	   				array('messages' =>array(Zend_Validate_Date::FALSEFORMAT => 'Enter the valid date')));

		$closedate = new Zend_Form_Element_Text('closedate');
		$closedate->setAttrib('class', 'txt_put');
		$closedate->setAttrib('id', 'closedate');
		$closedate->setRequired(true)
	   					->addValidator(new Zend_Validate_Date('YYYY-MM-DD'),true,
	   				array('messages' =>array(Zend_Validate_Date::FALSEFORMAT => 'Enter the valid date')));

		$applicableto = new Zend_Form_Element_Select('applicableto');
		$applicableto->addMultiOption('','Select...');
		$applicableto->setAttrib('class', 'txt_put');
		$applicableto->setAttrib('id', 'applicableto');
		$applicableto->setRequired(true);

		$minmumdeposit = new Zend_Form_Element_Text('minmumdeposit');
		$minmumdeposit->setAttrib('class', 'txt_put');
		$minmumdeposit->setAttrib('id', 'minmumdeposit');
		$graterthan=new Zend_Validate_GreaterThan(0);
        $minmumdeposit->setRequired(true)
				       ->addValidators(array(array('NotEmpty'),array('Float'),array($graterthan,true)));

		$frequencyofdeposit = new Zend_Form_Element_Select('frequencyofdeposit');
		$frequencyofdeposit->addMultiOption('','Select...');
		$frequencyofdeposit->setAttrib('class', 'txt_put');
		$frequencyofdeposit->setAttrib('id', 'frequencyofdeposit');
		$frequencyofdeposit->setRequired(true);

		$Int_timefrequency_id = new Zend_Form_Element_Select('Int_timefrequency_id');
		$Int_timefrequency_id->addMultiOption('','Select...');
		$Int_timefrequency_id->setAttrib('class', 'txt_put');
		$Int_timefrequency_id->setAttrib('id', 'Int_timefrequency_id');
		$Int_timefrequency_id->setRequired(true);

		$frequencyofinterestupdating = new Zend_Form_Element_Select('frequencyofinterestupdating');
		$frequencyofinterestupdating->setAttrib('class', 'txt_put');
		$frequencyofinterestupdating->setAttrib('id', 'frequencyofinterestupdating');
		$frequencyofinterestupdating->addMultiOption('','Select...');
		$frequencyofinterestupdating->addMultiOption('MinBalance','MinBalance');
		$frequencyofinterestupdating->addMultiOption('AvgBalance','AvgBalance');
		$frequencyofinterestupdating->setRequired(true);

		$minimumbalanceforinterest = new Zend_Form_Element_Text('minimumbalanceforinterest');
		$minimumbalanceforinterest->setAttrib('class', 'txt_put');
		$minimumbalanceforinterest->setAttrib('id', 'minimumbalanceforinterest');
		$graterthan=new Zend_Validate_GreaterThan(0);
        $minimumbalanceforinterest->setRequired(true)
				       ->addValidators(array(array('NotEmpty'),array('Float'),array($graterthan,true)));



		$minimum_deposit_amount  = new Zend_Form_Element_Text('minimum_deposit_amount');
		$minimum_deposit_amount->setAttrib('class', 'txt_put');
		$minimum_deposit_amount->setAttrib('id', 'minimum_deposit_amount');
		$graterthan=new Zend_Validate_GreaterThan(0);
        $minimum_deposit_amount->setRequired(true)
				       ->addValidators(array(array('NotEmpty'),array('Float'),array($graterthan,true)));

		$maximum_deposit_amount  = new Zend_Form_Element_Text('maximum_deposit_amount');
		$maximum_deposit_amount->setAttrib('class', 'txt_put');
		$maximum_deposit_amount->setAttrib('id', 'maximum_deposit_amount');
		$graterthan=new Zend_Validate_GreaterThan(0);
        $maximum_deposit_amount->setRequired(true)
				       ->addValidators(array(array('NotEmpty'),array('Float'),array($graterthan,true)));

		$frequency_of_deposit = new Zend_Form_Element_Select('frequency_of_deposit');
		$frequency_of_deposit->addMultiOption('','Select...');
		$frequency_of_deposit->setAttrib('class', 'txt_put');
		$frequency_of_deposit->setAttrib('id', 'frequency_of_deposit');
		$frequency_of_deposit->setRequired(true);

		$penal_Interest  = new Zend_Form_Element_Text('penal_Interest');
		$penal_Interest->setAttrib('class', 'txt_put');
		$penal_Interest->setAttrib('id', 'penal_Interest');
		$graterthan=new Zend_Validate_GreaterThan(0);
        $penal_Interest->setRequired(true)
				       ->addValidators(array(array('NotEmpty'),array('Float'),array($graterthan,true)));


		$submit = new Zend_Form_Element_Submit('Submit');
		$submit->setAttrib('class', 'savings');
		$submit->setAttrib('id', 'savings');

		$this->addElements( array($productType,$offerproductname,$offerproductshortname,$offerproduct_description,$begindate,$closedate,$applicableto,$minmumdeposit,$frequencyofdeposit,$Int_timefrequency_id,$frequencyofinterestupdating,$minimumbalanceforinterest,$minimum_deposit_amount,$maximum_deposit_amount,$frequency_of_deposit,$penal_Interest,$savingproducttype,$submit,$offerproduct_id,$productshortname,$currentdates));
	}
}
/** end of class */