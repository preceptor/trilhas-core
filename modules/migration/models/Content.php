<?php
class Migration_Model_Content
{
    /**
     * Export course content to a zip file
     *
     * @param integer $courseId
     * @return ZipArchive 
     */
    public function export($courseId)
    {
        $course = new Tri_Db_Table_Course();
        $row = $course->fetchRow(array('id = ?' => $courseId));
        
        if (!$row) {
            throw new Exception('Invalid course');
        }
        
        $content = new Zend_Db_Table('content');
        $pages = $content->fetchAll(array('course_id = ?' => $row->id));
        
        if (!count($pages)) {
            throw new Exception('There are no contents for this course');
        }
        
        $zip = new ZipArchive();
        $res = $zip->open($this->getFilename($row->id), ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
        if ($res !== true) {
            throw new Exception('Error while creating zip file');
        }
        
        $tree = Zend_Json::encode(Application_Model_Content::fetchAllOrganize($courseId));
        $zip->addFromString('tree.json', $tree);
        $zip->addEmptyDir('static');
        
        foreach ($pages as $page) {
            $name = $page->id . ".html";
            $description = $this->resolveImages($zip, utf8_decode($page->description));
            $zip->addFromString($name, $description);
        }
        
        return $zip;
    }
    
    public function import($courseId, $filename)
    {
        $zip = new ZipArchive();
        $res = $zip->open($filename);
        
        if ($res !== true) {
            throw new Exception('Error while creating zip file');
        }
       
        $tree = Zend_Json::decode($zip->getFromName('tree.json'));
        
        if (!count($tree)) {
            throw new Exception('Error while loading tree');
        }
        
        $content   = new Zend_Db_Table('content');
        $contentId = array();
        foreach ($tree as $page) {
            $level       = $page['level'];
            $html        = $zip->getFromName($page['id'].'.html');
            $description = $this->moveImages($zip, $html);
            
            $data  = array('content_id'  => @$contentId[$level],
                           'title'       => $page['title'],
                           'description' => $description,
                           'course_id'   => $courseId);
            
            $contentId[$level+1] = $content->insert($data);
            
        }
        
        return true;
    }
    
    protected function moveImages($zip, $html)
    {
        $doc = new DOMDocument();
        if (@$doc->loadHTML($html)) {
            $images = $doc->getElementsByTagName('img');

            if (count($images)) {
                foreach ($images as $img) {
                    $src = $img->getAttribute('src');
                    
                    if (!strpos($src, 'http://')) {
                        $content = $zip->getFromName($src); 
                        
                        if ($content) {
                            $newSource = SERVER_URL . $src;
                            $name      = str_replace('static/', '', $src);
                            file_put_contents(APPLICATION_PATH . '/../data/upload/' . $name, $content);
                            $img->setAttribute('src', $newSource);
                        }
                    }
                }
            }
            return $doc->saveHTML();
        }
        return $html;
    }
    
    /**
     * Get images from content and put in static folder
     *
     * @param ZipArchive $zip
     * @param string $html
     * @return string 
     */
    protected function resolveImages($zip, $html)
    {
        $doc = new DOMDocument();
        if (@$doc->loadHTML($html)) {
            $images = $doc->getElementsByTagName('img');

            if (count($images)) {
                foreach ($images as $img) {
                    $src = $img->getAttribute('src');
                    $content = @file_get_contents($src); 
                    
                    if (!$content) {
                        $src = SERVER_URL . $src;
                        $content = @file_get_contents($src); 
                    }
                    
                    if ($content) {
                        $newSource = 'static/' . basename($src);
                        $zip->addFromString($newSource, $content);
                        $img->setAttribute('src', $newSource);
                    }
                }
            }
            return $doc->saveHTML();
        }
        return $html;
    }
    
    public function getFilename($courseId)
    {
        return APPLICATION_PATH . '/../data/content'.$courseId.'.zip';
    }
}