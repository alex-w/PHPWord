<?php

/**
* Acknowledgement: This code is based on the work of Evan Shaw and others
* https://github.com/PHPOffice/PHPWord/issues/1462#issuecomment-1991847158
*/

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * ListItem element HTML writer
 *
 * @since 0.10.0
 */
class ListItemRun extends TextRun
{
    /**
     * Write list item
     *  
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\ListItemRun) {
            return '';
        }
        $content = '';
        $content .= sprintf(
            '<li data-depth="%s" data-liststyle="%s" data-numId="%s">',
            $this->element->getDepth(),
            $this->getListFormat($this->element->getDepth()),
            $this->getListId()
        );

        $namespace = 'PhpOffice\\PhpWord\\Writer\\HTML\\Element';
        $container = $this->element;

        $elements = $container->getElements();
        foreach ($elements as $element) {
            $elementClass = get_class($element);
            $writerClass = str_replace('PhpOffice\\PhpWord\\Element', $namespace, $elementClass);
            if (class_exists($writerClass)) {
                /** @var \PhpOffice\PhpWord\Writer\HTML\Element\AbstractElement $writer Type hint */
                $writer = new $writerClass($this->parentWriter, $element, true);
                $content .= $writer->write();
            }
        }

        $content .= '</li>';
        $content .= "\n";
        return $content;
    }

    public function getListFormat($depth)
    {
        return $this->element->getStyle()->getNumStyle();
    }

    public function getListId()
    {
        return $this->element->getStyle()->getNumId();
    }
}
