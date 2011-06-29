<?php
class Content_Model_Restriction
{
    public static $restriction = array();
    
    public static function verify($contentId)
    {
		$in = self::_getParentContent($contentId);
        self::_verifyPanel($in);

        return self::$restriction;
    }

    private function _verifyPanel($in)
    {
		$session = new Zend_Session_Namespace('data');
        $table = new Tri_Db_Table('restriction_panel');

		$select = $table->select()
                        ->where('classroom_id = ?' , $session->classroom_id)
			            ->where('content_id IN(?)', $in);

		$result = $table->fetchAll($select);
        $panelNote = new Tri_Db_Table('panel_note');
		
        if (count($result)) {
        	foreach ($result as $rs) {
            	$note = $panelNote->fetchRow(array('panel_id = ?' => $rs->panel_id))->note;
            	
				if ($note < $rs->note) {
					self::$restriction['has']     = true;
                    self::$restriction['content'] = "restriction content, note must have more than %value%";
                    self::$restriction['value']   = $rs->note;
                    return false;
				}
				
				if ($note < $rs->note_restriction) {
					self::_verifyTime($in);
				}
            }
        } else {
            self::_verifyTime($in);
        }
            
    }
    
    private function _verifyTime($in)
    {
    	$session = new Zend_Session_Namespace('data');
        $table = new Tri_Db_Table('restriction_time');

		$select = $table->select()
                        ->where('classroom_id = ?' , $session->classroom_id)
			            ->where('content_id IN(?)', $in);

		$result = $table->fetchAll($select);

        if( count($result) ){
        	foreach ($result as $rs) {
				$started  = (float) preg_replace('/[^0-9]/','',$rs->begin);
				$finished = (float) preg_replace('/[^0-9]/','',$rs->end);
				$today    = (float) date('Ymd');

				if ($started > $today) {
					self::$restriction['has']     = true;
					self::$restriction['content'] = "restricted content, access after %value%";
					self::$restriction['value']   = Zend_Filter::filterStatic($rs->begin, 'date', array(), 'Tri_Filter');
					return false;
				}
                
                if ($finished) {
                    if ($finished < $today) {
                        self::$restriction['has']     = true;
                        self::$restriction['content'] = "content expired since the %value%";
                        self::$restriction['value']   = Zend_Filter::filterStatic($rs->end, 'date', array(), 'Tri_Filter');
                        return false;
                    }
                }
			}
    	}
    }

    private function _getParentContent($id){
		$session  = new Zend_Session_Namespace('data');
		$contents = Zend_Json::decode($session->contents);

		foreach($contents as $content){
			if ($content['id'] != $id) {
				$tmp[] = (int) $content['id'];
			} else {
				$tmp[] = (int) $content['id'];
				return $tmp;
			}
		}

		return 0;
	}
}