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
class Usercommonview_IndexController extends Zend_Controller_Action
{
    public function init() 
    {
        $this->view->pageTitle='User';
        // calling model			
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
 
        //         if (($this->view->globalvalue[0]['id'] == 0)) {
        //              $this->_redirect('index/logout');
        //         }
        $this->view->adm = new App_Model_Adm();
    }

    public function commonviewAction()
    {
        // 		$this->view->title = "View user";
        // 		//Acl
        //      $access = new App_Model_Access();
        //      $checkaccess = $access->accessRights('User',$this->view->globalvalue[0]['name'],'commonviewAction');
        //      if (($checkaccess != NULL)) {
        // calling search form
        // 		$SectForm = new Sectors_Form_Search();
        // 		$this->view->form = $SectForm;
        //getting the id
            $id = $this->_getParam('id');
            $this->view->id = $id;
        //getting the model
            $userdetails=new User_Model_User();
            $user_details1=$userdetails->getUser($id);
            $module=$userdetails->getmodule('User');
            foreach($module as $module_id){ }
        //displaying the submodule details
            $this->view->mod_id=$module_id['parent'];
            $this->view->sub_id=$module_id['module_id'];
            $this->view->userdetails = $user_details1;
            $this->view->address = $this->view->adm->getModule("ourbank_address",$id,"User");
            $this->view->contact = $this->view->adm->getModule("ourbank_contact",$id,"User");
        //         } else {
        //            		 $this->_redirect('index/error');
        //     }
    }
}