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
/*
 *  model page for fetch and return filtered search office type, office
 */
class Office_Model_office extends Zend_Db_Table_Abstract {

    protected $_name = 'ourbank_officehierarchy';
    public function SearchOffice($officeid,$shortname,$officename) {
        $select = $this->select()
                       ->setIntegrityCheck(false)  
                       ->join(array('a' => 'ourbank_office'),array('a.id','a.parentoffice_id'))
                       ->join(array('b'=>'ourbank_officehierarchy'),'b.id=a.officetype_id',array('b.type'))
                       ->where('a.name like "%" ? "%"',$officename)
                       ->where('a.short_name like "%" ? "%"',$shortname)
                       ->where('a.officetype_id like "%" ? "%"',$officeid)
                       ->order(array('a.id DESC'));
       $result = $this->fetchAll($select);
	//return filtered office details
      return $result->toArray();
    }

        public function getOfficetype() {
              $select = $this->select()
                       ->setIntegrityCheck(false)  
                       ->join(array('a' => 'ourbank_officehierarchy'),array('a.id,a.type'));
       $result = $this->fetchAll($select);
	//return office type
       return $result->toArray();
    }

     public function getOffice() {
              $select = $this->select()
                       ->setIntegrityCheck(false)  
                       ->join(array('a' => 'ourbank_office'),array('a.id'))
                       ->join(array('b'=>'ourbank_officehierarchy'),'b.id=a.officetype_id',array('b.type'))
                       ->order(array('a.id DESC'));
//                        die($select->__toString($select));
       $result = $this->fetchAll($select);
	//return office details
       return $result->toArray();
    }
}
