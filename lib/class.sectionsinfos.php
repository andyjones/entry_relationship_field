<?php
/**
 * Copyright: Deux Huit Huit 2017
 * LICENCE: MIT https://deuxhuithuit.mit-license.org
 */
class SectionsInfos
{
    private static $deepness = 0;
    public static function fetch($sections)
    {
        self::$deepness++;
        $options = array();
        $sections = SectionManager::fetch($sections);
        if (!empty($sections)) {
            foreach ($sections as $section) {
                $section_fields = $section->fetchFields();
                if(!is_array($section_fields)) {
                    continue;
                }

                $fields = array();
                foreach($section_fields as $f) {
                    $fd = General::intval($f->get('deepness'));
                    if ($fd > 0 && self::$deepness > $fd) {
                        continue;
                    }
                    $modes = $f->fetchIncludableElements();
                    
                    if (is_array($modes)) {
                        // include default
                        $fields[] = array(
                            'id' => $f->get('id'),
                            'name' => $f->get('label'),
                            'handle' => $f->get('element_name'),
                            'type' => $f->get('type'),
                            'default' => true,
                        );
                        if (count($modes) > 1) {
                            foreach ($modes as $mode) {
                                $fields[] = array(
                                    'id' => $f->get('id'),
                                    'name' => $f->get('label'),
                                    'handle' => $mode,
                                    'type' => $f->get('type')
                                );
                            }
                        }
                    }
                }

                $options[] = array(
                    'name' => $section->get('name'),
                    'handle' => $section->get('handle'),
                    'fields' => $fields
                );
            }
        }
        self::$deepness--;
        return $options;
    }
}
